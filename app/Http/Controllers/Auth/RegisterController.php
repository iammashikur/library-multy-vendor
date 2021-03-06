<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LibraryWallet;
use App\Providers\RouteServiceProvider;
use App\Models\User;
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

    protected function redirectTo(){
        if(auth()->user()
        ->hasRole(
            [
                'admin',
                'manager',
                'volunteer',
                'librarian',
                'writer'
            ]
            )){
            return '/admin/dashboard';
        }else{
            return '/';
        }
    }

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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits:11', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $user = new User();
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->password = Hash::make($data['password']);
        $user->save();

        if($data['type'] == 'librarian' or $data['type'] == 'writer'){
            $user->assignRole($data['type']);
        }

        /**
         *  make a librarian wallet
         */
        if($data['type'] == 'librarian'){
           $libraryWallet = new LibraryWallet();
           $libraryWallet->user_id = $user->id;
           $libraryWallet->save();
        }


        return $user;

    }
}
