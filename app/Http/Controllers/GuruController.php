<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staf;
use App\Models\User;
use App\Models\Jabatan;
use Auth;

class GuruController extends Controller
{
    public function index (){   
        $dataJabatan = Jabatan::where('nama', '=', 'Guru')->first();   
        $data = Staf::where('id_jabatan', $dataJabatan->id)->get();
        return view('guru.guru', compact('data'));
    }

    public function tambah (){
        return view('guru.tambah');
    }

    public function simpan (Request $request){
        $dataJabatan = Jabatan::where('nama', '=', 'Guru')->first();
        //dd($request->all());
        $userData = [
            'nama' => $request->input('nama'),
            'username' => $request->input('nama'),
            'password' => bcrypt($request->input('nama')),
            'role' => 'Guru',
        ];

        // Create user record
        $user = User::create($userData);

        $data = $request->except('_token', 'submit');
        $data['user_id'] = $user->id;
        $data['id_jabatan'] = $dataJabatan->id;
        Staf::create($data);

        return redirect('akademik/guru');
    }

    public function edit ($id){
        $data = Staf::findOrFail($id);
        $dataJabatan = Jabatan::all();
        return view('guru.edit', compact('data', 'dataJabatan'));
    }

    public function update (Request $request, $id){
        $dataJabatan = Jabatan::where('nama', '=', 'Guru')->first();

        $data = Staf::findOrFail($id);
        $data->nama = $request->nama;
        $data->rate_gaji = $request->rate_gaji;
        $data->id_jabatan = $dataJabatan->id;
        $data->save();

        $user = User::findOrFail($data->user_id);
        $user->nama = $data->nama;
        $user->role = $dataJabatan->nama;
        $user->save();

        return redirect('akademik/guru');
    }
    
    public function reset ($id){
        
        $data = Staf::findOrFail($id);

        $user = User::findOrFail($data->user_id);
        $user->password = bcrypt($data->nis);
        $user->save();

        return redirect('akademik/guru');
    }
    
    public function delete (Request $request){
        
        $data = Staf::findOrFail($request->id);
        $data->delete();

        $user = User::findOrFail($data->user_id);
        $user->delete();

        return redirect('akademik/guru');
    }
}
