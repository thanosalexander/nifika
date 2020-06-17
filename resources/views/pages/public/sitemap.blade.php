<?php

use App\Page;
use Carbon\Carbon;
?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<?php foreach($pages as $page): /* @var $page Page */ ?>
<url>
  <loc><?= e($page->url()); ?></loc>
  <lastmod><?= e(Carbon::now()->toDateString()) ?></lastmod>
  <priority><?= ($page->id === 5 ? '1.00' : '0.80')  ?></priority>
</url>
<?php endforeach; ?>
</urlset>