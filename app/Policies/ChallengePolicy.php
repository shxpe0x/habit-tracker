<?php

namespace App\Policies;

use App\Models\Challenge;
use App\Models\User;

class ChallengePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Challenge $challenge): bool
    {
        return $user->id === $challenge->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Challenge $challenge): bool
    {
        return $user->id === $challenge->user_id;
    }

    public function delete(User $user, Challenge $challenge): bool
    {
        return $user->id === $challenge->user_id;
    }
}
