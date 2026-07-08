<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Campaign $campaign): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Campaign $campaign): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->is_admin;
    }

    public function send(User $user, Campaign $campaign): bool
    {
        return $user->is_admin && $campaign->isDraft();
    }
}
