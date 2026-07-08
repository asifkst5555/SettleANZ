<?php

namespace App\Policies;

use App\Models\DownloadToken;
use App\Models\User;

class DownloadTokenPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, DownloadToken $token): bool
    {
        return $user->is_admin;
    }

    public function revoke(User $user, DownloadToken $token): bool
    {
        return $user->is_admin;
    }
}
