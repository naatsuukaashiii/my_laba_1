<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreUserRoleRequest;
use App\Http\Requests\DestroyUserRoleRequest;
use App\Models\User;
use App\Models\Role;
use App\DTO\UserRoleDTO;
use App\DTO\UserRoleCollectionDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
class UserRoleController extends Controller
{
    public function index($userId): JsonResponse
    {
        if (!auth()->user()->hasPermission('get-user-roles')) {
            return response()->json(['message' => 'Permission denied: get-user-roles'], 403);
        }
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $roles = $user->roles()->get();
        return response()->json(new UserRoleCollectionDTO(
            $roles->map(function ($role) use ($user) {
                return new UserRoleDTO(
                    user_id: $user->id,
                    role_id: $role->id
                );
            })->toArray()
        ));
    }
    public function store(StoreUserRoleRequest $request, $userId): JsonResponse
    {
        if (!auth()->user()->hasPermission('assign-role')) {
            return response()->json(['message' => 'Permission denied: assign-role'], 403);
        }
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $roleId = $request->input('role_id');
        $role = Role::find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        if ($user->roles()->where('role_id', $roleId)->exists()) {
            return response()->json(['message' => 'Role already assigned to user'], 400);
        }
        $user->roles()->attach($role, ['created_by' => auth()->id()]);
        return response()->json(new UserRoleDTO(
            user_id: $user->id,
            role_id: $role->id
        ), 201);
    }
    public function destroy(DestroyUserRoleRequest $request, $userId): JsonResponse
    {
        if (!auth()->user()->hasPermission('remove-role')) {
            return response()->json(['message' => 'Permission denied: remove-role'], 403);
        }
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $roleId = $request->input('role_id');
        if (!$user->roles()->where('role_id', $roleId)->exists()) {
            return response()->json(['message' => 'Role is not assigned to user'], 400);
        }
        $detached = $user->roles()->detach($roleId);
        Log::info('Role detached from user', [
            'user_id' => $userId,
            'role_id' => $roleId,
            'detached_count' => $detached
        ]);
        if ($detached === 0) {
            return response()->json(['message' => 'Failed to detach role'], 500);
        }
        return response()->json(['message' => 'User role detached']);
    }
    public function softDelete($userId, $roleId): JsonResponse
    {
        if (!auth()->user()->hasPermission('soft-delete-role')) {
            return response()->json(['message' => 'Permission denied: soft-delete-role'], 403);
        }
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $role = Role::find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        if (!$user->roles()->where('role_id', $roleId)->exists()) {
            return response()->json(['message' => 'Role is not assigned to user'], 400);
        }
        $user->roles()->updateExistingPivot($roleId, ['deleted_at' => now(), 'deleted_by' => auth()->id()]);
        return response()->json(['message' => 'User role softly deleted']);
    }
    public function restore($userId, $roleId): JsonResponse
    {
        if (!auth()->user()->hasPermission('restore-user-role')) {
            return response()->json(['message' => 'Permission denied: restore-user-role'], 403);
        }
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $role = Role::find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $pivot = $user->roles()->wherePivot('role_id', $roleId)->first();
        if (!$pivot || !$pivot->pivot->deleted_at) {
            return response()->json(['message' => 'Role is not softly deleted'], 400);
        }
        $user->roles()->updateExistingPivot($roleId, ['deleted_at' => null, 'deleted_by' => null]);
        return response()->json(['message' => 'User role restored']);
    }
    public function getPermissions($userId): JsonResponse
    {
        if (!auth()->user()->hasPermission('get-user-role-permissions')) {
            return response()->json(['message' => 'Permission denied: get-user-role-permissions'], 403);
        }
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $roles = $user->roles()->with('permissions')->get();
        if ($roles->isEmpty()) {
            return response()->json(['message' => 'User has no roles'], 400);
        }
        $permissions = $roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');
        return response()->json($permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'code' => $permission->code,
            ];
        }));
    }
}