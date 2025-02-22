<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreRolePermissionRequest;
use App\Http\Requests\DestroyRolePermissionRequest;
use App\Models\Role;
use App\Models\Permission;
use App\DTO\RolePermissionDTO;
use App\DTO\RolePermissionCollectionDTO;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
class RolePermissionController extends Controller
{
    public function index(Role $role): JsonResponse
    {
        if (!auth()->user()->hasPermission('get-role-permissions')) {
            return response()->json(['message' => 'Permission denied: get-role-permissions'], 403);
        }
        Log::info('Fetching permissions for role', [
            'role_id' => $role->id
        ]);
        $permissions = $role->permissions()->get();
        Log::info('Permissions fetched', [
            'role_id' => $role->id,
            'permissions' => $permissions->pluck('id')->toArray()
        ]);
        return response()->json(new RolePermissionCollectionDTO(
            $permissions->map(function ($permission) use ($role) {
                return new RolePermissionDTO(
                    role_id: $role->id,
                    permission_id: $permission->id
                );
            })->toArray()
        ));
    }
    public function store(StoreRolePermissionRequest $request, Role $role): JsonResponse
    {
        if (!auth()->user()->hasPermission('assign-permission-to-role')) {
            return response()->json(['message' => 'Permission denied: assign-permission-to-role'], 403);
        }
        $permission = Permission::findOrFail($request->input('permission_id'));
        if ($role->permissions()->where('permission_id', $permission->id)->exists()) {
            return response()->json(['message' => 'Permission already assigned to role'], 400);
        }
        $role->permissions()->attach($permission, ['created_by' => auth()->id()]);
        Log::info('Permission assigned to role', [
            'role_id' => $role->id,
            'permission_id' => $permission->id
        ]);
        return response()->json(new RolePermissionDTO(
            role_id: $role->id,
            permission_id: $permission->id
        ), 201);
    }
    public function destroy(DestroyRolePermissionRequest $request, $roleId): JsonResponse
    {
        if (!auth()->user()->hasPermission('remove-permission-from-role')) {
            return response()->json(['message' => 'Permission denied: remove-permission-from-role'], 403);
        }
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $permissionId = $request->input('permission_id');

        if (!$role->permissions()->where('permission_id', $permissionId)->exists()) {
            return response()->json(['message' => 'Permission is not assigned to role'], 400);
        }
        $detached = $role->permissions()->detach($permissionId);
        Log::info('Permission detached from role', [
            'role_id' => $roleId,
            'permission_id' => $permissionId,
            'detached_count' => $detached
        ]);
        if ($detached === 0) {
            return response()->json(['message' => 'Failed to detach permission'], 500);
        }
        return response()->json(['message' => 'Role permission detached']);
    }
    public function softDelete(Role $role, Permission $permission): JsonResponse
    {
        if (!auth()->user()->hasPermission('soft-delete-permission-from-role')) {
            return response()->json(['message' => 'Permission denied: soft-delete-permission-from-role'], 403);
        }
        $pivot = $role->permissions()->wherePivot('permission_id', $permission->id)->first();
        if (!$pivot || !is_null($pivot->pivot->deleted_at)) {
            Log::warning('Permission is not assigned or already softly deleted', [
                'role_id' => $role->id,
                'permission_id' => $permission->id
            ]);
            return response()->json(['message' => 'Permission is not assigned or already softly deleted'], 400);
        }
        $role->permissions()->updateExistingPivot($permission->id, [
            'deleted_at' => now(),
            'deleted_by' => auth()->id()
        ]);
        Log::info('Permission softly deleted from role', [
            'role_id' => $role->id,
            'permission_id' => $permission->id
        ]);
        return response()->json(['message' => 'Role permission softly deleted']);
    }
    public function restore(Role $role, Permission $permission): JsonResponse
    {
        if (!auth()->user()->hasPermission('restore-permission-to-role')) {
            return response()->json(['message' => 'Permission denied: restore-permission-to-role'], 403);
        }
        $pivot = $role->permissions()->wherePivot('permission_id', $permission->id)->first();
        if (!$pivot || !$pivot->pivot->deleted_at) {
            return response()->json(['message' => 'Permission is not softly deleted'], 400);
        }
        $role->permissions()->updateExistingPivot($permission->id, [
            'deleted_at' => null,
            'deleted_by' => null
        ]);
        Log::info('Permission restored to role', [
            'role_id' => $role->id,
            'permission_id' => $permission->id
        ]);

        return response()->json(['message' => 'Role permission restored']);
    }
    public function getUsers(Role $role): JsonResponse
    {
        if (!auth()->user()->hasPermission('get-users-with-role')) {
            return response()->json(['message' => 'Permission denied: get-users-with-role'], 403);
        }
        $users = $role->users()->get();
        return response()->json($users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ];
        }));
    }
}