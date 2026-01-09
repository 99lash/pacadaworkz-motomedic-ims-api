<?php


namespace App\Services;

use App\Exceptions\Auth\UserNotFoundException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{

    public function getAllUsers(array $params)
    {
        $query = User::query();

        /**
         * Searching Logic
         **/
        // by name
        if (isset($params['search'])) {
            $query->where('name', 'like', "%{$params['search']}%");
        }
        // var_dump($params);

        /**
         * Filter Logic
         **/
        // by role
        if (isset($params['role_id'])) {
            $query->where('role_id', $params['role_id']);
        }
        /**
         * Sorting Logic
         **/
        $sortBy = $params['sort_by'] ?? 'created_at';
        $sortOrder = $params['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        /**
         * Pagination Logic, so abstract hahaha...
         **/
        $limit = $params['limit'] ?? 10;
        return $query->paginate($limit);
    }

    public function getUserById(int $id)
    {
        // return User::find($id);
        $user = User::with('role')->find($id);
        if (!$user) throw new UserNotFoundException($id, 'id');
        return $user;
    }

    public function createUser(array $fields)
    {
        $isDefaultPassword = $fields['is_default_password'];
        unset($fields['is_default_password']);
        /**
         *
         * if `isDefaultPassword` is set to TRUE override the `password` field by USER_DEFAULT_PASSWORD,
         * NOTE: set niyo yung USER_DEFAULT_PASSWORD sa .env
         *
         **/
        $password = $isDefaultPassword ? env('USER_DEFAULT_PASSWORD', 'Password@2025!') : $fields['password'];
        $fields['password'] = Hash::make($password);
        $fields['is_active'] = true;

        // prevent duplicate entries by checking and restoring soft deleted users.
        $user = User::withTrashed()->where('email', $fields['email'])->first();
        if ($user && $user->trashed()) {
            $user->restore();
            $user->update($fields);
            return $user;
        }
        return User::create($fields);
    }

    public function updateUserById(int $id, array $fields)
    {
        $user = User::find($id);
        if (!$user) throw new UserNotFoundException($id, 'id');
        // if (isset($fields['password'])) {
        //     unset($fields['password']);
        // }
        // if (isset($fields['deleted_at'])) {
        //     unset($fields['deleted_at']);
        // }
        $user->update($fields);
        return $user;
    }

    public function deleteUserById(int $id)
    {
        // return User::destroy($id);
        $user = User::find($id);
        if (!$user) throw new UserNotFoundException($id, 'id');

        return $user->delete();
    }

    public function resetPasswordById(int $id, array $fields)
    {
        $user = User::find($id);

        if (!$user) throw new UserNotFoundException($id, 'id');

        if ($user) {
            $isDefaultPassword = $fields['is_default_password'];
            /**
             *
             * if `isDefaultPassword` is set to TRUE override the `newPassword` field by USER_DEFAULT_PASSWORD,
             * NOTE: set niyo yung USER_DEFAULT_PASSWORD sa .env
             *
             **/
            $newPassword = $isDefaultPassword ? env('USER_DEFAULT_PASSWORD', 'Password@2026!') : $fields['new_password'];

            $user->update([
                'password' => Hash::make($newPassword)
            ]);
            return true;
        }

        return false;
    }

    public function changePasswordById(int $id, array $fields) {
        $user = User::find($id);

        if (!$user) throw new UserNotFoundException($id, 'id');

        // $currentPassword = $fields['current_password'];
        $newPassword = $fields['new_password'];

        // NOTE: current_password validation is handled by ChangePasswordRequest.php
        // di ko na chineck dito kasi sa ChangePasswordRequest.php palang ay vinalidate na, haha wow laravels
        // if (!Hash::check($currentPassword, $user->password))

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return true;
    }
}
