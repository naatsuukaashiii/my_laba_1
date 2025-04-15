<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\DTO\Role\RoleDTO;
use App\DTO\Role\RoleCollectionDTO;
use Illuminate\Http\JsonResponse;
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        if (!auth()->user()->hasPermission('get-list-user')) {
            return response()->json(['message' => 'Permission denied: get-list-user'], 403);
        }
        $users = User::with('roles')->get();
        return response()->json(new RoleCollectionDTO(
            $users->map(function ($user) {
                return new UserDTO(
                    $user->id,
                    $user->username,
                    $user->email,
                    $user->birthday,
                    $user->roles->map(function ($role) {
                        return [
                            'id' => $role->id,
                            'name' => $role->name,
                            'code' => $role->code,
                        ];
                    })
                );
            })
        ));
    }
    public function store(StoreUserRequest $request): JsonResponse
    {
        if (!auth()->user()->hasPermission('create-user')) {
            return response()->json(['message' => 'Permission denied: create-user'], 403);
        }
        \Log::info('Creating user', ['data' => $request->all()]);
        $user = User::create(array_merge($request->validated(), [
            'password' => bcrypt($request->input('password')),
            'created_by' => auth()->id(),
        ]));
        \Log::info('User created', ['user' => $user]);
        return response()->json(new RoleDTO(
            $user->id,
            $user->username,
            $user->email,
            $user->birthday,
            []
        ), 201);
    }
    public function show($id): JsonResponse
    {
        if (!auth()->user()->hasPermission('read-user')) {
            return response()->json(['message' => 'Permission denied: read-user'], 403);
        }
        $user = User::with('roles')->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(new RoleDTO(
            $user->id,
            $user->username,
            $user->email,
            $user->birthday,
            $user->roles->map(function($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'code' => $role->code,
                ];
            })->toArray()
        ));
    }
    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        if (!auth()->user()->hasPermission('update-user')) {
            return response()->json(['message' => 'Permission denied: update-user'], 403);
        }
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->update($request->validated());
        return response()->json(new RoleDTO(
            $user->id,
            $user->username,
            $user->email,
            $user->birthday,
            $user->roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'code' => $role->code,
                ];
            })->toArray()
        ));
    }
    public function destroy($userId): JsonResponse
    {
        if (!auth()->user()->hasPermission('delete-user')) {
            return response()->json(['message' => 'Permission denied: delete-user'], 403);
        }
        $user = User::withTrashed()->find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->forceDelete();
        return response()->json(['message' => 'User permanently deleted']);
    }
    public function softDelete($id): JsonResponse
    {
        if (!auth()->user()->hasPermission('delete-user')) {
            return response()->json(['message' => 'Permission denied: delete-user'], 403);
        }
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->update([
            'deleted_at' => now(),
            'deleted_by' => auth()->id(),
        ]);
        return response()->json(['message' => 'User softly deleted']);
    }
    public function restore($id): JsonResponse
    {
        if (!auth()->user()->hasPermission('restore-user')) {
            return response()->json(['message' => 'Permission denied: restore-user'], 403);
        }
        $user = User::withTrashed()->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->update([
            'deleted_at' => null,
            'deleted_by' => null,
        ]);
        return response()->json(['message' => 'User restored']);
    }
}
