<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContractPolicy
{
    use HandlesAuthorization;

    // Метод определяет, может ли пользователь просматривать договор
    public function view(User $user, Contract $contract): bool
    {
        return $user->id === $contract->user_id;
    }

}
