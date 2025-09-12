<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\delete;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getUser()
    {
        $user = User::paginate(10);
        return response()->json([
            'sucess' => true,
            'message' =>'get all user',
            'data' => $user,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeUser(StoreUserRequest $request)
    {
        $user = User::create([
            'name' =>$request->input("name"),
            'email' =>$request->input('email'),
            'password' =>Hash::make( $request->password),
        ]);
        try{
            if($user){
                return response()->json([
                'success' => true,
                'message' => 'Create Success',
                'data' => $user,
                ], 201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Not create user',
                ],400);
            }
        }catch(Exception $e){
                return response()->json([
                    'success' =>false,
                    'message' =>'Error create user',
                    'error' => $e->getMessage(),
                ],500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($user->password) : $user->password,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'update user',
            'data' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ], 200);
    } catch (Exception $e) {
        // Log error để debug nếu cần
        \Log::error('Failed to delete user: '.$e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete user. Please try again later.'
        ], 500);
    }
    }
}