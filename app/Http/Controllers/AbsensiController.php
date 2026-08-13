<?php

namespace App\Http\Controllers;

use App\Models\AbsensiKasir;
use App\Models\BarberDailyStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    /**
     * Halaman Rekap Absensi: Barber otomatis dari status aktif,
     * Kasir dicatat manual oleh Owner. Difilter per bulan.
     */
    public function index(Request $request): View
    {
        $mode = $request->query('mode', 'bulan'); // 'bulan' atau 'tahun'
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        if ($mode === 'tahun') {
            $awalPeriode = Carbon::create($tahun, 1, 1)->startOfYear();
            $akhirPeriode = Carbon::create($tahun, 1, 1)->endOfYear();
            $labelPeriode = (string) $tahun;
        } else {
            $awalPeriode = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $akhirPeriode = $awalPeriode->copy()->endOfMonth();
            $labelPeriode = $awalPeriode->translatedFormat('F Y');
        }

        // Total hari toko benar-benar buka dalam periode ini
        $totalHariTokoBuka = \App\Models\StoreDay::whereBetween('tanggal', [
            $awalPeriode->toDateString(), $akhirPeriode->toDateString(),
        ])->where('status', '!=', 'belum_buka')->count();

        // Rekap Barber
        $barbers = User::role('barber')->get()->map(function ($barber) use ($awalPeriode, $akhirPeriode, $totalHariTokoBuka) {
            $totalHadir = BarberDailyStatus::where('barber_id', $barber->id)
                ->whereHas('storeDay', function ($q) use ($awalPeriode, $akhirPeriode) {
                    $q->whereBetween('tanggal', [$awalPeriode->toDateString(), $akhirPeriode->toDateString()]);
                })
                ->count();

            return [
                'nama' => $barber->name,
                'total_hadir' => $totalHadir,
                'total_libur' => max($totalHariTokoBuka - $totalHadir, 0),
            ];
        });

        // Rekap Kasir
        $kasirs = User::role('kasir')->get()->map(function ($kasir) use ($awalPeriode, $akhirPeriode) {
            $totalHadir = AbsensiKasir::where('kasir_id', $kasir->id)
                ->whereBetween('tanggal', [$awalPeriode->toDateString(), $akhirPeriode->toDateString()])
                ->where('hadir', true)
                ->count();

            $totalLibur = AbsensiKasir::where('kasir_id', $kasir->id)
                ->whereBetween('tanggal', [$awalPeriode->toDateString(), $akhirPeriode->toDateString()])
                ->where('hadir', false)
                ->count();

            return [
                'id' => $kasir->id,
                'nama' => $kasir->name,
                'total_hadir' => $totalHadir,
                'total_libur' => $totalLibur,
            ];
        });

        $tanggalInput = $request->query('tanggal_input', now()->toDateString());
        $absensiHariItu = AbsensiKasir::where('tanggal', $tanggalInput)->pluck('hadir', 'kasir_id');

        return view('absensi.index', compact(
            'barbers', 'kasirs', 'mode', 'bulan', 'tahun', 'labelPeriode', 'tanggalInput', 'absensiHariItu'
        ));
    }

    /**
     * Simpan/update absensi kasir untuk satu tanggal tertentu.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'hadir' => ['array'],
            'hadir.*' => ['exists:users,id'],
        ]);

        $tanggal = $validated['tanggal'];
        $kasirHadir = $validated['hadir'] ?? [];

        $semuaKasir = User::role('kasir')->pluck('id');

        foreach ($semuaKasir as $kasirId) {
            AbsensiKasir::updateOrCreate(
                ['kasir_id' => $kasirId, 'tanggal' => $tanggal],
                ['hadir' => in_array($kasirId, $kasirHadir), 'dicatat_oleh' => $request->user()->id]
            );
        }

        return redirect()
            ->route('absensi.index', ['tanggal_input' => $tanggal])
            ->with('success', 'Absensi kasir berhasil dicatat.');
    }
}