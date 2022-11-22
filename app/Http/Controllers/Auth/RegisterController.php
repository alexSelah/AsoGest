<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'numSocio' => ['required','integer'],
            'nombre' => ['required','string','max:255'],
            '1apellido' => ['required','string','max:255'],
            'fnacimiento' => ['required','date_format:d-m-Y', 'after:-14 years'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required','max:15','unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'DNI' => ['required','string','max:9'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'numSocio' => $data['numSocio'],
            'nombre' => $data['nombre'],
            '1apellido' => $data['1apellido'],
            '2apellido' => $data['2apellido'],
            'DNI' => $data['DNI'],
            'email' => $data['email'],
            'username' => $data['username'],
            'sexo' => $data['sexo'],
            'fnacimiento' => $data['fnacimiento'],
            'direccion' => $data['direccion'],
            'localidad' => $data['localidad'],
            'provincia' => $data['provincia'],
            'telefono' => $data['telefono'],
            'notas' => $data['notas'],
            'foto' => $data['foto'],
            'habilitado' => $data['habilitado'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
