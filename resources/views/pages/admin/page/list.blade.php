<?php

use App\Logic\App\Permission;
use App\Page;

$userTopLevelPage = Page::find(Page::userTopLevelPage(auth()->user()));
$tableData = [
    'pageTitle' => $pageData['pageTitle'],
    'permissions' => [
        'add' => allow(Permission::CREATE_ENTITY, (!is_null($relationEntityName) ? $relationEntityName: $entityName)),
        'edit' => (!is_null($relationEntityName)),
        'editOrder' => (
                (is_null($parentModel) && !is_null($userTopLevelPage))
                || (!is_null($parentModel) && $parentModel->canHaveSubPages(auth()->user()))
                ),
    ],
    'actionButtons' => [
        'add' => trans("{$transBaseName}.".(!is_null($relationEntityName) ? $relationEntityName: $entityName).".addPage"),
//        'edit' => trans("{$transBaseName}.".(!is_null($relationEntityName) ? $relationEntityName: $entityName).".editPage"),
    ],
    'showOperationsColumn' => true,
    'operationsColumnWidth' => '10%',
    'showFilters' => false,
    'globalSearch' => true,
    'order' => [
        [0 => 'type', 1 => 'asc'],
        [0 => 'id', 1 => 'asc']
//        ['column' => 'type', 'dir' => 'asc'],
//        ['column' => 'id', 'dir' => 'asc']
   ], 
    'paging' => true, 
    'rows' => [
        'header' => [
            ['name' => 'id', 'label' => 'ID', 'width' => '5%', 'orderable' => true, 'filter' => []],
            ['name' => 'title', 'label' => trans("{$transBaseName}.page.field.title"), 'width' => '20%', 'orderable' => true, 'filter' => []],
            ['name' => 'type', 'label' => trans("{$transBaseName}.page.field.type"), 'width' => '10%', 'orderable' => true, 'filter' => []],
            ['name' => 'created_at', 'label' => trans("{$transBaseName}.page.field.createdDate"), 'width' => '5%', 'orderable' => true],
            ['name' => 'sort', 'label' => trans("{$transBaseName}.page.field.sort"), 'width' => '5%', 'orderable' => true],
            ['name' => 'enabled', 'label' => trans("{$transBaseName}.listEntity.status"), 'width' => '5%', 'orderable' => false],
        ],
        'body' => [
            
        ]
    ]
];
?>

<?= view($viewBasePath.'.partials.list.table.datatable', [
    'tableData' => $tableData,
    'entityName' => $entityName,
    'relationEntityName' => $relationEntityName,
    'parentModel' => $parentModel
]) ?>

<?= view("{$viewBasePath}.page.list.scripts", [
    'tableData' => $tableData,
    'entityName' => $entityName,
    'relationEntityName' => $relationEntityName,
    'parentModel' => $parentModel
]) ?>