<?php

namespace App\Services\Customers;

use App\Models\Role\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomersService
{
    protected $moduleName;

    public function __construct()
    {
        $this->moduleName = "Customers";
    }


    public function customersList($view = null, $page = null,$search = null)
    {
        try {
            $customers = User::with('role')->orderBy('id', 'desc')->select('*')->selectRaw('name as label');
            if($search){
                $customers = $customers->where('name', 'like', '%' .$search. '%' )
                    ->orderByRaw('CASE
               WHEN name LIKE "'.$search.'%" THEN 1
               WHEN name LIKE "%'.$search.'%" THEN 2
               ELSE 3
               END');
            }
            if($view){
                $customers = $customers->paginate($view, ['*'], 'page', $page);
                return $customers;
            }
            $customers = $customers->get();
            return $customers;
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')];
        }
    }

    public function create()
    {
        try {
            $Roles = Role::get();

            return $Roles;
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')];
        }
    }


    public function store($data)
    {
        try {

            $customers = User::create([
                'name'          => $data['name'],
                'email'          => $data['email'],
                'password'          => bcrypt($data['password']),
                'role_id'          => $data['role_id'],
                'phone_no'          => $data['phone_no'],
            ]);
            return [
                'message' => config('constants.record_created')($this->moduleName),
                'data' => $customers
            ];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            return ['error' => config('constants.internal_error')];
        }
    }


    public function show($id)
    {
        try {
            if (!User::exists($id)) {
                return [
                    'error' => config('constants.not_found')($this->moduleName),
                ];
            }

            $customers = User::with('role')->where('id', $id)->first();

            return $customers;
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')];
        }
    }


    public function getEditData($id)
    {
        try {
            $Roles = Role::get();
            return [$this->show($id),$Roles];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')];
        }
    }


    public function update($data, $id)
    {
        try {
            $customers = User::where('id', $id)->first();
            if (!$customers) {
                return [
                    'error' => config('constants.not_found')($this->moduleName),
                ];
            }

            // update Tag Data.
            $update = [
                'name'          => $data['name'],
                'email'          => $data['email'],
                'role_id'          => $data['role_id'],
                'phone_no'          => $data['phone_no'],
            ];

            $customers->update($update);

            return [
                'message' => config('constants.record_updated')($this->moduleName),
                'data' => $customers
            ];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')];
        }
    }
    public function updateProfile($data, $id)
    {
        try {
            $customers = User::where('id', $id)->first();

            if (!$customers) {
                return [
                    'error' => config('constants.not_found')($this->moduleName),
                ];
            }

            // update Tag Data.
            $update = [
                'name'          => $data['name'],
                'email'          => $data['email'],
                'phone_no'          => $data['phone_no'],
            ];

            $customers->update($update);

            return [
                'message' => config('constants.record_updated')($this->moduleName),
                'data' => $customers
            ];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')];
        }
    }
    public function updatePassword($data)
    {
        try {
            $user = Auth::user();
            $user->password = Hash::make($data['password']);
            $user->save();

            return [
                'message' => config('constants.record_updated')($this->moduleName),
                'data' => 'success'
            ];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')];
        }
    }


    public function destroy($id)
    {
        try {
            $customers = User::where('id', $id)->first();
            if (!$customers) {
                return [
                    'error' => config('constants.not_found')($this->moduleName),
                ];
            }
            $customers->delete();
            return [
                'message' => config('constants.record_deleted')($this->moduleName),
            ];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName,$e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')];
        }
    }
}
