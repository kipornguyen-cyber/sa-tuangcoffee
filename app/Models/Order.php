<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'table_number', 'total_amount', 'status', 'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-500/20 text-amber-400">Pending</span>',
            'paid' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-500/20 text-emerald-400">Paid</span>',
            'cancelled' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-500/20 text-red-400">Cancelled</span>',
            default => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-500/20 text-gray-400">Unknown</span>',
        };
    }

    public static function generateOrderCode(): string
    {
        $date = now()->format('ymd');
        $lastOrder = static::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastOrder ? (int) substr($lastOrder->order_code, -4) + 1 : 1;
        return 'ORD-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
