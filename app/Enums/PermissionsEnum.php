<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case VIEW_USER = 'view_user';
    case CREATE_USER = 'create_user';
    case EDIT_USER = 'edit_user';
    case DELETE_USER = 'delete_user';

    case VIEW_POSTS = 'view_posts';
    case CREATE_POSTS = 'create_posts';
    case EDIT_POSTS = 'edit_posts';
    case DELETE_POSTS = 'delete_posts';

}
