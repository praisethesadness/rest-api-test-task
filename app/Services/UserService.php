<?php

namespace App\Services;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserService
{
    private $errorMessage;

    public function __construct()
    {
        $this->errorMessage = response(['error' => 'INTERNAL_ERROR'], 500);
    }

    public function createAndLogin(CreateUserRequest $request)
    {
        try {
            $user = User::create($request->validated());
            auth()->login($user);
        } catch (\Throwable $th) {
            return $this->errorMessage;
        }

        return $user;
    }

    public function getUser()
    {
        try {
            return auth()->user();
        } catch (\Throwable $th) {
            return $this->errorMessage;
        }
    }

    public function updateUser(UpdateUserRequest $request)
    {
        try {
            $user = User::find(auth()->id());
            $user->update($request->validated());
        } catch (\Throwable $th) {
            return $this->errorMessage;
        }        

        return $user;
    }

    public function deleteUser()
    {
        try {
            $user = User::find(auth()->id());
            $user->delete();
        } catch (\Throwable $th) {
            return $this->errorMessage;
        }

        return $user;
    }
}