<?php

namespace App\Http\Controllers\Api;

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


}
