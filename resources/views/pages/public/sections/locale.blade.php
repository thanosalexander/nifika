<?php 
use \App\Logic\Locales\AppLocales;

$locales = AppLocales::getFrontend(true);
?>
<?php if(count($locales) > 1): ?>
<?php foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties): ?>
<a class="languageLink <?= AppLocales::getCurrentLocale() == $localeCode ? 'active' : ''  ?>"
   rel="alternate"
   hreflang="<?= e($localeCode)  ?>"
   href="<?= e(AppLocales::getLocalizedURL($localeCode) )  ?>">
    <?= e($localeCode)  ?>
</a>&nbsp;
<?php endforeach; ?>
<?php endif; ?>
