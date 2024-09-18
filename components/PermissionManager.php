<?php

namespace app\components;

class PermissionManager {
    // Privilege
    public const PRIVILEGE_REPORT = 'report';

    public const PRIVILEGE_QUEUES_CREATE_TOKEN = 'queues.create_token';
    public const PRIVILEGE_QUEUES_CALL = 'queues.call';
    public const PRIVILEGE_QUEUES_MONITOR = 'queues.monitor';

    public const PRIVILEGE_ROLES = 'roles';

    public const PRIVILEGE_USERS = 'users';

    public const PRIVILEGE_ADS = 'ads';

    // Roles
    public const ROLE_ADMIN = 1;
    public const ROLE_SERVER = 2;
    public const ROLE_KIOSK = 3;
    public const ROLE_MONITOR = 4;

    public const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_SERVER => 'Server',
        self::ROLE_KIOSK => 'Kiosk',
        self::ROLE_MONITOR => 'Monitor',
    ];

    // Permissions
    public const ROLE_PRIVILEGE = [
        self::ROLE_ADMIN => [
            self::PRIVILEGE_REPORT,
            self::PRIVILEGE_ROLES,
            self::PRIVILEGE_USERS,
            self::PRIVILEGE_QUEUES_MONITOR,
            self::PRIVILEGE_ADS
        ],
        self::ROLE_SERVER => [
            self::PRIVILEGE_QUEUES_CALL
        ],
        self::ROLE_KIOSK => [
            self::PRIVILEGE_QUEUES_CREATE_TOKEN
        ],
        self::ROLE_MONITOR => [
            self::PRIVILEGE_QUEUES_MONITOR
        ],
    ];

    public const TASK_PRIVILEGE = [
        'reports' => self::PRIVILEGE_REPORT,
        'queues' => [
            'kiosk' => self::PRIVILEGE_QUEUES_CREATE_TOKEN,
            'generate' => self::PRIVILEGE_QUEUES_CREATE_TOKEN,
            'current-token' => self::PRIVILEGE_QUEUES_CALL,
            'call' => self::PRIVILEGE_QUEUES_CALL,
            'call-next' => self::PRIVILEGE_QUEUES_CALL,
            'recall' => self::PRIVILEGE_QUEUES_CALL,
            'forward' => self::PRIVILEGE_QUEUES_CALL,
            'call-token' => self::PRIVILEGE_QUEUES_CALL,
            'open-close' => self::PRIVILEGE_QUEUES_CALL,
            'new-token-in-queue' => self::PRIVILEGE_QUEUES_CALL,
            'monitor' => self::PRIVILEGE_QUEUES_MONITOR,
            'monitor-socket' => self::PRIVILEGE_QUEUES_MONITOR,
        ],
        'roles' => self::PRIVILEGE_ROLES,
        'users' => self::PRIVILEGE_USERS,
        'ads' => self::PRIVILEGE_ADS
    ];

    public const PRIVILEGE_MENU = [
        self::ROLE_ADMIN => [
            '/reports' => 'Report',
            '/ads' => 'Ads',
            '/roles' => 'Roles',
            '/users' => 'Users',
            '/queues/monitor' => 'Monitor',
        ],
        self::ROLE_SERVER => [
            '/queues/call' => 'Call'
        ],
        self::ROLE_KIOSK => [
            '/queues/kiosk' => 'Kiosk'
        ],
        self::ROLE_MONITOR => [
            '/queues/monitor' => 'Monitor'
        ]
    ];
}

?>