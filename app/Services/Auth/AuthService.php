<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct()
    {
        $this->moduleName = "Auth";
    }
    public function register($data)
    {
        try{
        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role_id'   => 2,
            'phone_no'  => $data['phone_no'],
            'password'  => bcrypt($data['password']),
        ]);
            // Log in the user
            Auth::login($user);
            $success['token'] =  $user->createToken('authToken')->plainTextToken;
        $success['name'] =  $user->name;


        return ['data' => $success['name'], 'token' => $success['token']];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')];
        }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
    }
}
