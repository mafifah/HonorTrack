<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Staf;
use App\Models\DataOption;
use App\Models\Absensi;
use Auth;
use Carbon\Carbon;

class jadwalController extends Controller
{
    public function index (){      
        $data = Jadwal::all();
        $hariIni = Carbon::now()->locale('id')->dayName; // Nama hari saat ini

        return view('jadwal.jadwal', compact('data', 'hariIni'));
    }

    public function tambah (){
        $dataMataPelajaran = MataPelajaran::all();
        $dataKelas = Kelas::all();
        $dataGuru = Staf::where('id_jabatan', 2)->get();
        $dataHari = DataOption::where('entity', '=', 'Hari')->get();
        $dataJenis = DataOption::where('entity', '=', 'Jenis Jadwal')->get();
        return view('jadwal.tambah', compact('dataMataPelajaran', 'dataKelas', 'dataGuru', 'dataHari', 'dataJenis'));
    }

    public function simpan (Request $request){
        $data = $request->except('_token', 'submit');
        Jadwal::create($data);
        return redirect('akademik/jadwal');
    }

    public function edit ($id){
        $data = Jadwal::findOrFail($id);
        $dataMataPelajaran = MataPelajaran::all();
        $dataKelas = Kelas::all();
        $dataGuru = Staf::where('id_jabatan', 2)->get();
        $dataHari = DataOption::where('entity', '=', 'Hari')->get();
        $dataJenis = DataOption::where('entity', '=', 'Jenis Jadwal')->get();
        return view('jadwal.edit', compact('data', 'dataMataPelajaran', 'dataKelas', 'dataGuru', 'dataHari', 'dataJenis'));
    }

    public function update (Request $request, $id){
        $data = Jadwal::findOrFail($id);
        $data->id_mata_pelajaran = $request->id_mata_pelajaran;
        $data->id_kelas = $request->id_kelas;
        $data->id_guru = $request->id_guru;
        $data->hari = $request->hari;
        $data->jam_mulai = $request->jam_mulai;
        $data->jam_selesai = $request->jam_selesai;
        $data->jenis = $request->jenis;
        $data->save();

        return redirect('akademik/jadwal');
    }

    public function rekamAbsensi (Request $request){
        $data = Jadwal::findOrFail($request->id);
        return view('jadwal.absensi', compact('data'));
    }

    public function simpanAbsensi (Request $request){
        //dd($request->all());
        date_default_timezone_set('Asia/Jakarta');
        $date= date("Y-m-d");
        $time = date("H:i");
        $data = Jadwal::findOrFail($request->id);
        //dd($datetime, $data);
        // Validasi rentang waktu
        if ($time <= $data->jam_mulai || $time >= $data->jam_selesai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Absen tidak bisa dilakukan karena tidak sesuai dengan jadwal.'
            ], 400); // 400: Bad Request
        }
        $absensi = new Absensi();
        $absensi->id_jadwal = $data->id;
        $absensi->tanggal = $date;
        $absensi->status_kehadiran = "Hadir";
        $absensi->pokok_pembahasan = $request->pokok_pembahasan;
        $absensi->koordinat = $request->koordinat;
        $absensi->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Absen berhasil dilakukan.'
        ]);
    }
    
    public function delete (Request $request){
        $data = Jadwal::findOrFail($request->id);
        $data->delete();
        return redirect('akademik/jadwal');
    }
}
