<?php
namespace App\ApiePlayground\Permission\Enums;

enum UserRole: string {
    case ADMIN = 'Admin user';
    case MANAGER = 'IT Manager';
    case USER = 'User';
    case EDITOR = 'Editor';
}