<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;

class UserService
{    
    public function createAndLogin(array $credentials)
    {
        $user = User::create($credentials);
        auth()->login($user);

        return new UserResource($user);
    }

    public function getUser()
    {
        return new UserResource(auth()->user());
    }

    public function updateUser(array $credentials)
    {
        $user = User::find(auth()->id());
        $user->update($credentials);

        return new UserService($user);
    }

    public function deleteUser()
    {
        $user = User::find(auth()->id());
        $user->delete();

        return new UserService($user);
    }
}
