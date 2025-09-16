<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
class UserService
{
    /**
     * Summary of getAllUser
     *
     * @param array $filters
     * @param int $page
     * @param bool $includeTrashed
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllUser(array $filters = [], int $page = 15, bool $includeTrashed = false)
    {
       $query = User::with('role');

       if($includeTrashed){
        $query->withTrashed();
       }
       if(isset($filters['status'])){
            $query->where('status', $filters['status']);
       }
       if(isset($filters['role_id'])){
            $query->byRole($filters['role_id']);
       }
       // check khi ma search co trong
       $query->when(isset($filters['search']), function ($q) use ($filters){
            $q->where('name' , 'like' ,'%' . $filters['search'] . '%')
            ->orWhere('email' , 'like', '%' . $filters['search']. '%');
       });

       return $query->paginate($page);
    }

    // createUser updateUser getUserById deleteUser getUserRole uploadAvatar toggleUserStatus
    public function createUser(array $data): User{
        try{
        if(isset($data['avatar']) && $data['avatar'] instanceof UploadedFile){
            $data = $this->uploadAvatar($data['avatar']);
       }

        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
       }
        return User::create($data);
        }catch(Exception $e){
            throw new Exception('Faild to create user' .$e->getMessage());
        }

    }
    public function updateUser(User $user , array $data): User{
        try{
            if(isset($data['avatar']) && $data['avatar'] instanceof UploadedFile){
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                 $data['avatar'] = $this->uploadAvatar($data['avatar']);
            }
            if(isset($data['password']) && $data['password']){
                $data['password'] = Hash::make($data['password']);
            }
            else{
                unset($data['password']);
            }
            $user->update($data);
            return $user->fresh();

        }catch(Exception $e){
            throw new Exception('cant not update User' .$e->getMessage());
        }
    }
    // using soft delte
    public function deleteUser(User $user): bool
    {
        try{
                return $user->delete();
            }catch(Exception $e){
                throw new Exception('can not delte user' .$e->getMessage());
            }
    }
    public function forceDeleteUser(User $user):bool
    {
        try{
            if($user->avatar){
                Storage::disk('public')->delete($user->avatar);
            }
            return $user->forceDelete();
        }catch(Exception $e){
            throw new Exception('can not delete user' .$e->getMessage());
        }
    }

    public function restoreUser(int $idUser): ?User
    {
       try{
            $user = User::onlyTrashed()->find($idUser);
            if($user){
                $user->restore();
                return $user->fresh();
            }
            return null;
       }catch(Exception $e){
           throw new Exception('can not restore user' .$e->getMessage());
       }
    }

    public function toggleUserStatus(User $user): User
    {
        $user->update(['status' => !$user->status]);
        return $user->fresh();
    }

    public function getUserById(int $id, bool $includeTrashed = false): ?User
    {
            $query = User::with('role');

            if($includeTrashed){
                $query->withTrashed();
            }
            return $query->find($id);
    }

    public function getUserRole(int $id)
    {
        return User::with('role')
                ->find($id)
                ->paginate(15)
                ->role;
    }

};
