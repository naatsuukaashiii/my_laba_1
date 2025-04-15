<?php

namespace App\Http\Controllers;

use App\DTO\Permission\PermissionDTO;
use App\Http\Requests\StoreRolesPermissionsRequest;
use App\Http\Requests\UpdateRolesPermissionsRequest;
use App\Models\RolePermission;
use Illuminate\Http\JsonResponse;

class RolesPermissionsController extends Controller
{
    public function index(): JsonResponse
    {
        $rolePermissions = RolePermission::with(['role', 'permission'])->get();
        return response()->json($rolePermissions);
    }

    public function store(StoreRolesPermissionsRequest $request): JsonResponse
    {
        $rolePermission = RolePermission::create($request->toDTO()->toArray());
        return response()->json($rolePermission->load(['role', 'permission']), 201);
    }

    public function show(RolePermission $rolePermission): JsonResponse
    {
        return response()->json($rolePermission->load(['role', 'permission']));
    }

    public function update(UpdateRolesPermissionsRequest $request, RolePermission $rolePermission): JsonResponse
    {
        $rolePermission->update($request->toDTO()->toArray());
        return response()->json($rolePermission->fresh()->load(['role', 'permission']));
    }

    public function destroy(RolePermission $rolePermission): JsonResponse
    {
        $this->authorize('delete-role-permission', $rolePermission);
        $rolePermission->delete();
        return response()->json(null, 204);
    }
}
