<?php

namespace App\Policies;

use App\Models\Ebook;
use App\Models\User;

class EbookPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Ebook $ebook): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Ebook $ebook): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Ebook $ebook): bool
    {
        return $user->is_admin;
    }

    public function publish(User $user, Ebook $ebook): bool
    {
        return $user->is_admin;
    }

    public function archive(User $user, Ebook $ebook): bool
    {
        return $user->is_admin;
    }
}
