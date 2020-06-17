<?php
use App\Setting;
?>
<!-- RD Google Map-->
<section class="google-map-container" data-key="<?= e(ss(Setting::SS_GOOGLE_MAP_API_KEY)) ?>" data-zoom="17" data-center="<?= e(json_encode(['lat' => e(ss(Setting::SS_GOOGLE_MAP_LAT)), 'lng' => e(ss(Setting::SS_GOOGLE_MAP_LNG))]))  ?>">
    <div class="google-map"></div>
    <ul class="google-map-markers">
        <li data-location="<?= e(json_encode(['lat' => e(ss(Setting::SS_GOOGLE_MAP_LAT)), 'lng' => e(ss(Setting::SS_GOOGLE_MAP_LNG))]))  ?>"></li>
    </ul>
</section>