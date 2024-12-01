<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use File;
use Illuminate\Support\Facades\Hash;
use Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }
        return redirect('/login');
    }

    public function profile()
    {
        return view('auth.profile');
    }

    public function profileupdate(Request $request)
    {
        $input = $request->all();
        $rules = array(
          'userpp' => 'mimes:jpg,png',
          'username' => 'required',
        );

        $validation = Validator::make($input, $rules);
        if($validation->fails()){
          return response()->json(0);
        }
        $data = Auth::user();
        $data->name = $request->input('username');
        if ($request->hasFile('userpp')) {
        $image_path = $data->user_img;
        if(file_exists('images/'.$image_path)) {
          File::delete('images/'.$image_path);
          }
        $file = $request->file('userpp');
        $new_name = $file->hashName();
        $path = $file->move('images/', $new_name);
        $data->user_img = $new_name;
      }
        $data->save();
        return response()->json(1);
    }

    public function settings()
    {
        return view('auth.settings');
    }

    public function settingsupdate(Request $request)
    {
        if (!(Hash::check($request->input('current_pass'), Auth::user()->password))) {
            return response()->json(0);
          }
          if(strcmp($request->input('current_pass'), $request->input('new_pass')) == 0){
            return response()->json(1);
          }
          if(!(strcmp($request->input('new_pass'), $request->input('confirm_pass'))) == 0){
            return response()->json(2);
          }
          //dd($lastpass, $currentpass, $newpass, $confirmpass);
          $user = Auth::user();
          $user->password = Hash::make($request->input('new_pass'));
          $user->save();
          return response()->json(3);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
