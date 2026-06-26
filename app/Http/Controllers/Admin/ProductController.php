<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
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
        $validated = $request->validate([
            "name" => "required|string|unique:products,name",
            "description" => "required|string|max:1000",
            "price" => "required|numeric|min:0",
            "category_id" => "required|exists:categories,id",
            "sizes" => "required|array|min:1",
            "sizes.*.name" => "required|string",
            "sizes.*.stock" => "required|integer|min:0",
            "images" => "nullable|array",
            "images.*" => "image|mimes:jpeg,png,jpg,gif,webp|max:2048",
        ]);
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

            if ($request->hasFile('images')) {
                $manager = new ImageManager(new Driver());
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
        return view("admin.products.edit", compact("product", "categories") );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
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
        ]);
        $validated["slug"] = Str::slug($request->name);

        try {
            DB::beginTransaction();

            $product->update([
                "name" => $validated["name"],
                "slug" => $validated["slug"],
                "description" => $validated["description"],
                "price" => $validated["price"],
                "category_id" => $validated["category_id"],
            ]);

            // Synchronize sizes to prevent deleting ordered items
            $submittedSizeIds = collect($request->sizes)->pluck('id')->filter()->toArray();

            // 1. Delete sizes that are not in the submitted IDs
            $product->sizes()->whereNotIn('id', $submittedSizeIds)->delete();

            // 2. Loop through and update or create sizes
            foreach ($request->sizes as $sizeData) {
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

            if ($request->hasFile('images')) {
                $manager = new ImageManager(new Driver());
                $existingImagesCount = $product->images()->count();
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
                        'is_thumbnail' => ($existingImagesCount === 0 && $index === 0),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route("admin.products.index")->with("success", "Product berhasil diupdate");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with("error", "Gagal memperbarui product: " . $e->getMessage());
        }
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
                if(Storage::disk('public')->exists($image->image_url)){
                    Storage::disk('public')->delete($image->image_url);
                }
            }
            $product->delete();
            
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

            if(Storage::disk('public')->exists($image->image_url)){
                Storage::disk('public')->delete($image->image_url);
            }

            $productId = $image->product_id;
            $wasThumbnail = $image->is_thumbnail;
            
            $image->delete();

            // If the deleted image was a thumbnail, set another image as thumbnail
            if ($wasThumbnail) {
                $product = Product::find($productId);
                if ($product && $product->images()->count() > 0) {
                    $firstImage = $product->images()->first();
                    $firstImage->update(['is_thumbnail' => true]);
                }
            }

            DB::commit();
            return redirect()->back()->with("success", "Foto product berhasil dihapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", "Gagal menghapus foto product: " . $e->getMessage());
        }
    }
}
