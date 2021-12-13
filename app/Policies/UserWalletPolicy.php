<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserWalletPolicy
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
     * @param  \App\Models\UserWallet  $userWallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, UserWallet $userWallet)
    {
        return $this->accessCheck($user, $userWallet);
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
     * @param  \App\Models\UserWallet  $userWallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserWallet $userWallet)
    {
        return $this->accessCheck($user, $userWallet);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserWallet  $userWallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserWallet $userWallet)
    {
        return $this->accessCheck($user, $userWallet);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserWallet  $userWallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, UserWallet $userWallet)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserWallet  $userWallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, UserWallet $userWallet)
    {
        //
    }

    private function accessCheck($user, $userWallet)
    {
        if ($user->id === $userWallet->user_id) {
            return Response::allow();
        }

        return Response::deny('Sorry you can\'t do that!');
    }
}
