<?php

use App\Logic\Template\BreadcrumbItem;
/* @var $breadcrumbItem BreadcrumbItem */
/* @var $url string */
/* @var $title string */ ?>
<li class="<?= $breadcrumbItem->isCurrent() ? 'active' : '' ?>">
<?php if ($breadcrumbItem->isLink()): ?><a href="<?= e($breadcrumbItem->url()) ?>"><?php endif; ?>
<?= e($breadcrumbItem->title()); ?>
<?php if ($breadcrumbItem->isLink()): ?></a><?php endif; ?>
</li>