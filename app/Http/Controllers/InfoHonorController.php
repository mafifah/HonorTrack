<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InfoHonorController extends Controller
{
    public function index()
    {
        
        
        $tanggalAkhir = date("Y-m-d");
        // Mengurangi 3 bulan menggunakan strtotime
        $tanggalAwal = date("Y-m-d", strtotime("-3 months", strtotime($tanggalAkhir)));
        //dd($tanggalAwal, $tanggalAkhir);
        // Query untuk menghitung honor
        $data = DB::table('absensi as a')
        ->join('jadwal as j', 'a.id_jadwal', '=', 'j.id')
        ->join('staf as s', 'j.id_guru', '=', 's.id')
        ->select(
            's.id as id_guru',
            's.nama as nama_guru',
            's.rate_gaji as rate_gaji',
            DB::raw('COUNT(a.id) as jumlah_kehadiran'),
            DB::raw("
                SUM(
                    CASE 
                        WHEN a.status_kehadiran = 'Hadir' THEN CAST(s.rate_gaji AS DECIMAL(10,2))
                        WHEN a.status_kehadiran = 'Sakit' THEN CAST(s.rate_gaji AS DECIMAL(10,2)) * 0.5
                        ELSE 0
                    END
                ) as total_honor
            ")
        )
        ->whereBetween('a.tanggal', [$tanggalAwal, $tanggalAkhir])
        ->groupBy('s.id', 's.nama', 's.rate_gaji')
        ->orderBy('total_honor', 'DESC')
        ->get();

        //dd($data);
        return view('info-honor.info-honor', compact('data', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function perbaruiData(Request $request)
    {
        $tanggalAkhir = date("Y-m-d");
        $tanggalAwal = date("Y-m-d", strtotime("-3 months", strtotime($tanggalAkhir)));

        if($request->tanggalAkhir != null){
            $tanggalAkhir = $request->tanggalAkhir;
        }

        if($request->tanggalAwal != null){
            $tanggalAwal = $request->tanggalAwal;
        }else{
           $tanggalAwal = date("Y-m-d", strtotime("-3 months", strtotime($tanggalAkhir))); 
        }
        

        $data = DB::table('absensi as a')
        ->join('jadwal as j', 'a.id_jadwal', '=', 'j.id')
        ->join('staf as s', 'j.id_guru', '=', 's.id')
        ->select(
            's.id as id_guru',
            's.nama as nama_guru',
            's.rate_gaji as rate_gaji',
            DB::raw('COUNT(a.id) as jumlah_kehadiran'),
            DB::raw("
                SUM(
                    CASE 
                        WHEN a.status_kehadiran = 'Hadir' THEN CAST(s.rate_gaji AS DECIMAL(10,2))
                        WHEN a.status_kehadiran = 'Sakit' THEN CAST(s.rate_gaji AS DECIMAL(10,2)) * 0.5
                        ELSE 0
                    END
                ) as total_honor
            ")
        )
        ->whereBetween('a.tanggal', [$tanggalAwal, $tanggalAkhir])
        ->groupBy('s.id', 's.nama', 's.rate_gaji')
        ->orderBy('total_honor', 'DESC')
        ->get();
        
        
        if($request->cetak == "Yes"){
            //dd($request->all());
            $pdf= Pdf::loadView('info-honor.info-honor-cetak', compact('data', 'tanggalAwal', 'tanggalAkhir'));
            return $pdf->stream('LaporanHonor.pdf');
        }else{
            return view('info-honor.panel-detil', compact('data', 'tanggalAwal', 'tanggalAkhir'));
        }
        
    }

    public function cetakData(Request $request)
    {
        //dd("cetak");
        $tanggalAkhir = date("Y-m-d");
        $tanggalAwal = date("Y-m-d", strtotime("-3 months", strtotime($tanggalAkhir)));

        if($request->tanggalAkhir != null){
            $tanggalAkhir = $request->tanggalAkhir;
        }

        if($request->tanggalAwal != null){
            $tanggalAwal = $request->tanggalAwal;
        }else{
           $tanggalAwal = date("Y-m-d", strtotime("-3 months", strtotime($tanggalAkhir))); 
        }
        

        $data = DB::table('absensi as a')
        ->join('jadwal as j', 'a.id_jadwal', '=', 'j.id')
        ->join('staf as s', 'j.id_guru', '=', 's.id')
        ->select(
            's.id as id_guru',
            's.nama as nama_guru',
            's.rate_gaji as rate_gaji',
            DB::raw('COUNT(a.id) as jumlah_kehadiran'),
            DB::raw("
                SUM(
                    CASE 
                        WHEN a.status_kehadiran = 'Hadir' THEN CAST(s.rate_gaji AS DECIMAL(10,2))
                        WHEN a.status_kehadiran = 'Sakit' THEN CAST(s.rate_gaji AS DECIMAL(10,2)) * 0.5
                        ELSE 0
                    END
                ) as total_honor
            ")
        )
        ->whereBetween('a.tanggal', [$tanggalAwal, $tanggalAkhir])
        ->groupBy('s.id', 's.nama', 's.rate_gaji')
        ->orderBy('total_honor', 'DESC')
        ->get();


        //dd($request->all());
        $pdf= Pdf::loadView('info-honor.info-honor-cetak', compact('data', 'tanggalAwal', 'tanggalAkhir'));
        return $pdf->stream('LaporanHonor.pdf');
        
        
    }
}
