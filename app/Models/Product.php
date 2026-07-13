<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis',
        'harga',
        'stok',
        'min_stok',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Cek apakah stok produk ini udah di bawah ambang batas minimum.
     */
    public function isStokMenipis(): bool
    {
        return $this->stok <= $this->min_stok;
    }
}