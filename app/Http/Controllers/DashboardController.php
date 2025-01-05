<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staf;
use App\Models\User;
use App\Models\Jadwal;
use Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index (){   
        $data = [];
        $hariIni = Carbon::now()->locale('id')->dayName; // Nama hari saat ini
        if(Auth::user()->role == 'Guru'){
            $guru = Staf::where('user_id', Auth::user()->id)->first();
            //dd($guru);
            $jadwal = Jadwal::where('id_guru', $guru->id)->orderByRaw("
                FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')
            ")->orderBy('jam_mulai')->get();

            // Kelompokkan jadwal berdasarkan hari
            $data = $jadwal->groupBy('hari');
        }else{

        }
        //dd($data);
        return view('dashboard', compact('data', 'hariIni'));
    }
}
