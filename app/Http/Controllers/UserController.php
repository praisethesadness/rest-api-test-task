<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{        
    public function __construct()
    {
        $this->middleware('auth:api')->except('store');
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $user = User::create($request->validated());
            auth()->login($user);
        } catch (\Throwable $th) {
            return response(
                ['error' => 'INTERNAL_ERROR'],
                status: 500
            );
        }        

        return $user;
    }
    
    public function show()
    {
        try {
            return auth()->user();
        } catch (\Throwable $th) {
            return response(
                ['error' => 'INTERNAL_ERROR'],
                status: 500
            );
        }
    }

    public function update(UpdateUserRequest $request)
    {
        try {
            $user = User::find(auth()->id());
            $user->update($request->validated());
        } catch (\Throwable $th) {
            return response(
                ['error' => 'INTERNAL_ERROR'],
                status: 500
            );
        }        

        return $user;
    }
    
    public function destroy()
    {
        try {
            User::find(auth()->id())->delete();
        } catch (\Throwable $th) {
            return response(
                ['error' => 'INTERNAL_ERROR'],
                status: 500
            );
        }        
    }
}
