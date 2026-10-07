<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'name',
        'phone',
        'locality',
        'address',
        'city',
        'state',
        'country',
        'landmark',
        'zip',
        'status',
        'stock_deducted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id');
    }

    /**
     * Kurangi stok produk sesuai item order.
     * Dipakai saat order dibuat. Melempar exception jika stok kurang.
     */
    public function deductStock(): void
    {
        if ($this->stock_deducted) {
            return;
        }

        foreach ($this->orderItems as $item) {
            $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
            if (!$product) {
                continue;
            }
            if ($product->quantity < $item->quantity) {
                throw new \RuntimeException("Stok produk \"{$product->name}\" tidak mencukupi (sisa {$product->quantity}).");
            }
            $product->quantity -= $item->quantity;
            $product->stock_status = $product->quantity > 0 ? 'instock' : 'outofstock';
            $product->save();
        }

        $this->stock_deducted = true;
        $this->save();
    }

    /**
     * Kembalikan stok produk (order dibatalkan / dihapus).
     * Aman dipanggil berulang: hanya jalan jika stok pernah dikurangi.
     */
    public function restoreStock(): void
    {
        if (!$this->stock_deducted) {
            return;
        }

        foreach ($this->orderItems as $item) {
            $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
            if (!$product) {
                continue;
            }
            $product->quantity += $item->quantity;
            $product->stock_status = $product->quantity > 0 ? 'instock' : 'outofstock';
            $product->save();
        }

        $this->stock_deducted = false;
        $this->save();
    }
}
