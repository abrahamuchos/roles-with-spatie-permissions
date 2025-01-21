<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case VIEW_USER = 'view_user';
    case CREATE_USER = 'create_user';
    case EDIT_USER = 'edit_user';
    case DELETE_USER = 'delete_user';

    case VIEW_BOOKS = 'view_books';
    case CREATE_BOOKS = 'create_books';
    case EDIT_BOOKS = 'edit_books';
    case DELETE_BOOKS = 'delete_books';

}
