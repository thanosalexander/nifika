<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TranslatableColumnsScope implements Scope
{
    /** Add columns translations of current Language to model's query 
     * so they can be used in query's clauses.
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Builder */
    public function apply(Builder $builder, Model $model){
        //get class name of model
        $modelClass = get_class($model);
        //get current language
        $lang = $modelClass::currentLanguage();
        //get translatable column names 
        $columns = $model->getTColumnMap();
        //get translations relation of model
        $relation = $model->translations();
        //set select clause
        $builder->select($model->getTable().'.*');
        
        foreach($columns as $columnName => $columnId){
            //create an alias name for translations table for this column
            $tableAlias = $modelClass::tColumnTable($columnName);
            $columnAlias = $modelClass::tColumn($columnName);
            $foreignKey = str_replace("{$relation->getModel()->getTable()}.", "{$tableAlias}_", $relation->getForeignKey());
            // join derived table
            $builder->leftJoin(\DB::raw(
                "(SELECT "
                    . "{$relation->getForeignKey()} AS `{$foreignKey}`, "
                    . "`value` AS `{$columnAlias}` FROM translations "
                . "WHERE {$relation->getMorphType()} = '{$relation->getMorphClass()}' "
                . "AND translations.lang = '{$lang}' AND translations.column = {$columnId}"
                . ") AS {$tableAlias}"), $foreignKey, '=', $relation->getQualifiedParentKeyName());
        }
        
        return $builder;
    }
}