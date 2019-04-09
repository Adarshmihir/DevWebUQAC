<?php

namespace App\Http\Controllers\Auth;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        parent::__construct();
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
            'cli_pseudo' => 'required|string|max:255',
            'cli_mel' => 'required|string|email|max:255|unique:t_e_client_cli',
            'cli_civilite' => 'required|string|max:4',
            'cli_motpasse' => 'required|string|min:6|confirmed',
            'cli_nom' => 'required|string',
            'cli_prenom' => 'required|string',
            'cli_telfixe' => 'required_without_all:cli_telportable',
            'cli_telportable' => 'required_without_all:cli_telfixe',
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
        return Client::create([
            'cli_pseudo' => $data['cli_pseudo'],
            'cli_mel' => $data['cli_mel'],
            'cli_motpasse' => bcrypt($data['cli_motpasse']),
            'cli_civilite' => $data['cli_civilite'],
            'cli_nom' => $data['cli_nom'],
            'cli_prenom' => $data['cli_prenom'],
            'cli_telfixe' => $data['cli_telfixe'],
            'cli_telportable' => $data['cli_telportable'],
        ]);
    }
}
