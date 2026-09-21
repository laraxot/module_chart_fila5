<?php

declare(strict_types=1);

return [
    'fields' => [
        'id' => ['label' => 'id'],
        'title' => ['label' => 'title'],
        'type' => ['label' => 'type'],
        'created_at' => ['label' => 'created_at'],
        'group_by' => ['label' => 'group_by'],
        'sort_by' => ['label' => 'sort_by'],
        'width' => ['label' => 'width'],
        'height' => ['label' => 'height'],
        'font_family' => ['label' => 'font_family'],
        'font_style' => ['label' => 'font_style'],
        'font_size' => ['label' => 'font_size'],
    ],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'layout' => ['label' => 'layout', 'icon' => 'layout', 'tooltip' => 'layout'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
    ],
];
