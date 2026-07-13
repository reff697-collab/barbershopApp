<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'barber_id',
        'jumlah_pinjaman',
        'cicilan_per_hari',
        'sisa_hutang',
        'status',
        'tanggal_pinjam',
        'catatan',
        'input_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
        ];
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function inputBy()
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }
}