<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Typography\FontFactory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'sizes', 'images'])->orderBy("id", "asc")->paginate(5);
        return view("admin.products.index", compact("products"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view("admin.products.create", compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            "name" => "required|string|unique:products,name",
            "description" => "required|string|max:1000",
            "price" => "required|numeric|min:0",
            "category_id" => "required|exists:categories,id",
            "sizes" => "required|array|min:1",
            "sizes.*.name" => "required|string",
            "sizes.*.stock" => "required|integer|min:0",
            "images" => "nullable|array",
            "images.*" => "image|mimes:jpeg,png,jpg,gif,webp|max:2048",
        ];

        $messages = [
            'name.unique' => 'Nama produk sudah digunakan, silakan gunakan nama lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'sizes.required' => 'Minimal harus ada 1 ukuran produk.',
            'sizes.*.name.required' => 'Nama ukuran wajib diisi (misal: S, M, L).',
            'sizes.*.stock.required' => 'Stok ukuran wajib diisi.',
            'images.*.image' => 'File yang diupload harus berupa gambar.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];

        $validated = $request->validate($rules, $messages);
        $validated["slug"] = Str::slug($request->name);

        try {
            DB::beginTransaction();

            $product = Product::create([
                "name" => $validated["name"],
                "slug" => $validated["slug"],
                "description" => $validated["description"],
                "price" => $validated["price"],
                "category_id" => $validated["category_id"],
            ]);

            foreach ($request->sizes as $size) {
                $product->sizes()->create([
                    'name' => $size['name'],
                    'stock' => $size['stock'],
                ]);
            }

            $photosCount = 0;
            if ($request->hasFile('images')) {
                $manager = new ImageManager(new Driver());
                $photosCount = count($request->file('images'));
                foreach ($request->file('images') as $index => $file) {
                    $fileName = Str::slug($request->name) . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                    $path = 'products/' . $fileName;

                    $image = $manager->decode($file->getRealPath());
                    $image->text('IKAT ETHNIC', $image->width() / 2, $image->height() / 2, function (FontFactory $font) use ($image) {
                        $font->file(public_path('fonts/Roboto-Bold.ttf'));
                        $font->size(max(32, $image->width() / 15));
                        $font->color('rgba(255, 255, 255, 0.5)');
                        $font->align('center', 'center');
                    });

                    Storage::disk('public')->put($path, (string) $image->encode());

                    $product->images()->create([
                        'image_url' => $path,
                        'is_thumbnail' => $index === 0,
                    ]);
                }
            }

            // PERBAIKAN: AdminLog::create() dipindah ke DALAM transaksi,
            // sebelum DB::commit() — konsisten dengan pola di update()/destroy().
            // Kalau logging gagal, seluruh proses (termasuk pembuatan produk)
            // ikut di-rollback, dan pesan error yang ditampilkan ke user jadi akurat.
            $createDescription = "Menambahkan produk baru: {$product->name} (Rp" . number_format($product->price, 0, ',', '.') . ")";
            if ($photosCount > 0) {
                $createDescription .= " — {$photosCount} foto diunggah";
            }

            AdminLog::create([
                'user_id' => auth()->id(),
                'action' => 'CREATE_PRODUCT',
                'description' => $createDescription,
            ]);

            DB::commit();

            return redirect()->route("admin.products.index")->with("success", "Product berhasil ditambahkan");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with("error", "Gagal menambahkan product: " . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $product = Product::with(['sizes', 'images'])->findOrFail($id);
        return view("admin.products.edit", compact("product", "categories"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        $rules = [
            "name" => "required|string|unique:products,name," . $id,
            "description" => "required|string|max:1000",
            "price" => "required|numeric|min:0",
            "category_id" => "required|exists:categories,id",
            "sizes" => "required|array|min:1",
            "sizes.*.id" => "nullable|exists:product_sizes,id",
            "sizes.*.name" => "required|string",
            "sizes.*.stock" => "required|integer|min:0",
            "images" => "nullable|array",
            "images.*" => "image|mimes:jpeg,png,jpg,webp,gif|max:2048",
        ];

        $messages = [
            'name.unique' => 'Nama produk sudah digunakan, silakan gunakan nama lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'sizes.required' => 'Minimal harus ada 1 ukuran produk.',
            'sizes.*.name.required' => 'Nama ukuran wajib diisi (misal: S, M, L).',
            'sizes.*.stock.required' => 'Stok ukuran wajib diisi.',
            'images.*.image' => 'File yang diupload harus berupa gambar.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];

        $validated = $request->validate($rules, $messages);
        $validated["slug"] = Str::slug($request->name);

        // Simpan nilai lama SEBELUM di-update, untuk dibandingkan nanti
        $oldPrice = $product->price;
        $oldStockBySize = $product->sizes->pluck('stock', 'name')->toArray();

        try {
            DB::beginTransaction();

            $product->update([
                "name" => $validated["name"],
                "slug" => $validated["slug"],
                "description" => $validated["description"],
                "price" => $validated["price"],
                "category_id" => $validated["category_id"],
            ]);

            $this->syncProductSizes($product, $request->sizes);

            $newPhotosCount = $this->uploadProductImages($product, $request->file('images'), $request->name);

            $this->logProductChanges($product, $oldPrice, $oldStockBySize, $newPhotosCount, $validated['price'], $request->user()?->id);

            DB::commit();
            return redirect()->route("admin.products.index")->with("success", "Product berhasil diupdate");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with("error", "Gagal memperbarui product: " . $e->getMessage());
        }
    }

    private function syncProductSizes(Product $product, array $sizes)
    {
        $submittedSizeIds = collect($sizes)->pluck('id')->filter()->toArray();

        // 1. Delete sizes that are not in the submitted IDs
        $product->sizes()->whereNotIn('id', $submittedSizeIds)->delete();

        // 2. Loop through and update or create sizes
        foreach ($sizes as $sizeData) {
            if (isset($sizeData['id'])) {
                $product->sizes()->where('id', $sizeData['id'])->update([
                    'name' => $sizeData['name'],
                    'stock' => $sizeData['stock'],
                ]);
            } else {
                $product->sizes()->create([
                    'name' => $sizeData['name'],
                    'stock' => $sizeData['stock'],
                ]);
            }
        }
    }

    private function uploadProductImages(Product $product, ?array $images, string $productName): int
    {
        if (empty($images)) {
            return 0;
        }

        $manager = new ImageManager(new Driver());
        $existingImagesCount = $product->images()->count();
        $newPhotosCount = count($images);

        foreach ($images as $index => $file) {
            $fileName = Str::slug($productName) . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
            $path = 'products/' . $fileName;

            $image = $manager->decode($file->getRealPath());
            $image->text('IKAT ETHNIC', $image->width() / 2, $image->height() / 2, function (FontFactory $font) use ($image) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(max(32, $image->width() / 15));
                $font->color('rgba(255, 255, 255, 0.5)');
                $font->align('center', 'center');
            });

            Storage::disk('public')->put($path, (string) $image->encode());

            $product->images()->create([
                'image_url' => $path,
                'is_thumbnail' => ($existingImagesCount === 0 && $index === 0),
            ]);
        }

        return $newPhotosCount;
    }

    private function logProductChanges(Product $product, float $oldPrice, array $oldStockBySize, int $newPhotosCount, float $newPrice, ?int $userId)
    {
        $changes = [];

        if ((float) $oldPrice !== (float) $newPrice) {
            $changes[] = "Harga: Rp" . number_format($oldPrice, 0, ',', '.') . " -> Rp" . number_format($newPrice, 0, ',', '.');
        }

        $product->load('sizes'); // ambil data ukuran terbaru setelah sync di atas
        $newStockBySize = $product->sizes->pluck('stock', 'name')->toArray();

        foreach ($newStockBySize as $sizeName => $newStock) {
            $oldStock = $oldStockBySize[$sizeName] ?? null;
            if ($oldStock === null) {
                $changes[] = "Ukuran {$sizeName} ditambahkan (stok: {$newStock})";
            } elseif ((int) $oldStock !== (int) $newStock) {
                $changes[] = "Stok {$sizeName}: {$oldStock} -> {$newStock}";
            }
        }

        foreach ($oldStockBySize as $sizeName => $oldStock) {
            if (!array_key_exists($sizeName, $newStockBySize)) {
                $changes[] = "Ukuran {$sizeName} dihapus";
            }
        }

        if ($newPhotosCount > 0) {
            $changes[] = $newPhotosCount === 1
                ? "Menambahkan 1 foto baru"
                : "Menambahkan {$newPhotosCount} foto baru";
        }

        $description = "Memperbarui produk: {$product->name}";
        if (!empty($changes)) {
            $description .= " (" . implode('; ', $changes) . ")";
        }

        AdminLog::create([
            'user_id' => $userId,
            'action' => 'UPDATE_PRODUCT',
            'description' => $description,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);

            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
            }

            $name = $product->name;
            $product->delete();

            AdminLog::create([
                'user_id' => auth()->id(),
                'action' => 'DELETE_PRODUCT',
                'description' => "Menghapus produk: {$name}",
            ]);

            DB::commit();
            return redirect()->route("admin.products.index")->with("success", "Product berhasil dihapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route("admin.products.index")->with("error", "Gagal menghapus product: " . $e->getMessage());
        }
    }

    /**
     * Remove the specified image from storage.
     */
    public function destroyImage(string $id)
    {
        try {
            DB::beginTransaction();
            $image = ProductImage::findOrFail($id);

            if (Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }

            $productId = $image->product_id;
            $wasThumbnail = $image->is_thumbnail;
            $productName = $image->product?->name ?? "Produk #{$productId}";

            $image->delete();

            // If the deleted image was a thumbnail, set another image as thumbnail
            if ($wasThumbnail) {
                $product = Product::find($productId);
                if ($product && $product->images()->count() > 0) {
                    $firstImage = $product->images()->first();
                    $firstImage->update(['is_thumbnail' => true]);
                }
            }

            // PERBAIKAN: sebelumnya method ini tidak mencatat log sama sekali.
            AdminLog::create([
                'user_id' => auth()->id(),
                'action' => 'DELETE_PRODUCT_IMAGE',
                'description' => "Menghapus salah satu foto produk: {$productName}",
            ]);

            DB::commit();
            return redirect()->back()->with("success", "Foto product berhasil dihapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", "Gagal menghapus foto product: " . $e->getMessage());
        }
    }
}
