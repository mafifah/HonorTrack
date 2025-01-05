<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataOption;
use Auth;
use Carbon\Carbon;

class SettingSystemController extends Controller
{
    public function index (){      
        $statusValidasi = DataOption::where('entity', '=', 'Status Validasi Lokasi')->first();
        $schoolLat = DataOption::where('entity', '=', 'Koordinat Sekolah Lat')->first(); // Koordinat sekolah (latitude)
        $schoolLong = DataOption::where('entity', '=', 'Koordinat Sekolah Long')->first();             // Koordinat sekolah (longitude)

        return view('setting.index', compact('statusValidasi', 'schoolLat', 'schoolLong'));
    }

    public function update (Request $request){
        $DataOptionLat = DataOption::findOrFail($request->schoolLatid);
        $DataOptionLat->nama = $request->schoolLat;
        $DataOptionLat->save();

        $DataOptionLong = DataOption::findOrFail($request->schoolLongid);
        $DataOptionLong->nama = $request->schoolLong;
        $DataOptionLong->save();

        $DataOptionStatus = DataOption::findOrFail($request->statusValidasiid);
        $DataOptionStatus->nama = $request->statusValidasi;
        $DataOptionStatus->save();

        return redirect('setting/system');
    }
}
