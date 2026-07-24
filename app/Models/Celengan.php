<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Celengan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'saldo',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'saldo' => 'decimal:2',
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transaksis()
    {
        return $this->hasMany(CelenganTransaksi::class);
    }
}