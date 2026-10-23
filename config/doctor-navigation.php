<?php

return [
    'items' => [
        [
            'name' => 'Dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            'route' => 'doctor.dashboard',
        ],
        [
            'name' => 'Appointment Time Slot',
            'icon' => 'fas fa-fw fa-clock',
            'route' => 'time-slots.index',
        ],
        [
            'name' => 'Appointment List',
            'icon' => 'fas fa-fw fa-calendar-check',
            'route' => 'doctor.appointments',
        ],
        [
            'name' => 'Prescriptions',
            'icon' => 'fas fa-fw fa-prescription-bottle-medical',
            'route' => 'doctor.prescriptions',
        ],
        [
            'name' => 'Setting',
            'icon' => 'fas fa-fw fa-cog',
            'route' => 'doctor.dashboard',
        ],
    ],
];
