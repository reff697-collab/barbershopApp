<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_day_id',
        'nominal',
        'keterangan',
        'input_by',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
        ];
    }

    public function storeDay()
    {
        return $this->belongsTo(StoreDay::class);
    }

    public function inputBy()
    {
        return $this->belongsTo(User::class, 'input_by');
    }
}