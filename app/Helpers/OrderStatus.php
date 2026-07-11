<?php

namespace App\Helpers;

class OrderStatus
{
    public static function meta(string $status): array
    {
        $st = strtolower($status);

        return match (true) {
            in_array($st, ['completed', 'selesai']) => [
                'label' => 'Selesai',
                'fill'    => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/50',
                'outline' => 'bg-transparent text-emerald-600 border-emerald-500/40',
                'dot'   => 'bg-emerald-500',
                'chart' => 'rgba(16,185,129,0.85)',
            ],
            in_array($st, ['processing', 'diproses']) => [
                'label' => 'Diproses',
                'fill'    => 'bg-gold/10 text-gold border-gold/50',
                'outline' => 'bg-transparent text-gold border-gold/40',
                'dot'   => 'bg-gold',
                'chart' => 'rgba(138,106,8,0.85)',
            ],
            $st === 'shipped' => [
                'label' => 'Dikirim',
                'fill'    => 'bg-blue-500/10 text-blue-600 border-blue-500/50',
                'outline' => 'bg-transparent text-blue-600 border-blue-500/40',
                'dot'   => 'bg-blue-500',
                'chart' => 'rgba(59,130,246,0.85)',
            ],
            in_array($st, ['cancelled', 'dibatalkan']) => [
                'label' => 'Dibatalkan',
                'fill'    => 'bg-rose-500/10 text-rose-600 border-rose-500/50',
                'outline' => 'bg-transparent text-rose-600 border-rose-500/40',
                'dot'   => 'bg-rose-500',
                'chart' => 'rgba(244,63,94,0.85)',
            ],
            default => [
                'label' => 'Pending',
                'fill'    => 'bg-amber-500/10 text-amber-600 border-amber-500/50',
                'outline' => 'bg-transparent text-amber-600 border-amber-500/40',
                'dot'   => 'bg-amber-500',
                'chart' => 'rgba(245,158,11,0.85)',
            ],
        };
    }

    /**
     * Semua status dalam bentuk array, untuk dipakai di JS (Chart.js) via json_encode.
     */
    public static function all(): array
    {
        $statuses = ['Completed', 'Processing', 'Shipped', 'Pending', 'Cancelled'];
        $result = [];
        foreach ($statuses as $s) {
            $result[$s] = self::meta($s);
        }
        return $result;
    }
}
