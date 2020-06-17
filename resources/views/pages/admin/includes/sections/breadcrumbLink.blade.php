<?php

use App\Logic\Template\BreadcrumbItem;
/* @var $breadcrumbItem BreadcrumbItem */
/* @var $url string */
/* @var $title string */ ?>
<?php if ($breadcrumbItem->isLink()): ?><a href="<?= $breadcrumbItem->url() ?>"><?php endif; ?>
<?= e($breadcrumbItem->title()); ?>
<?php if ($breadcrumbItem->isLink()): ?></a><?php endif; ?>