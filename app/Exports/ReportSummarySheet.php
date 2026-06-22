<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportSummarySheet implements FromArray, WithTitle, WithEvents, WithColumnWidths
{
    protected $dateFrom;
    protected $dateTo;
    protected $orders;
    protected $totalOrders;
    protected $totalRevenue;
    protected $totalItemsSold;
    protected $totalNewUsers;
    protected $statusBreakdown;
    protected $topProducts;

    public function __construct(
        Carbon $dateFrom,
        Carbon $dateTo,
        $orders,
        int $totalOrders,
        $totalRevenue,
        $totalItemsSold,
        int $totalNewUsers,
        $statusBreakdown,
        $topProducts
    ) {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->orders = $orders;
        $this->totalOrders = $totalOrders;
        $this->totalRevenue = $totalRevenue;
        $this->totalItemsSold = $totalItemsSold;
        $this->totalNewUsers = $totalNewUsers;
        $this->statusBreakdown = $statusBreakdown;
        $this->topProducts = $topProducts;
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 24,
            'C' => 24,
            'D' => 30,
            'E' => 20,
            'F' => 50,
            'G' => 18,
            'H' => 18,
            'I' => 26,
            'J' => 18,
        ];
    }

    public function array(): array
    {
        $rows = [];

        // Row 1: Main title
        $rows[] = ['LAPORAN PENJUALAN — IKAT ETHNIC', '', '', '', '', '', '', '', '', ''];
        // Row 2: Period
        $rows[] = ['Periode: ' . $this->dateFrom->format('d M Y') . ' — ' . $this->dateTo->format('d M Y'), '', '', '', '', '', '', '', '', ''];
        // Row 3: Spacer
        $rows[] = ['', '', '', '', '', '', '', '', '', ''];

        // Row 4: Summary label
        $rows[] = ['RINGKASAN PERIODE', '', '', '', '', '', '', '', '', ''];
        // Rows 5-8: Summary KPIs
        $rows[] = ['Total Order', '', '', $this->totalOrders, '', '', '', '', '', ''];
        $rows[] = ['Total Revenue', '', '', $this->totalRevenue, '', '', '', '', '', ''];
        $rows[] = ['Total Item Terjual', '', '', $this->totalItemsSold, '', '', '', '', '', ''];
        $rows[] = ['Total User Baru', '', '', $this->totalNewUsers, '', '', '', '', '', ''];
        // Row 9: Spacer
        $rows[] = ['', '', '', '', '', '', '', '', '', ''];

        // Row 10: Status breakdown label
        $rows[] = ['BREAKDOWN STATUS ORDER', '', '', '', '', '', '', '', '', ''];
        // Row 11: Status table header
        $rows[] = ['Status', 'Jumlah Order', 'Total Revenue', '', '', '', '', '', '', ''];
        // Status data rows
        foreach ($this->statusBreakdown as $item) {
            $rows[] = [$item->status, $item->total, $item->revenue, '', '', '', '', '', '', ''];
        }
        // Spacer
        $rows[] = ['', '', '', '', '', '', '', '', '', ''];

        // Top products label
        $rows[] = ['TOP 5 PRODUK TERLARIS', '', '', '', '', '', '', '', '', ''];
        // Top products header
        $rows[] = ['#', 'Nama Produk', 'Total Terjual (Item)', 'Total Revenue', '', '', '', '', '', ''];
        // Top products data
        foreach ($this->topProducts as $idx => $item) {
            $rows[] = [$idx + 1, $item->product?->name ?? '(Produk dihapus)', $item->total_sold, $item->total_revenue, '', '', '', '', '', ''];
        }
        // Spacer
        $rows[] = ['', '', '', '', '', '', '', '', '', ''];

        // Transactions label
        $rows[] = ['DAFTAR TRANSAKSI', '', '', '', '', '', '', '', '', ''];
        // Transaction header
        $rows[] = ['No', 'Order ID', 'Pelanggan', 'Email', 'Tanggal', 'Produk Dipesan', 'Ongkos Kirim', 'Total Bayar', 'Status Pembayaran', 'Status'];

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

                // ── Calculate dynamic row positions ─────────────────────
                $statusCount   = $this->statusBreakdown->count();
                $topCount      = $this->topProducts->count();
                $orderCount    = $this->orders->count();

                // Row 1-2: title+period
                // Row 3: spacer
                // Row 4: summary label
                // Row 5-8: summary KPIs
                // Row 9: spacer
                $statusLabelRow  = 10;
                $statusHeaderRow = 11;
                $statusDataStart = 12;
                $statusDataEnd   = $statusDataStart + $statusCount - 1;
                $spacer1         = $statusDataEnd + 1;
                $topLabelRow     = $spacer1 + 1;
                $topHeaderRow    = $topLabelRow + 1;
                $topDataStart    = $topHeaderRow + 1;
                $topDataEnd      = $topDataStart + $topCount - 1;
                $spacer2         = $topDataEnd + 1;
                $txLabelRow      = $spacer2 + 1;
                $txHeaderRow     = $txLabelRow + 1;
                $txDataStart     = $txHeaderRow + 1;
                $txDataEnd       = $txDataStart + $orderCount - 1;
                $lastRow         = max($txDataEnd, $txHeaderRow);

                // ── Dark background for entire sheet ────────────────────
                $sheet->getStyle("A1:J{$lastRow}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1A1A1A']],
                    'font' => ['color' => ['argb' => 'FFF0ECE4'], 'name' => 'Calibri', 'size' => 10],
                ]);

                // ── Title row ───────────────────────────────────────────
                $sheet->mergeCells('A1:J1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FFD4AF37'], 'name' => 'Calibri'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0F0F0F']],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(38);

                // ── Period row ──────────────────────────────────────────
                $sheet->mergeCells('A2:J2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF888077']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0F0F0F']],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // ── Spacer row 3 ────────────────────────────────────────
                $sheet->mergeCells('A3:J3');
                $sheet->getStyle('A3')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF151515']],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(8);

                // ── Summary label (row 4) ───────────────────────────────
                $sheet->mergeCells('A4:J4');
                $this->applyLabelStyle($sheet, 'A4:J4');
                $sheet->getRowDimension(4)->setRowHeight(22);

                // ── Summary KPI rows 5-8 ────────────────────────────────
                $kpiRows = [
                    5 => ['Total Order',       false],
                    6 => ['Total Revenue',      true],
                    7 => ['Total Item Terjual', false],
                    8 => ['Total User Baru',    false],
                ];
                foreach ($kpiRows as $row => [$label, $isCurrency]) {
                    $sheet->mergeCells("A{$row}:C{$row}");
                    $sheet->mergeCells("D{$row}:J{$row}");
                    $sheet->getStyle("A{$row}")->applyFromArray([
                        'font'      => ['bold' => true, 'color' => ['argb' => 'FFB0A89A']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF252525']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 2],
                    ]);
                    $sheet->getStyle("D{$row}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FFD4AF37']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1C1C1C']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 2],
                    ]);
                    if ($isCurrency) {
                        $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    }
                    $sheet->getRowDimension($row)->setRowHeight(24);
                }

                // ── Spacer row 9 ────────────────────────────────────────
                $sheet->mergeCells('A9:J9');
                $sheet->getStyle('A9')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF151515']],
                ]);
                $sheet->getRowDimension(9)->setRowHeight(8);

                // ── Status Breakdown section ────────────────────────────
                $sheet->mergeCells("A{$statusLabelRow}:J{$statusLabelRow}");
                $this->applyLabelStyle($sheet, "A{$statusLabelRow}:J{$statusLabelRow}");
                $sheet->getRowDimension($statusLabelRow)->setRowHeight(22);

                $this->applyTableHeaderStyle($sheet, "A{$statusHeaderRow}:C{$statusHeaderRow}");
                $sheet->getRowDimension($statusHeaderRow)->setRowHeight(20);

                for ($r = $statusDataStart; $r <= $statusDataEnd; $r++) {
                    $this->applyDataRowStyle($sheet, "A{$r}:C{$r}", $r);
                    $sheet->getStyle("C{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getRowDimension($r)->setRowHeight(18);
                }

                // ── Spacer ──────────────────────────────────────────────
                $sheet->mergeCells("A{$spacer1}:J{$spacer1}");
                $sheet->getStyle("A{$spacer1}")->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF151515']]]);
                $sheet->getRowDimension($spacer1)->setRowHeight(8);

                // ── Top Products section ────────────────────────────────
                $sheet->mergeCells("A{$topLabelRow}:J{$topLabelRow}");
                $this->applyLabelStyle($sheet, "A{$topLabelRow}:J{$topLabelRow}");
                $sheet->getRowDimension($topLabelRow)->setRowHeight(22);

                $this->applyTableHeaderStyle($sheet, "A{$topHeaderRow}:D{$topHeaderRow}");
                $sheet->getRowDimension($topHeaderRow)->setRowHeight(20);

                for ($r = $topDataStart; $r <= $topDataEnd; $r++) {
                    $this->applyDataRowStyle($sheet, "A{$r}:D{$r}", $r);
                    $sheet->getStyle("D{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getRowDimension($r)->setRowHeight(18);
                }

                // ── Spacer ──────────────────────────────────────────────
                $sheet->mergeCells("A{$spacer2}:J{$spacer2}");
                $sheet->getStyle("A{$spacer2}")->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF151515']]]);
                $sheet->getRowDimension($spacer2)->setRowHeight(8);

                // ── Transactions section ────────────────────────────────
                $sheet->mergeCells("A{$txLabelRow}:J{$txLabelRow}");
                $this->applyLabelStyle($sheet, "A{$txLabelRow}:J{$txLabelRow}");
                $sheet->getRowDimension($txLabelRow)->setRowHeight(22);

                $this->applyTableHeaderStyle($sheet, "A{$txHeaderRow}:J{$txHeaderRow}");
                $sheet->getRowDimension($txHeaderRow)->setRowHeight(22);

                for ($r = $txDataStart; $r <= $txDataEnd; $r++) {
                    $order = $this->orders[$r - $txDataStart];
                    $status = $order->status;

                    $this->applyDataRowStyle($sheet, "A{$r}:I{$r}", $r);

                    // Currency format
                    $sheet->getStyle("G{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getStyle("H{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');

                    // Colored status cell
                    $bgColor   = match($status) {
                        'Completed'  => 'FF1D4D2E',
                        'Processing' => 'FF4D3A1A',
                        'Shipped'    => 'FF1A2C4D',
                        'Cancelled'  => 'FF4D1A1A',
                        default      => 'FF2A2A2A',
                    };
                    $textColor = match($status) {
                        'Completed'  => 'FF4ADE80',
                        'Processing' => 'FFD4AF37',
                        'Shipped'    => 'FF60A5FA',
                        'Cancelled'  => 'FFFF6B6B',
                        default      => 'FFB0A89A',
                    };
                    $sheet->getStyle("J{$r}")->applyFromArray([
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
                        'font'      => ['bold' => true, 'color' => ['argb' => $textColor]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF2A2A2A']]],
                    ]);

                    // Wrap text for products column
                    $sheet->getStyle("F{$r}")->getAlignment()->setWrapText(true);
                    $sheet->getRowDimension($r)->setRowHeight(-1); // auto height for wrapped text
                }

                // ── Page setup ──────────────────────────────────────────
                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                // Freeze header rows for transactions
                $sheet->freezePane("A{$txDataStart}");

                // Sheet tab color
                $sheet->getTabColor()->setARGB('FFD4AF37');
            },
        ];
    }

    // ─── Private Style Helpers ──────────────────────────────────────────────────

    private function applyLabelStyle($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 11,
                'color' => ['argb' => 'FFD4AF37'],
                'name'  => 'Calibri',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E1E1E'],
            ],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FFD4AF37']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'indent'     => 1,
            ],
        ]);
    }

    private function applyTableHeaderStyle($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 10,
                'color' => ['argb' => 'FFD4AF37'],
                'name'  => 'Calibri',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF2A2218'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF4A3D1A']],
                'bottom'     => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FFD4AF37']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    private function applyDataRowStyle($sheet, string $range, int $row): void
    {
        $bgColor = ($row % 2 === 0) ? 'FF1E1E1E' : 'FF252525';
        $sheet->getStyle($range)->applyFromArray([
            'font'      => ['color' => ['argb' => 'FFF0ECE4'], 'size' => 10, 'name' => 'Calibri'],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF2A2A2A']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
    }
}
