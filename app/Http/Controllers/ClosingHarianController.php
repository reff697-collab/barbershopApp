<?php

namespace App\Http\Controllers;

use App\Models\ClosingHarian;
use Illuminate\View\View;

class ClosingHarianController extends Controller
{
    /**
     * Daftar semua closing harian, terbaru duluan.
     */
    public function index(): View
    {
        $closings = ClosingHarian::with('storeDay')
            ->latest('closed_at')
            ->paginate(15);

        return view('closing.index', compact('closings'));
    }

    /**
     * Detail satu closing harian, termasuk rincian komisi per barber.
     */
    public function show(ClosingHarian $closing): View
    {
        $closing->load(['storeDay', 'closedBy', 'barberDetails.barber']);

        return view('closing.show', compact('closing'));
    }
}