<?php

namespace App\Http\Controllers;

use App\Enums\RolesEnum;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Get all posts based on your role.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Post::class);
        $user = Auth::user();

        if($user->hasRole(RolesEnum::ADMIN) || $user->hasRole(RolesEnum::GUEST)){
            $posts = Post::all();

        }else{
            $posts = Post::where('user_id', $user->id)->get();
        }

        return PostResource::collection($posts);
    }
}
