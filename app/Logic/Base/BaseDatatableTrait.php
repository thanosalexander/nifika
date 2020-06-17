<?php

namespace App\Logic\Base;

use Yajra\Datatables\Datatables;
use Yajra\Datatables\Engines\QueryBuilderEngine;
/**
 * Description of BaseDatatableTrait
 */
trait BaseDatatableTrait {

    public static function _get($entity = null) {
        $obj = new static();

        if(!property_exists($obj, 'entity')) throw new \Exception("property 'entity' must be exists!");

        $obj->entity = $entity !== null ? $entity : $obj->entity;
        if(empty($obj->entity)) throw new \Exception("property 'entity' should not be empty!");

        return $obj;
    }

    protected function initDataTable($query, $modelClass) {
        return Datatables::of($query)
                ->setRowId(function($model) {
                    return 'entityRow' . $model->getKey();
                })
                ->addRowAttr('data-id', function($model) {
                    return $model->getKey();
                });
    }

    /** Edit "status" column of datatable results
     * @param QueryBuilderEngine $datatable
     * @param array $labels for on,off status [on => myApp()->getConfig('adminRouteBaseName').'.listEntity.enabled', off => myApp()->getConfig('adminRouteBaseName').'.listEntity.disabled']
     * @return QueryBuilderEngine */
    protected function editStatusColumn($datatable, $column = 'status', $labels = null) {
        $viewBasePath = myApp()->getConfig('adminViewBasePath');
        $html = view($viewBasePath . '.partials.list.table.column.status', ['column' => $column, 'labels' => $labels])->render();
        return $datatable->editColumn($column, $html);
    }

    /**
     * Add "operations"(edit,delete) column to datatable results
     * @param QueryBuilderEngine $datatable
     * @param boolean $excludeDelete if is true exclude delete action
     * @return QueryBuilderEngine
     */
    protected function addDataTableOperationsColumn($datatable, $excludeDelete = false) {
        $entity = $this->entity;
        $viewBasePath = myApp()->getConfig('adminViewBasePath');
        $datatable->addColumn('operations', function($model) use ($entity, $excludeDelete, $viewBasePath) {
            return view($viewBasePath . '.partials.list.table.column.actions', [
                        'entity' => $this->entity,
                        'excludeDelete' => $excludeDelete,
                        'model' => $model
                    ])->render();
        });
//        $html = view(myApp()->getConfig('adminViewBasePath').'.partials.list.table.column.status', ['column' => $column, 'labels' => $labels])->render();
        return $datatable;
    }

    /**
     * Edit filter for "id" column to matched as integer
     * @param QueryBuilderEngine $datatable
     * @return QueryBuilderEngine
     */
    protected function filterIdColumn($datatable) {
        return $datatable->filterColumn('id', function ($query, $keyword) use($datatable) {
                    if (!empty($keyword)) {
                        $query->where($datatable->getQueryBuilder()->from . '.id', '=', $keyword);
                    }
                });
    }

    /** Setup functionality for translatable column with given name
     * @param QueryBuilderEngine $datatable
     * @param string $columnName
     * @param string $modelClass
     * @return QueryBuilderEngine */
    protected function setupMyNameColumn($datatable, $modelClass) {
        return $datatable
                ->orderColumns(['myName'], $modelClass::getMyNameColumn() . ' $1')
                ->filterColumn('myName', function ($query, $keyword) use($modelClass) {
                    $query->whereRaw($modelClass::likeMyName($keyword)->getQuery()->wheres[0]['sql']);
                })
                ->addColumn('myName', function ($model) {
                    return $model->getMyName();
                });
    }

    /** Setup functionality for translatable column with given name
     * @param QueryBuilderEngine $datatable
     * @param string $columnName
     * @param string $modelClass
     * @return QueryBuilderEngine */
    protected function setupTranslatableColumn($datatable, $columnName, $modelClass) {
        return $datatable->orderColumns([$columnName], $modelClass::tColumn($columnName) . ' $1')
                ->filterColumn($columnName, function ($query, $keyword) use($columnName, $modelClass) {
                    $query->whereRaw($modelClass::likeTColumn($columnName, $keyword)->getQuery()->wheres[0]['sql']);
                });
    }

}
