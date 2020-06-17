<?php 

use App\Setting;

if( config('app.env') == 'production' && !config('app.debug') ): ?>
<?php $analytics = ss( Setting::SS_GOOGLE_ANALYTICS_ID ); ?>
<?php if( !empty( $analytics ) ): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($analytics) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= e($analytics) ?>');
</script>
<?php endif; ?>
<?php endif; ?> 
 