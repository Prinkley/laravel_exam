<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Thing;
use App\Models\User;

class ThingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Thing $thing): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Thing $thing): bool
    {
        return $user->isAdmin() || $user->id === $thing->master_id;
    }

    public function delete(User $user, Thing $thing): bool
    {
        return $user->isAdmin() || $user->id === $thing->master_id;
    }

    public function assign(User $user, Thing $thing): bool
    {
        return $user->id === $thing->master_id;
    }

    public function restore(User $user, Thing $thing): bool
    {
        return false;
    }

    public function forceDelete(User $user, Thing $thing): bool
    {
        return false;
    }
}
