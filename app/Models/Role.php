<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const ADMINSTRATOR = 1;
    public const ADMINISTRATOR_NAME = 'Administrator';

    public const STAFF = 2;
    public const STAFF_NAME = 'Staff';

    /**
     * @var array
     * list of id cannot deletable
     */
    public const IDS_CANNOT_EDIT_OR_DELETE = [
        self::ADMINSTRATOR,
        self::STAFF,
    ];

    /**
     * @var array
     * list of name cannot allowed
     */
    public const NAMES_CANNOT_ALLOWED = [
        self::ADMINISTRATOR_NAME,
        self::STAFF_NAME,
    ];

    /**
     * Get list of name cannot allowed
     * @return array
     */
    public static function getNamesCannotAllowed(): array
    {
        return self::NAMES_CANNOT_ALLOWED;
    }
}
