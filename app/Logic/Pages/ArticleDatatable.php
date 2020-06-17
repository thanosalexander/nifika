<?php

namespace App\Logic\Pages;

use App\Logic\App\EntityManager;
use App\Logic\Base\BaseDatatable;
use App\Page;

/** Description of ArticleDatatable */
class ArticleDatatable extends BaseDatatable {

    use BaseDatatableTrait;

    protected function initEntity() {
        $this->entity = EntityManager::ARTICLE;
    }

    public function initQuery() {
        return Page::query()->whereIn('type', [Page::TYPE_ARTICLE]);
    }

    public function build() {
        $datatable = parent::build();
        $this->setupTranslatableColumn($datatable, 'title', Page::class);
        $this->addDataTableOperationsColumn($datatable);
        $this->editStatusColumn($datatable, 'enabled');
        $this->filterIdColumn($datatable);
        return $datatable;
    }

}
