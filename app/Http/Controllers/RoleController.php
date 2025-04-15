<?php

namespace App\Http\Controllers;

use App\DTO\Role\RoleDTO;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->toDTO()->toArray());
        return response()->json($role, 201);
    }

    public function show(Role $role): JsonResponse
    {
        return response()->json($role);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role->update($request->toDTO()->toArray());
        return response()->json($role);
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete-role', $role);

        $role->delete();
        return response()->json(null, 204);
    }
}
