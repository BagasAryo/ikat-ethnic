<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportTransactionSheet implements FromArray, WithTitle, WithEvents, WithColumnWidths
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function title(): string
    {
        return 'Data Transaksi';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,  // No
            'B' => 24, // Order ID
            'C' => 24, // Pelanggan
            'D' => 30, // Email
            'E' => 20, // Tanggal
            'F' => 50, // Produk Dipesan
            'G' => 18, // Ongkir
            'H' => 18, // Total
            'I' => 26, // Status Pembayaran
            'J' => 18, // Status
        ];
    }

    public function array(): array
    {
        $rows = [];

        // Transaction header
        $rows[] = ['No', 'Order ID', 'Pelanggan', 'Email', 'Tanggal', 'Produk Dipesan', 'Ongkos Kirim', 'Total Bayar', 'Status Pembayaran', 'Status Transaksi'];

        // Transaction data
        $no = 1;
        foreach ($this->orders as $order) {
            $itemsDetail = $order->orderItems->map(fn($item) =>
                $item->product_name . ' (' . $item->product_size . ') x' . $item->quantity
            )->implode('; ');

            $paymentStatus = $order->payment ? ucfirst($order->payment->status) : 'Unpaid';
            $paymentMethod = $order->payment ? ' (' . strtoupper($order->payment->payment_method) . ')' : '';

            $rows[] = [
                $no++,
                $order->order_number,
                $order->user?->name ?? '-',
                $order->user?->email ?? '-',
                $order->created_at->format('d M Y, H:i'),
                $itemsDetail,
                (float) $order->shipping_cost,
                (float) $order->total_amount,
                $paymentStatus . $paymentMethod,
                $order->status,
            ];
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $orderCount = $this->orders->count();
                $lastRow = $orderCount + 1; // +1 for header

                // Header Style
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF1F2937'], // Contrast header (Dark gray/blue)
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']],
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Data Style
                if ($orderCount > 0) {
                    $sheet->getStyle("A2:J{$lastRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    // Format Currency
                    $sheet->getStyle("G2:G{$lastRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getStyle("H2:H{$lastRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');

                    // Wrap text for products column
                    $sheet->getStyle("F2:F{$lastRow}")->getAlignment()->setWrapText(true);
                }

                // Freeze Header Row for Easy Scrolling
                $sheet->freezePane('A2');

                // Page setup
                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);
            },
        ];
    }
}
