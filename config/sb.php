<?php

return [
    'admin_title' => 'SB Admin',
    'date_format' => 'j M Y G:i:s',

    'seed' => [
        'admin_email' => env('SEED_ADMIN_EMAIL', 'admin@example.com'),
        'admin_password' => env('SEED_ADMIN_PASSWORD', 'password'),
        'editor_email' => env('SEED_EDITOR_EMAIL', 'editor@example.com'),
    ],

    'sidebar_nav' => [
        ['dashboard', 'Dashboard', 'tachometer-alt'],
        ['pages', 'Pages', 'file-alt', 'interface', 'pages.index'],
        ['services', 'Services', 'hand-point-right', 'interface', 'services.index'],
        ['portfolio', 'Portfolio', 'briefcase', 'interface', 'portfolio.index'],
        ['timeline', 'Timeline', 'stream', 'interface', 'timeline.index'],
        ['team', 'Team', 'star', 'interface', 'team.index'],
        ['contacts', 'Contacts', 'table', 'utilities', 'contacts.index'],
        ['strings', 'Strings', 'language', 'utilities', 'strings.index'],
        ['options', 'Options', 'cogs', 'utilities', 'options'],
        ['users', 'Users', 'users', 'utilities', 'users.index'],
        // ['permissions', 'Permissions', 'user-check', 'utilities', 'permissions.index'],
        // ['logs', 'Logs', 'history', 'utilities', 'logs.index'],
    ],

    'dropdown_nav' => [],

    'options' => [
        'site_title' => 'Start Bootstrap',
        'admin_email' => env('SB_ADMIN_EMAIL', 'admin@example.com'),
        'social_links' => null,
    ],
];
