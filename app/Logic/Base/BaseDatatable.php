<?php

namespace App\Logic\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Yajra\Datatables\Engines\EloquentEngine;

/**
 * Description of BaseDatatableTrait
 */
abstract class BaseDatatable {

    /** @var User */
    protected $user = null;
    public $entity = 'nonameEntityName';
    public $parentEntity = null;


    abstract public static function _get($entity = null);

    public function setUser(User $user){
        $this->user = $user;
    }
    
    public function setEntity($entity = null) {
        $this->entity = $entity !== null ? $entity : $this->entity;
        if (empty($this->entity))
            throw new \Exception("property 'entity' should not be empty!");
        return $this;
    }
    
    public function setParentEntity($parentEntity){
        $this->parentEntity = $parentEntity;
    }

    /** It has to be declared from which it is used it
     * @return EloquentEngine
     */
    public function build() {
        $this->initEntity();
        $query = $this->initQuery();
        $modelClass = get_class($query->getModel());
        $datatable = $this->initDataTable($query, $modelClass);
        return $datatable;
    }

    abstract protected function initEntity();

    /**
     * @return Builder
     */
    abstract protected function initQuery();

    /**
     * @return EloquentEngine
     */
    abstract protected function initDataTable($query, $modelClass);
}
