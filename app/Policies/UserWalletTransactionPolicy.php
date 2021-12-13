<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\UserWalletTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserWalletTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserWalletTransaction  $userWalletTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, UserWalletTransaction $userWalletTransaction)
    {
        return $this->accessCheck($user, $userWalletTransaction);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserWalletTransaction  $userWalletTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserWalletTransaction $userWalletTransaction)
    {
        return $this->accessCheck($user, $userWalletTransaction);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserWalletTransaction  $userWalletTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserWalletTransaction $userWalletTransaction)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserWalletTransaction  $userWalletTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, UserWalletTransaction $userWalletTransaction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserWalletTransaction  $userWalletTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, UserWalletTransaction $userWalletTransaction)
    {
        //
    }

    private function accessCheck($user, $userWalletTransaction)
    {
        if ($user->id === $userWalletTransaction->user->id) {
            return Response::allow();
        }

        return Response::deny('Sorry you can\'t do that!');
    }
}
