<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Hash;
use Request;
use function Pest\Laravel\delete;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

      /*  //Apply middleware
       $this->middleware('auth.scantum');
       $this->middleware('check.user.status');
       $this->middleware('check.user.role:admin,manager')->except(['index,show']); */
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'data' => User::all(),
        ]);

    }

    /**
     *
     */
    public function store(Request $storeUserRequest )
    {
        try{
            dd($storeUserRequest->all());
            $user = User::create([
            'name' => $storeUserRequest['name'],
            'email' => $storeUserRequest['email'],
            'phone' => $storeUserRequest['phone'],
            'role_id' => $storeUserRequest['role_id'],
            'status' => $storeUserRequest['status'],
            'password' => $storeUserRequest['password'],
        ]);
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $user,
        ], 201);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to register user. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }

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


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'data' => new UserResource($user->load()),
        ]);
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
       try{
            $user = $this->userService->updateUser($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => new UserResource($user->load('role')),
            ]);

       }catch(Exception $e){
            return response()->json([
            'success' => false,
            'message' => 'Failed to update user. Please try again later.',
            'error' => $e->getMessage(),
            ] , 500);
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try{
            $this->userService->deleteUser($user);
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' =>$e->getMessage(),
            ], 500);
        }
    }

    // delte not soft delete
    public function forceDestroy(Request $request, int $idUser)
    {
        try{
            $user = User::withTrashed()->findOrFail($idUser);
            $this->userService->forceDeleteUser($user);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ], 200);
    } catch (\Exception $e) {
        // Log error để debug nếu cần
        \Log::error('Failed to delete user: '.$e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete user. Please try again later.'
        ], 500);
    }
    }
}