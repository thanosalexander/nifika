<?php

use App\Logic\App\EntityManager;

foreach ($tableData['rows']['header'] as $column) {
    $temp['data'] = $column['name'];
    $temp['title'] = $column['label'];
    $temp['name'] = $column['name'];
    $temp['searchable'] = isset($column['filter']);
    $temp['visible'] = isset($column['visible']) ? $column['visible'] : true;
    $temp['orderable'] = isset($column['orderable']) ? $column['orderable'] : false;
    $temp['className'] = isset($column['className']) ? $column['className'] : '';
    $temp['width'] = isset($column['width']) ? $column['width'] : '';
    $tableData['columnsData'][] = $temp;
}
if($tableData['showOperationsColumn']){
    $operationsColumnWidth = isset($tableData['operationsColumnWidth']) ? $tableData['operationsColumnWidth'] : '10%';
    $tableData['columnsData'][] = ['data' => 'operations', 'width' => $operationsColumnWidth, 'className' => 'operationsCols text-right', 'title' => trans("{$transBaseName}.listEntity.operations"), 'name' => 'operations', 'searchable' => false, 'orderable' => false];
}

$showExportButtons = (array_key_exists('showExportButtons', $tableData) && $tableData['showExportButtons']);
$datatableExportButtonsDomRow = '';
if($showExportButtons){
    $datatableExportButtonsDomRow = '<"row"<"col-md-12 text-right marginBottom15"B>>';
}

$tableLengthOptions = [10=>10,25=>25,50=>50,100=>100];
if(array_key_exists('tableLengthOptions', $tableData) && !empty($tableData['tableLengthOptions'])){
    $tableLengthOptions = $tableData['tableLengthOptions'];
}

if(!empty($tableData['order'])){
    $columnNames = array_map(function($column){ return $column['name']; }, $tableData['columnsData']);
    
    foreach($tableData['order'] as $key => $order){
        if(in_array($order[0], $columnNames)){
            $columnsIndexes = array_flip($columnNames);
            $tableData['order'][$key][0] =  $columnsIndexes[$order[0]];
        }else{
            unset($tableData['order'][$key]);
        }
    }
    $tableData['order'] = array_values($tableData['order']);
}else{
   $tableData['order'] = [[0, 'asc']]; 
}
// with option add hiiden class on table and need to remove hiiden class on init.dt event
$tableData['showTableAfterDataLoaded'] = isset($tableData['showTableAfterDataLoaded']) && !empty($tableData['showTableAfterDataLoaded']) ? $tableData['showTableAfterDataLoaded']: false;
$customDataTableScripts = isset($customDataTableScripts) ? $customDataTableScripts : "{$viewBasePath}.partials.list.table.datatableScripts";


$addActionLabel = (array_key_exists('actionButtons', $tableData) && (array_key_exists('add', $tableData['actionButtons'])) ? $tableData['actionButtons']['add'] : trans($transBaseName.'.listEntity.action.add'));
$editActionLabel = (array_key_exists('actionButtons', $tableData) && (array_key_exists('edit', $tableData['actionButtons'])) ? $tableData['actionButtons']['edit'] : trans($transBaseName.'.listEntity.action.edit'));
$editOrderActionLabel = (array_key_exists('actionButtons', $tableData) && (array_key_exists('editOrder', $tableData['actionButtons'])) ? $tableData['actionButtons']['editOrder'] : trans($transBaseName.'.listEntity.action.editOrder'));

$showAddAction = (array_key_exists('permissions', $tableData) && array_key_exists('add', $tableData['permissions']) ? $tableData['permissions']['add'] : false);
$showEditAction = (array_key_exists('permissions', $tableData) && array_key_exists('edit', $tableData['permissions']) ? $tableData['permissions']['edit'] : false);
$showEditOrderAction = (array_key_exists('permissions', $tableData) && array_key_exists('editOrder', $tableData['permissions']) ? $tableData['permissions']['editOrder'] : false);

$addActionUrl = (!$showAddAction ? null : EntityManager::entityAddUrl($entityName, $parentModel, $relationEntityName));
$editActionUrl = (!$showEditAction ? null : EntityManager::entityEditUrl($entityName, $parentModel));
$editOrderActionUrl = (!$showEditOrderAction ? null : EntityManager::entityEditOrderUrl($entityName, $parentModel, $relationEntityName));

?>

@if(View::exists($customDataTableScripts))
<?= view($customDataTableScripts, [
    'tableData' => $tableData,
    'entityName' => $entityName,
    'relationEntityName' => $relationEntityName,
    'parentModel' => $parentModel
]); ?>
@endif

@section('content')
@parent
<div id="<?= "{$entityName}DatatableWrapper" ?>">
    <div id="headerRow" class="row">
        <div class="col-sm-12">
            <h3 id="headerPageTitle" class=""><?=e($tableData['pageTitle'])?></h3>
            <?php if ($showEditAction && !is_null($editActionUrl)): ?>
                <a class="btn btn-primary" href="<?= $editActionUrl ?>">
                    <span class="glyphicon glyphicon-edit"></span> <?= $editActionLabel ?></a>
            <?php endif; ?>
            <?php if ($showEditOrderAction && !is_null($editOrderActionUrl)): ?>
                <a class="btn btn-primary" href="<?= $editOrderActionUrl ?>">
                    <span class="glyphicon glyphicon-sort-by-alphabet"></span> <?= $editOrderActionLabel ?></a>
            <?php endif; ?>
            <?php if ($showAddAction && !is_null($addActionUrl)): ?>
                <a class="btn btn-primary" href="<?= $addActionUrl ?>">
                    <span class="glyphicon glyphicon-plus"></span> <?= $addActionLabel ?></a>
            <?php endif; ?>
        </div>
    </div>
    <div class="filterRow row">
        <?php if($tableData['showFilters']): 
            $filtersView = "{$viewBasePath}." . (!empty($relationEntityName) ? $relationEntityName : $entityName) . ".list.filters";
        ?>
        @if(View::exists($filtersView))
            <?= view($filtersView) ?>
        @endif
        <?php endif; ?>

    </div>
    <table id="datatables" class="table display responsive no-wrap table-striped table-no-bordered table-hover <?= $tableData['showTableAfterDataLoaded'] ? 'hidden': '' ?>" cellspacing="0" width="100%" style="width:100%"></table>
    <!--<table class="table table-striped table-hover <?= $tableData['showTableAfterDataLoaded'] ? ' hidden': '' ?>" width="100%"></table>-->
</div>
@endsection
