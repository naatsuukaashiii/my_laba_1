<?php

namespace App\Http\Controllers;

use App\DTO\Permission\PermissionDTO;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function store(StorePermissionRequest $request): JsonResponse
    {
        if (!auth()->user()->hasPermission('get-list-permission')) {
            return response()->json(['message' => 'Permission denied: get-list-permission'], 403);
        }
        $permission = Permission::create($request->toDTO()->toArray());
        return response()->json($permission, 201);

    }
    public function show(Permission $permission): JsonResponse
    {
        return response()->json($permission);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $permission->update($request->toDTO()->toArray());
        return response()->json($permission);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $this->authorize('delete-permission', $permission);
        $permission->delete();
        return response()->json(null, 204);
    }



}
