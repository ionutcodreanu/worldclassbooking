<?php

$data = [
    'suites' => [
        'default' => [
            'paths' => ["%paths.base%/../Features"],
            'contexts' => [
                [
                    'WorldClassBooker\Tests\Bootstrap\FeatureContext' => [
                        'username' => '',
                        'password' => '',
                        'schedulePage' => 'member-schedule.php',
                        'trainer' => '',
                        'classType' => 'Cycling',
                        'clubName' => 'World Class Lujerului'
                    ]
                ]
            ]
        ]
    ]
];

echo json_encode($data);
