<?php

namespace App\Http\Controllers;

use App\DTO\Role\RoleDTO;
use App\Http\Requests\StoreUserRoleRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\UserRole;
use Illuminate\Http\JsonResponse;

class UserRoleController extends Controller
{
    public function index(): JsonResponse
    {
        $userRoles = UserRole::with(['user', 'role'])->get();
        return response()->json($userRoles);
    }

    public function store(StoreUserRoleRequest $request): JsonResponse
    {
        $userRole = UserRole::create($request->toDTO()->toArray());
        return response()->json($userRole->load(['user', 'role']), 201);
    }

    public function show(UserRole $userRole): JsonResponse
    {
        return response()->json($userRole->load(['user', 'role']));
    }

    public function update(UpdateUserRoleRequest $request, UserRole $userRole): JsonResponse
    {
        $userRole->update($request->toDTO()->toArray());
        return response()->json($userRole->fresh()->load(['user', 'role']));
    }

    public function destroy(UserRole $userRole): JsonResponse
    {
        $this->authorize('delete-user-role', $userRole);
        $userRole->delete();
        return response()->json(null, 204);
    }
}
