<?php
namespace App\Http\Controllers;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use App\DTO\PermissionDTO;
use App\DTO\PermissionCollectionDTO;
use Illuminate\Http\JsonResponse;
class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        if (!auth()->user()->hasPermission('get-list-permission')) {
            return response()->json(['message' => 'Permission denied: get-list-permission'], 403);
        }
        $permissions = Permission::all();
        return response()->json(new PermissionCollectionDTO(
            $permissions->map(function ($permission) {
                return new PermissionDTO(
                    id: $permission->id,
                    name: $permission->name,
                    description: $permission->description,
                    code: $permission->code
                );
            })->toArray()
        ));
    }
    public function store(StorePermissionRequest $request): JsonResponse
    {
        if (!auth()->user()->hasPermission('create-permission')) {
            return response()->json(['message' => 'Permission denied: create-permission'], 403);
        }
        $permission = Permission::create(array_merge($request->validated(), [
            'created_by' => auth()->id(),
        ]));
        return response()->json(new PermissionDTO(
            id: $permission->id,
            name: $permission->name,
            description: $permission->description,
            code: $permission->code
        ), 201);
    }
    public function show($permissionId): JsonResponse
    {
        if (!auth()->user()->hasPermission('read-permission')) {
            return response()->json(['message' => 'Permission denied: read-permission'], 403);
        }
        $permission = Permission::find($permissionId);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        return response()->json(new PermissionDTO(
            id: $permission->id,
            name: $permission->name,
            description: $permission->description,
            code: $permission->code
        ));
    }
    public function update(UpdatePermissionRequest $request, $permissionId): JsonResponse
    {
        if (!auth()->user()->hasPermission('update-permission')) {
            return response()->json(['message' => 'Permission denied: update-permission'], 403);
        }
        $permission = Permission::find($permissionId);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        $permission->update($request->validated());
        return response()->json(new PermissionDTO(
            id: $permission->id,
            name: $permission->name,
            description: $permission->description,
            code: $permission->code
        ));
    }
    public function destroy($permissionId): JsonResponse
    {
        if (!auth()->user()->hasPermission('delete-permission')) {
            return response()->json(['message' => 'Permission denied: delete-permission'], 403);
        }
        $permission = Permission::withTrashed()->find($permissionId);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        $permission->forceDelete();
        return response()->json(['message' => 'Permission permanently deleted']);
    }
    public function softDelete($permissionId): JsonResponse
    {
        if (!auth()->user()->hasPermission('delete-permission')) {
            return response()->json(['message' => 'Permission denied: delete-permission'], 403);
        }

        $permission = Permission::find($permissionId);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        if ($permission->trashed()) {
            return response()->json(['message' => 'Permission is already softly deleted'], 400);
        }
        $permission->delete();
        return response()->json(['message' => 'Permission softly deleted']);
    }
    public function restore($permissionId): JsonResponse
    {
        if (!auth()->user()->hasPermission('restore-permission')) {
            return response()->json(['message' => 'Permission denied: restore-permission'], 403);
        }
        $permission = Permission::withTrashed()->find($permissionId);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        if (!$permission->trashed()) {
            return response()->json(['message' => 'Permission is not softly deleted'], 400);
        }
        $permission->restore();
        return response()->json(['message' => 'Permission restored']);
    }
}