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

    public function simpanAbsensi(Request $request)
    {
        // Set timezone ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        $date = date("Y-m-d");
        $time = date("H:i");

        // Cari jadwal berdasarkan ID
        $data = Jadwal::findOrFail($request->id);

        // Validasi rentang waktu
        if ($time <= $data->jam_mulai || $time >= $data->jam_selesai) {
            return response()->json([
                'status' => 'error',
                'message' => "Maaf, Anda hanya dapat melakukan absen saat jam pelajaran berlangsung, yaitu dari {$data->jam_mulai} hingga {$data->jam_selesai}."
            ], 400);
        }

        $statusValidasi = DataOption::where('entity', '=', 'Status Validasi Lokasi')->pluck('nama')->first();
        if($statusValidasi == 'Yes'){
            // Hitung jarak menggunakan Haversine Formula
            $koordinatUser = json_encode($request->koordinat, true);
            list($userLat, $userLong) = explode(',', $koordinatUser);
            
            $schoolLat = DataOption::where('entity', '=', 'Koordinat Sekolah Lat')->pluck('nama')->first(); // Koordinat sekolah (latitude)
            $schoolLong = DataOption::where('entity', '=', 'Koordinat Sekolah Long')->pluck('nama')->first();             // Koordinat sekolah (longitude)

            // Mengonversi koordinat ke tipe data float
            $userLat = (float) $userLat;
            $userLong = (float) $userLong;
            $schoolLat = (float) $schoolLat;
            $schoolLong = (float) $schoolLong;

            $distance = $this->haversine($userLat, $userLong, $schoolLat, $schoolLong);

            //dd($distance);
            if ($distance > 0.1) { // Jarak lebih dari 100 meter (0.1 km)
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf, Anda harus berada dalam radius 100 meter dari sekolah untuk melakukan absensi.'
                ], 400);
            }
        }
        

        // Cek apakah sudah pernah absen pada tanggal ini untuk id_jadwal yang sama
        $sudahAbsen = Absensi::where('id_jadwal', $data->id)
            ->where('tanggal', $date)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah melakukan absen hari ini.'
            ], 400);
        }

        // Simpan absensi jika validasi lolos
        $absensi = new Absensi();
        $absensi->id_jadwal = $data->id;
        $absensi->tanggal = $date;
        $absensi->status_kehadiran = "Hadir";
        $absensi->pokok_pembahasan = $request->pokok_pembahasan;
        $absensi->koordinat = json_encode($request->koordinat); // Simpan sebagai JSON jika koordinat lebih dari 1
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

    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer
        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance; // Jarak dalam kilometer
    }
}
