<?php
return [
    'permissions' => [
        'create task',
        'edit task',
        'delete task',
        'view all tasks'
    ],
    'roles' => [
        'admin'
    ],
    'task_statuses' => [
        ['value' =>'To Do','id' => 'To Do','return' => false,'icon' => 'pi-user-edit text-warning-500','first' => true,'color' => 'warning'],
        ['value' =>'In Progress','id' => 'In Progress','return' => false,'icon' => 'pi-check-circle text-green-500','color' => 'blue'],
        ['value' =>'Completed','id' => 'Completed','return' => false,'last' => true,'color' => 'success'],
        ['value' =>'Pause','id' => 'Pause','return' => true,'icon' => 'pi-pause text-blue-500','color' => 'surface-900','next' => 'To Do']
    ]
];
