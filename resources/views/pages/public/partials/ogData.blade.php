<?php if(isset($seoOGData) && !empty($seoOGData)): ?>
<?php foreach($seoOGData as $property => $content): ?>
    <meta property="<?= e($property) ?>" content="<?= e($content)  ?>" />
<?php endforeach; ?>
<?php else: ?>
<meta name="og:title" content="" />
<meta name="og:description" content="" />
<meta name="og:type" content="website" />
<meta name="og:url" content="" />
<meta name="og:site_name" content="" />
<meta property="og:image" content="" />
<?php endif; ?>