<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Place;
use App\Models\User;

class PlacePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Place $place): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Place $place): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Place $place): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Place $place): bool
    {
        return false;
    }

    public function forceDelete(User $user, Place $place): bool
    {
        return false;
    }
}
