<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
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
       try{
            $filters = $request->all() ? $request->all() : [];
            dd($filters);
            $page = $request->get('per_page', 15);
            $includeTrashed = $request->boolean('include_trashed' , false);

            $users = $this->userService->getAllUser($filters, $page, $includeTrashed);

            return response()->json([
                'success' => true,
                'message' => 'User retrieved successlly',
                'data' => new UserCollection($users),
            ]);
       }catch(Exception $e){
         return response()->json([
            'success' => true,
            'message' => 'cant not get all user',
            'data' => $e->getMessage(),
         ], 500);
       }
    }
    /**
     *
     */
    public function store(StoreUserRequest $storeUserRequest)
    {
        try{
            $user = $this->userService->createUser($storeUserRequest->validation());

            return response()->json([
                'success' => true,
                'message' => 'User created successlly',
                'data' => new UserCollection($user),
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' => 'Failed to create user',
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
                'message' => 'User deleted successfully.',
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function restore(Request $request, int $idUser){
        try{
            $user = $this->userService->restoreUser($idUser);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found in trash'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'User restored successfully',
                'data' => new UserResource($user->load('role'))
            ]);

        }catch(Exception $e){
            return response()->json([
            'success' => true,
            'message' => 'Failded restore user',
            'error' => $e->getMessage(),
            ],500);
        }
    }

}
