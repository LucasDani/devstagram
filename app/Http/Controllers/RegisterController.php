<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //dd($request->get('name'));

        // MODIFICAR EL REQUEST PARA EL USERNAME
        $request->request->add(['username' => Str::slug($request->username)]);

        // VALIDACION
        $this->validate($request, [
            'name' => 'required|min:5|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:30',
            'password' => 'required|min:5|confirmed',
        ]);

        // INSERTAR EN LA BASE DE DATOS
        // Usando helpers
        
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // AUTENTICAR UN USUARIO

        /*auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);*/

        // Otra forma de autenticar

        auth()->attempt($request->only('email','password'));

        // REDIRECCIONAR AL USUARIO

        return redirect()->route('posts.index', ['user' => auth()->user()]);

    }
}
