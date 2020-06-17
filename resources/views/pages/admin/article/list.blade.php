<?php  
$tableData = [
    'pageTitle' => $pageData['pageTitle'],
    'permissions' => ['add' => true],
    'showOperationsColumn' => true,
    'showFilters' => false,
    'globalSearch' => true,
    'order' => [['id', 'asc']], 
    'paging' => true, 
    'rows' => [
        'header' => [
            ['name' => 'id', 'label' => 'ID', 'width' => '10%', 'orderable' => true, 'filter' => []],
//            ['name' => 'parent', 'label' => trans("{$transBaseName}.page.field.parent"), 'width' => '30%', 'orderable' => true, 'filter' => []],
            ['name' => 'title', 'label' => trans("{$transBaseName}.page.field.title"), 'width' => '30%', 'orderable' => true, 'filter' => []],
            ['name' => 'created_at', 'label' => trans("{$transBaseName}.page.field.created_at"), 'width' => '10%', 'orderable' => true],
            ['name' => 'enabled', 'label' => trans("{$transBaseName}.form.enabled"), 'width' => '10%', 'orderable' => true],
        ],
        'body' => [
            
        ]
    ]
];
?>

@include($viewBasePath.'.partials.list.table.datatable', ['entityName' => $entityName, 'tableData' => $tableData])

@include("{$viewBasePath}.{$entityName}.list.scripts")