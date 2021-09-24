<?php

function Dusers_schema()
{
    $schema = [
        'User' => [
            'uid' => [
                'type' => 'INT',
                'not null' => false,
                'default' => NULL,
            ],
            'firstname' => [
                'type' => 'varchar',
                'lenght' => "255",
                'not null' => false,
                'default' => NULL,
            ],
            'surname' => [
                'type' => 'varchar',
                'lenght' => "255",
                'not null' => false,
                'default' => NULL,
            ],
            'primary' => 'uid',
        ],
    ];
    return $schema;
}