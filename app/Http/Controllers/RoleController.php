<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\DTO\RoleDTO;
use App\DTO\RoleCollectionDTO;
use Illuminate\Http\JsonResponse;
class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        if (!auth()->user()->hasPermission('get-list-role')) {
            return response()->json(['message' => 'Permission denied: get-list-role'], 403);
        }
        $roles = Role::with('permissions')->get();
        return response()->json(new RoleCollectionDTO(
            $roles->map(function ($role) {
                return new RoleDTO(
                    id: $role->id,
                    name: $role->name,
                    description: $role->description,
                    code: $role->code,
                    permissions: $role->permissions->map(fn($permission) => [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'code' => $permission->code,
                    ])->toArray()
                );
            })->toArray()
        ));
    }
    public function store(StoreRoleRequest $request): JsonResponse
    {
        if (!auth()->user()->hasPermission('create-role')) {
            return response()->json(['message' => 'Permission denied: create-role'], 403);
        }
        $role = Role::create(array_merge($request->validated(), [
            'created_by' => auth()->id(),
        ]));
        return response()->json(new RoleDTO(
            id: $role->id,
            name: $role->name,
            description: $role->description,
            code: $role->code,
            permissions: []
        ), 201);
    }
    public function show($roleId): JsonResponse
    {
        if (!auth()->user()->hasPermission('read-role')) {
            return response()->json(['message' => 'Permission denied: read-role'], 403);
        }
        $role = Role::with('permissions')->find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json(new RoleDTO(
            id: $role->id,
            name: $role->name,
            description: $role->description,
            code: $role->code,
            permissions: $role->permissions->map(fn($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
                'code' => $permission->code,
            ])->toArray()
        ));
    }
    public function update(UpdateRoleRequest $request, $roleId): JsonResponse
    {
        if (!auth()->user()->hasPermission('update-role')) {
            return response()->json(['message' => 'Permission denied: update-role'], 403);
        }
        $role = Role::find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $validated = $request->validated();
        $role->update($validated);
        return response()->json(new RoleDTO(
            id: $role->id,
            name: $role->name ?? null,
            description: $role->description ?? null,
            code: $role->code ?? null,
            permissions: $role->permissions->map(fn($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
                'code' => $permission->code,
            ])->toArray()
        ));
    }
    public function destroy($roleId): JsonResponse
    {
        if (!auth()->user()->hasPermission('delete-role')) {
            return response()->json(['message' => 'Permission denied: delete-role'], 403);
        }
        $role = Role::withTrashed()->find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->forceDelete();

        return response()->json(['message' => 'Role permanently deleted']);
    }
    public function softDelete($roleId): JsonResponse
    {
        if (!auth()->user()->hasPermission('delete-role')) {
            return response()->json(['message' => 'Permission denied: delete-role'], 403);
        }
        $role = Role::find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        if ($role->trashed()) {
            return response()->json(['message' => 'Role is already softly deleted'], 400);
        }
        $role->delete();
        return response()->json(['message' => 'Role softly deleted']);
    }
    public function restore($roleId): JsonResponse
    {
        if (!auth()->user()->hasPermission('restore-role')) {
            return response()->json(['message' => 'Permission denied: restore-role'], 403);
        }
        $role = Role::withTrashed()->find($roleId);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        if (!$role->trashed()) {
            return response()->json(['message' => 'Role is not softly deleted'], 400);
        }
        $role->restore();
        return response()->json(['message' => 'Role restored']);
    }
}