<?php

return [
    /** dashboard */
    [
        'permissions' => 'statistics',
        'parent_permissions' => null,
        'childs' => null
    ],

    /** settings */
    [
        'permissions' => 'settings',
        'parent_permissions' => null,
        'childs' => [
            [
                'permissions' => 'settings_role',
                'childs' => [
                    ['permissions' => 'settings_role_add', "childs" => null],
                    ['permissions' => 'settings_role_edit', "childs" => null],
                    ['permissions' => 'settings_role_delete', "childs" => null],
                ]
            ]
        ]
    ],

    // add here again if there is some new permission data
];
