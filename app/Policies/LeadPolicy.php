<?php
namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['ADMIN', 'COUNSELOR']);
    }

    public function view(User $user, Lead $lead): bool
    {
        if ($user->role === 'COUNSELOR') {
            return $this->isLeadMaintainer($lead, $user);
        }
        return $user->role === 'ADMIN';
    }

    public function create(User $user): bool
    {
        return $user->role === 'ADMIN';
    }

    public function update(User $user, Lead $lead): bool
    {
        if ($user->role === 'COUNSELOR') {
            return $this->isLeadMaintainer($lead, $user);
        }
        return $user->role === 'ADMIN';
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->role === 'ADMIN';
    }

    private function isLeadMaintainer(Lead $lead, User $user)
    {
        return $lead->maintainers()->where('user_id', $user->id)->exists();
    }
}
