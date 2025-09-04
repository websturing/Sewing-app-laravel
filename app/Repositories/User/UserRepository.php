<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Carbon;

class UserRepository implements UserRepositoryInterface
{
    public function all(array $filters)
    {
        $query = User::with('roles');

        $query->when(
            $filters['email'] ?? null,
            fn($q, $email) => $q->where('email', 'LIKE', "%{$email}%")
        );

        $query->when(
            $filters['name'] ?? null,
            fn($q, $name) => $q->where('name', 'LIKE', "%{$name}%")
        );

        $query->when(
            ($filters['date_from'] ?? null) && ($filters['date_to'] ?? null),
            fn($q) => $q->whereBetween('created_at', [
                Carbon::parse($filters['date_from'])->startOfDay(),
                Carbon::parse($filters['date_to'])->endOfDay(),
            ])
        );

        return $query; // <--- jangan pakai get()
    }

    public function userWithRoles()
    {
        return User::with(['roles', 'activities'])->get();
    }

    public function userWithActivities($id)
    {
        return User::where('id', $id)
            ->with(['activities'])
            ->first();
    }

    public function findById(int $userId)
    {
        return User::findOrFail($userId);
    }

    public function create(array $userData)
    {
        return User::create($userData);
    }

    public function update(int $userId, array $userData)
    {
        $user = User::findOrFail($userId);
        $user->update($userData);
        return $user;
    }

    public function delete(int $userId)
    {
        $user = User::findOrFail($userId);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
