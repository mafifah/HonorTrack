<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staf;
use App\Models\User;
use App\Models\Jabatan;
use Auth;

class StafController extends Controller
{
    public function index (){      
        $data = Staf::all();
        return view('staf.staf', compact('data'));
    }

    public function tambah (){
        $dataJabatan = Jabatan::all();
        return view('staf.tambah', compact('dataJabatan'));
    }

    public function simpan (Request $request){
        $dataJabatan = Jabatan::findOrFail($request->id_jabatan);
        //dd($request->all());
        $userData = [
            'nama' => $request->input('nama'),
            'username' => $request->input('nama'),
            'password' => bcrypt($request->input('nama')),
            'role' => $dataJabatan->nama,
        ];

        // Create user record
        $user = User::create($userData);

        $data = $request->except('_token', 'submit');
        $data['user_id'] = $user->id;

        Staf::create($data);

        return redirect('user/staf');
    }

    public function edit ($id){
        $data = Staf::findOrFail($id);
        $dataJabatan = Jabatan::all();
        return view('staf.edit', compact('data', 'dataJabatan'));
    }

    public function update (Request $request, $id){
        $dataJabatan = Jabatan::findOrFail($request->id_jabatan);

        $data = Staf::findOrFail($id);
        $data->nama = $request->nama;
        $data->rate_gaji = $request->rate_gaji;
        $data->save();

        $user = User::findOrFail($data->user_id);
        $user->nama = $data->nama;
        $user->role = $dataJabatan->nama;
        $user->save();

        return redirect('user/staf');
    }
    
    public function reset ($id){
        
        $data = Staf::findOrFail($id);

        $user = User::findOrFail($data->user_id);
        $user->password = bcrypt($data->nis);
        $user->save();

        return redirect('user/anggota');
    }
    
    public function delete (Request $request){
        
        $data = Staf::findOrFail($request->id);
        $data->delete();

        $user = User::findOrFail($data->user_id);
        $user->delete();

        return redirect('user/staf');
    }
}
