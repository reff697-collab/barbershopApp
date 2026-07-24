<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CelenganTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'celengan_id',
        'tipe',
        'nominal',
        'keterangan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
        ];
    }

    public function celengan()
    {
        return $this->belongsTo(Celengan::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}