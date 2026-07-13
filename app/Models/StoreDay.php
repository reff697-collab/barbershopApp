<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'status',
        'opened_by',
        'opened_at',
        'closed_by',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function barberStatuses()
    {
        return $this->hasMany(BarberDailyStatus::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Ambil (atau buat baru) data StoreDay untuk hari ini.
     * Dipakai di banyak tempat supaya tidak perlu query manual berulang.
     */
    public static function today(): self
    {
        return static::firstOrCreate(['tanggal' => now()->toDateString()]);
    }
}