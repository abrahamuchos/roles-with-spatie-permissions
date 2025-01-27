<?php

namespace App\Http\Controllers\Api;

use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    /**
     * Get all users
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        Gate::authorize('viewAny', User::class);

        $users = User::all();

        return UserResource::collection($users);
    }

    /**
     * Show a specific user
     *
     * @param User $user
     *
     * @return UserResource
     * @throws AuthorizationException
     */
    public function show(User $user): UserResource
    {
        Gate::authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('create', User::class);

        //simulate create a new user

        return response()->json([], 201);
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('update', $user);

        //simulate update a user

        return response()->json();
    }

    /**
     * Delete a user
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(User $user): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('delete', $user);

        //Simula delete user

        return response()->json();
    }
}
