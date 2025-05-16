<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staf;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index (){   
        $data = [];
        $hariIni = Carbon::now()->locale('id')->dayName; // Nama hari saat ini
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        // Untuk Admin
        $totalGuru = 0;
        $totalMataPelajaran = 0;
        $totalKelas = 0;
        $totalAbsenTercatat = 0;

        $topStafHadir = collect(); // kosong, tapi bisa tetap dipanggil di blade
        if(Auth::user()->role == 'Guru'){
            $guru = Staf::where('user_id', Auth::user()->id)->first();

            $jadwal = Jadwal::where('id_guru', $guru->id)
                ->orderByRaw("
                    FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')
                ")
                ->orderBy('jam_mulai')
                ->get();

            // Kelompokkan jadwal berdasarkan hari
            $data = $jadwal->groupBy('hari');

        } else {
            // Untuk Admin: ambil statistik
            $totalGuru = Staf::count();
            $totalMataPelajaran = MataPelajaran::count();
            $totalKelas = Kelas::count();
            $totalAbsenTercatat = Absensi::whereBetween('tanggal', [$start, $end])->count();

            // Query top 5 staf dengan kehadiran "Hadir" di bulan ini
            $topStafHadir = DB::table('absensi')
                ->join('jadwal', 'absensi.id_jadwal', '=', 'jadwal.id')
                ->join('staf', 'jadwal.id_guru', '=', 'staf.id')
                ->select('staf.nama', DB::raw('COUNT(absensi.id) as total_hadir'))
                ->where('absensi.status_kehadiran', 'Hadir')
                ->whereBetween('absensi.tanggal', [$start, $end])
                ->groupBy('staf.nama')
                ->orderByDesc('total_hadir')
                ->limit(5)
                ->get();

        }

        return view('dashboard', compact(
            'data',
            'hariIni',
            'totalGuru',
            'totalMataPelajaran',
            'totalKelas',
            'totalAbsenTercatat',
            'topStafHadir'
        ));
    }
}
