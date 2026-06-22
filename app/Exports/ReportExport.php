<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
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
        $totalOrders,
        $totalRevenue,
        $totalItemsSold,
        $totalNewUsers,
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

    public function sheets(): array
    {
        return [
            new ReportSummarySheet(
                $this->dateFrom,
                $this->dateTo,
                $this->orders,
                $this->totalOrders,
                $this->totalRevenue,
                $this->totalItemsSold,
                $this->totalNewUsers,
                $this->statusBreakdown,
                $this->topProducts
            ),
        ];
    }
}
