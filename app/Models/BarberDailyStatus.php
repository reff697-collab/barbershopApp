<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberDailyStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_day_id',
        'barber_id',
        'status',
        'activated_at',
        'deactivated_at',
    ];

    protected function casts(): array
    {
        return [
            'activated_at' => 'datetime',
            'deactivated_at' => 'datetime',
        ];
    }

    public function storeDay()
    {
        return $this->belongsTo(StoreDay::class);
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }
}