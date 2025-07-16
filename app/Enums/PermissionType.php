<?php

namespace App\Enums;

enum PermissionType: string
{
    case CREATE = 'create';
    case READ = 'read';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case UPLOAD = 'upload';
    case DOWNLOAD = 'download';

    public static function defaultPermissions(): array
    {
        return [
            self::CREATE,
            self::READ,
            self::UPDATE,
            self::DELETE,
            self::UPLOAD,
            self::DOWNLOAD,
        ];
    }
}
