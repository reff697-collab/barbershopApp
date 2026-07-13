<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_day_id',
        'nomor_transaksi',
        'kasir_id',
        'barber_id',
        'payment_method',
        'total',
        'total_layanan',
        'komisi_barber',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'total_layanan' => 'decimal:2',
            'komisi_barber' => 'decimal:2',
        ];
    }

    public function storeDay()
    {
        return $this->belongsTo(StoreDay::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}