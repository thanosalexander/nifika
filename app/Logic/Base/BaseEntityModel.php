<?php

use App\Logic\App\EntityManager;
use App\Logic\Base\BaseModel;

namespace App\Logic\Base;

abstract class BaseEntityModel extends BaseModel
{
    
    public static function entityName() {
        return EntityManager::resolveModelToEntity(static::class);
    }
}
