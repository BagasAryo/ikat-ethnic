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
    protected $totalOrders;
    protected $totalRevenue;
    protected $totalItemsSold;
    protected $totalNewUsers;
    protected $statusBreakdown;
    protected $topProducts;

    public function __construct(
        Carbon $dateFrom,
        Carbon $dateTo,
        int $totalOrders,
        $totalRevenue,
        $totalItemsSold,
        int $totalNewUsers,
        $statusBreakdown,
        $topProducts
    ) {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->totalOrders = $totalOrders;
        $this->totalRevenue = $totalRevenue;
        $this->totalItemsSold = $totalItemsSold;
        $this->totalNewUsers = $totalNewUsers;
        $this->statusBreakdown = $statusBreakdown;
        $this->topProducts = $topProducts;
    }

    public function title(): string
    {
        return 'Ringkasan Laporan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 24,
            'C' => 24,
            'D' => 30,
        ];
    }

    public function array(): array
    {
        $rows = [];

        // Row 1: Main title
        $rows[] = ['LAPORAN PENJUALAN — IKAT ETHNIC', '', '', ''];
        // Row 2: Period
        $rows[] = ['Periode: ' . $this->dateFrom->format('d M Y') . ' — ' . $this->dateTo->format('d M Y'), '', '', ''];
        // Row 3: Spacer
        $rows[] = ['', '', '', ''];

        // Row 4: Summary label
        $rows[] = ['RINGKASAN PERIODE', '', '', ''];
        // Rows 5-8: Summary KPIs
        $rows[] = ['Total Order', '', '', $this->totalOrders];
        $rows[] = ['Total Revenue', '', '', $this->totalRevenue];
        $rows[] = ['Total Item Terjual', '', '', $this->totalItemsSold];
        $rows[] = ['Total User Baru', '', '', $this->totalNewUsers];
        // Row 9: Spacer
        $rows[] = ['', '', '', ''];

        // Row 10: Status breakdown label
        $rows[] = ['BREAKDOWN STATUS ORDER', '', '', ''];
        // Row 11: Status table header
        $rows[] = ['Status', 'Jumlah Order', 'Total Revenue', ''];
        // Status data rows
        foreach ($this->statusBreakdown as $item) {
            $rows[] = [$item->status, $item->total, $item->revenue, ''];
        }
        // Spacer
        $rows[] = ['', '', '', ''];

        // Top products label
        $rows[] = ['TOP 5 PRODUK TERLARIS', '', '', ''];
        // Top products header
        $rows[] = ['#', 'Nama Produk', 'Total Terjual (Item)', 'Total Revenue'];
        // Top products data
        foreach ($this->topProducts as $idx => $item) {
            $rows[] = [$idx + 1, $item->product?->name ?? '(Produk dihapus)', $item->total_sold, $item->total_revenue];
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

                $statusLabelRow  = 10;
                $statusHeaderRow = 11;
                $statusDataStart = 12;
                $statusDataEnd   = $statusDataStart + $statusCount - 1;
                $spacer1         = $statusDataEnd + 1;
                $topLabelRow     = $spacer1 + 1;
                $topHeaderRow    = $topLabelRow + 1;
                $topDataStart    = $topHeaderRow + 1;
                $topDataEnd      = $topDataStart + $topCount - 1;

                // ── Title row ───────────────────────────────────────────
                $sheet->mergeCells('A1:D1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // ── Period row ──────────────────────────────────────────
                $sheet->mergeCells('A2:D2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 11, 'color' => ['argb' => 'FF555555']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // ── Summary label (row 4) ───────────────────────────────
                $sheet->mergeCells('A4:D4');
                $this->applyLabelStyle($sheet, 'A4:D4');
                
                // ── Summary KPI rows 5-8 ────────────────────────────────
                $kpiRows = [
                    5 => ['Total Order',       false],
                    6 => ['Total Revenue',      true],
                    7 => ['Total Item Terjual', false],
                    8 => ['Total User Baru',    false],
                ];
                foreach ($kpiRows as $row => [$label, $isCurrency]) {
                    $sheet->mergeCells("A{$row}:C{$row}");
                    $sheet->getStyle("A{$row}")->applyFromArray([
                        'font'      => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 2],
                    ]);
                    $sheet->getStyle("D{$row}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 12],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 2],
                    ]);
                    if ($isCurrency) {
                        $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    }
                }

                // ── Status Breakdown section ────────────────────────────
                $sheet->mergeCells("A{$statusLabelRow}:D{$statusLabelRow}");
                $this->applyLabelStyle($sheet, "A{$statusLabelRow}:D{$statusLabelRow}");

                $this->applyTableHeaderStyle($sheet, "A{$statusHeaderRow}:C{$statusHeaderRow}");
                
                if ($statusCount > 0) {
                    for ($r = $statusDataStart; $r <= $statusDataEnd; $r++) {
                        $this->applyDataRowStyle($sheet, "A{$r}:C{$r}");
                        $sheet->getStyle("C{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    }
                }

                // ── Top Products section ────────────────────────────────
                $sheet->mergeCells("A{$topLabelRow}:D{$topLabelRow}");
                $this->applyLabelStyle($sheet, "A{$topLabelRow}:D{$topLabelRow}");

                $this->applyTableHeaderStyle($sheet, "A{$topHeaderRow}:D{$topHeaderRow}");

                if ($topCount > 0) {
                    for ($r = $topDataStart; $r <= $topDataEnd; $r++) {
                        $this->applyDataRowStyle($sheet, "A{$r}:D{$r}");
                        $sheet->getStyle("D{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    }
                }

                // ── Page setup ──────────────────────────────────────────
                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);
            },
        ];
    }

    // ─── Private Style Helpers ──────────────────────────────────────────────────

    private function applyLabelStyle($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 12,                
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'indent'     => 1,
            ],
        ]);
        $sheet->getRowDimension((int) filter_var($range, FILTER_SANITIZE_NUMBER_INT))->setRowHeight(25);
    }

    private function applyTableHeaderStyle($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1F2937'], // Dark Gray / Blueish contrast
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension((int) filter_var($range, FILTER_SANITIZE_NUMBER_INT))->setRowHeight(20);
    }

    private function applyDataRowStyle($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders'   => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']],
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
    }
}
