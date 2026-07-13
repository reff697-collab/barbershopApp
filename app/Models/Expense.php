<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kategori',
        'nama',
        'nominal',
        'catatan',
        'input_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nominal' => 'decimal:2',
        ];
    }

    public function inputBy()
    {
        return $this->belongsTo(User::class, 'input_by');
    }
}