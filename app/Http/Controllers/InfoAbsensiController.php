<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Absensi;
use App\Models\Staf;
class InfoAbsensiController extends Controller
{
    public function index()
    {
        $tanggalAkhir = date("Y-m-d");
        // Mengurangi 3 bulan menggunakan strtotime
        $tanggalAwal = date("Y-m-d", strtotime("-3 months", strtotime($tanggalAkhir)));
        $data = Absensi:: whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();
        $dataGuru = Staf::where('id_jabatan', 2)->get();
        //dd($data);
        return view('info-absensi.info-absensi', compact('data', 'tanggalAwal', 'tanggalAkhir', 'dataGuru'));
    }

    public function perbaruiData(Request $request)
    {
        //dd($request->all());
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
        

        $data;
        $idguru = $request->idguru;
        if($idguru != null){
            $data = Absensi::whereHas('jadwal', function ($query) use ($idguru) {
                        $query->where('id_guru', $idguru);
                    })
                    ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                    ->get();
        }else{
            $data = Absensi:: whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();
        }

        return view('info-absensi.panel-detil', compact('data', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function cetakData(Request $request)
    {
        //dd($request->all());
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
        

        $data;
        $guru = [];
        $idguru = $request->idguru;
        if($idguru != null){
            $guru = Staf::findOrFail($idguru);
            $data = Absensi::whereHas('jadwal', function ($query) use ($idguru) {
                        $query->where('id_guru', $idguru);
                    })
                    ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                    ->get();
        }else{
            $data = Absensi:: whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();
        }

        //dd($guru);
        $pdf= Pdf::loadView('info-absensi.info-absensi-cetak', compact('data', 'tanggalAwal', 'tanggalAkhir', 'guru'));
        return $pdf->stream('LaporanAbsensi.pdf');
        
        
    }
}
