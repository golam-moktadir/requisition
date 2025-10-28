<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository
{
    const API_ENDPOINT_RESOURCE_NAME = 'users';
    const REGISTER_API_ENDPOINT_NAME = 'register';
    const LOGIN_API_ENDPOINT_NAME = 'login';
    const CURRENT_USER_API_ENDPOINT_NAME = 'current-user';

    public function model()
    {
        return User::class;
    }

    protected function applyDefaultCriteria($query)
    {
        // parent::applyDefaultCriteria($query);
        // $query->where('id', '<>', Auth::id());
        $query->orderBy('name', 'ASC');
    }

    protected function getSearchFields()
    {
        return ['username', 'email'];
    }

    public function generateAccessToken(User $user): string
    {
        return $user->createToken('authToken')->plainTextToken;
    }

    public function generateDefaultPassword(): string
    {
        return 'password';
    }

    public function updateOrCreate(string $email, array $modelData)
    {
        $existingRecord = $this->model()::where('email', $email)->first();

        if ($existingRecord) {
            $existingRecord->update($modelData);
        } else {
            $this->model()::create(array_merge(['email' => $email], $modelData));
        }
    }

}
