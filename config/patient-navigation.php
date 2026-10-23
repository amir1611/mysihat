<?php
return [
    'items' => [
        [
            'name' => 'Overview',
            'icon' => 'fas fa-fw fa-grid-2',
            'route' => 'patient.dashboard',
        ],
        [
            'name' => 'Chatbot',
            'icon' => 'fas fa-fw fa-comments',
            'route' => 'patient.chatbot',
        ],
        [
            'name' => 'Appointment',
            'icon' => 'fas fa-fw fa-calendar',
            'route' => 'appointmentList',
        ],
        [
            'name' => 'Health Tools',
            'icon' => 'fas fa-fw fa-heartbeat',
            'route' => 'patient.health-tools',
        ],
        [
            'name' => 'My Records',
            'icon' => 'fas fa-fw fa-notes-medical',
            'route' => 'patient.records',
        ],
        [
            'name' => 'Care Team',
            'icon' => 'fas fa-fw fa-user-doctor',
            'route' => 'patient.care-team',
        ],
        [
            'name' => 'Marketplace',
            'icon' => 'fas fa-fw fa-bag-shopping',
            'route' => 'marketplace.index',
        ],
        [
            'name' => 'Setting',
            'icon' => 'fas fa-fw fa-cog',
            'route' => 'patient.profile',
        ],
    ],
];
