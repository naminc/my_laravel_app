<?php

namespace App\Repositories;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository implements UserRepositoryInterface
{
    public function __construct() {

    }
    public function findByEmail(string $email) {
        return User::where('email', $email)->first();
    }
    public function create(array $data) {
        return User::create($data);
    }
    public function all() {
        return User::all();
    }
    public function find($id) {
        return User::findOrFail($id);
    }
    public function update($id, array $data) {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }
    public function delete($id)
    {
        return User::destroy($id);
    } 
    public function updatePassword(int $userId, string $hashedPassword): bool
    {
        return User::where('id', $userId)->update(['password' => $hashedPassword]);
    }
}