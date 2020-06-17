<?php
namespace App\Logic\Pages;

use App\Http\Controllers\AdminController;
use App\Logic\App\EntityManager;
use App\Logic\Base\BaseDatatable;
use App\Logic\Base\BaseDatatableTrait;
use App\Logic\Pages\PageType;
use App\Page;
use App\Logic\App\Permission;
use Yajra\Datatables\Engines\QueryBuilderEngine;

/** Description of PageDatatable */
class PageDatatable extends BaseDatatable {

    use BaseDatatableTrait;

    protected function initEntity() {
        $this->entity = EntityManager::PAGE;
    }

    protected function initQuery(){
        if(!is_null($this->parentEntity)){
            $query = $this->parentEntity->subPages()->getQuery();
        } else {
            $query = Page::query()->topLevel(auth()->user());
        }
        EntityManager::entityUserScope($this->entity, $query, $this->user);
        
        return $query;
    }
    
    public function build(){
        $datatable = parent::build();
        $this->setupTranslatableColumn($datatable, 'title', Page::class);
        $this->addDataTableOperationsColumn($datatable, !allow(Permission::DELETE_ENTITY, $this->entity));
        $this->editStatusColumn($datatable, 'enabled');
        $this->setupPageType($datatable);
        $this->filterIdColumn($datatable);
        return $datatable;
    }
    /**
     * Add "operations"(edit,delete) column to datatable results
     * @param QueryBuilderEngine $datatable
     * @param boolean $excludeDelete if is true exclude delete action
     * @return QueryBuilderEngine
     */
    protected function addDataTableOperationsColumn($datatable, $excludeDelete = false){
        $entity = $this->entity;
        $datatable->addColumn('operations', function($model) use ($entity, $excludeDelete){
            return view(myApp()->getConfig('adminViewBasePath').'.page.list.table.column.actions', [
                        'entity' => $this->entity,
                        'excludeDelete' => $excludeDelete,
                        'model' => $model
                    ])->render();
        });
//        $html = view(myApp()->getConfig('adminViewBasePath').'.partials.list.table.column.status', ['column' => $column, 'labels' => $labels])->render();
        return $datatable;
    }
    
    /** Setup functionality for officerName column
     * @param QueryBuilderEngine $datatable
     * @return QueryBuilderEngine */
    protected function setupParentNameColumn($datatable){
        $datatable->addColumn('parentName', function (Page $model) {return (is_null($model->parentPage) ? '' : $model->parentPage->getMyName());});
    }
    
    /** Setup functionality for translatable column with given name
     * @param QueryBuilderEngine $datatable
     * @return QueryBuilderEngine */
    protected function setupPageType($datatable){
            $tableName = Page::getTableName();
            $pageTypeOrderByColumn = PageType::pageTypeLabelOrderByColumn($tableName, 'type');
            $pageTypeLabelWhereColumn = PageType::pageTypeLabelWhereColumn($tableName, 'type');
        return $datatable
                ->orderColumns(['type'], $pageTypeOrderByColumn . ' $1')
                ->filterColumn('type', function ($query, $keyword) use ($pageTypeLabelWhereColumn){
                    $query->whereRaw($pageTypeLabelWhereColumn. " like '%{$keyword}%'");})
                ->editColumn('type', function ($model) {return PageType::pageTypeLabel($model->type);});
    }
}
