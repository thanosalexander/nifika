<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        if(env('APP_ENV') == 'local'){
            Setting::setter(Setting::SS_SEO_SITENAME, 'Anna Veneti');
//            Setting::setter(Setting::SS_ARTICLE_CATEGORY_LIMIT, '');
//            Setting::setter(Setting::SS_FOOTER_BY_LINK, '');
//            Setting::setter(Setting::SS_ANALYTICS_ID, '');
            Setting::setter(Setting::SS_CONTACT_PAGE_ENABLED, 1);
            Setting::setter(Setting::SS_CONTACT_PAGE_ADDRESS, 'Ydras 5A, Geri, 2202, Nicosia');
            Setting::setter(Setting::SS_CONTACT_PAGE_PHONE, '+357 22 102 124');
            Setting::setter(Setting::SS_CONTACT_PAGE_FAX, '+357 22 250 746');
            Setting::setter(Setting::SS_CONTACT_PAGE_RECEIPT_EMAIL, config('mail.from.address'));
            Setting::setter(Setting::SS_CONTACT_PAGE_SHOW_RECEIPT_EMAIL, 1);
            Setting::setter(Setting::SS_GOOGLE_MAP_API_KEY, 'AIzaSyArlc42PVk6c9cFs9dlkieDdbIz0AzsPEw');
            Setting::setter(Setting::SS_GOOGLE_MAP_LAT, '38.009804');
            Setting::setter(Setting::SS_GOOGLE_MAP_LNG, '23.748001');
//            Setting::setter(Setting::SS_FACEBOOK_PAGE_PLUGIN_CODE, '');
//            Setting::setter(Setting::SS_FACEBOOK_GLOBAL_APP_ID, '');
//            Setting::setter(Setting::SS_FACEBOOK_PAGE_URL, '');
//            Setting::setter(Setting::SS_GOOGLE_PLUS_PAGE_URL, '');
//            Setting::setter(Setting::SS_TWITTER_PAGE_URL, '');
            Setting::setter(Setting::SS_ADMIN_SHOW_BREADCRUMB, 1);
            Setting::setter(Setting::SS_ADMIN_PAGE_IMAGES_ENABLED, 1);
            Setting::setter(Setting::SS_ADMIN_PAGE_VIDEO_ENABLED, 0);
            Setting::setter(Setting::SS_PUBLIC_PAGE_URL_SLUG_BASED_ENABLED, 1);
            Setting::setter(Setting::SS_PUBLIC_PAGE_URL_ID_BASED_ENABLED, 0);
        }
    }
}
