<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingHarianBarber extends Model
{
    use HasFactory;

    protected $fillable = [
        'closing_harian_id',
        'barber_id',
        'komisi_kotor',
        'potongan_cicilan',
        'komisi_bersih',
    ];

    public function closingHarian()
    {
        return $this->belongsTo(ClosingHarian::class);
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }
}