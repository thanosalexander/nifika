<?php

namespace App;

use App\Logic\Locales\AppLocales;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    const GROUP_GENERAL = 'general';
    const GROUP_START_PAGE = 'startPage';
    const GROUP_CONTACT_PAGE = 'contactPage';
    const GROUP_ADVANCED = 'advanced';
    const EDITTYPE_TEXT = 'text';
    const EDITTYPE_TEXT_PRICE = 'textPrice';
    const EDITTYPE_TEXT_NUMBER = 'textNumber';
    const EDITTYPE_TEXTAREA = 'textarea';
    const EDITTYPE_EDITOR = 'editor';
    const EDITTYPE_CHECKBOX = 'checkbox';
    const EDITTYPE_SELECT = 'select';
    const EDITTYPE_SELECT_MULTIPLE = 'multiSelect';
    const EDITTYPE_COLOR = 'color';

    /** The sitename used on SEO. */
    const SS_SEO_SITENAME = 1000;
    /** limit of articles per page */
    const SS_ARTICLE_CATEGORY_LIMIT = 1010;
    /** @var string Footer link html. */
    const SS_FOOTER_BY_LINK = 1020;

    /** @var int Whether the contact page is enabled or not. */
    const SS_CONTACT_PAGE_ENABLED = 1100;
    const SS_CONTACT_PAGE_ADDRESS = 1101;
    const SS_CONTACT_PAGE_PHONE = 1102;
    const SS_CONTACT_PAGE_RECEIPT_EMAIL = 1103;
    /** @var int Should the receipt email be shown on public? */
    const SS_CONTACT_PAGE_SHOW_RECEIPT_EMAIL = 1104;
    const SS_CONTACT_PAGE_FAX = 1105;
    
    /** The analytics Tracking ID for the given client. */
    const SS_GOOGLE_ANALYTICS_ID = 1203;
    const SS_GOOGLE_MAP_API_KEY = 1204;
    const SS_GOOGLE_MAP_LAT = 1205;
    const SS_GOOGLE_MAP_LNG = 1206;
    /** Facebook page plugin code to embed. */
    const SS_FACEBOOK_PAGE_PLUGIN_CODE = 1207;
    /** Facebook global app id */
    const SS_FACEBOOK_GLOBAL_APP_ID = 1207;
    const SS_FACEBOOK_PAGE_URL = 1208;
    const SS_GOOGLE_PLUS_PAGE_URL = 1209;
    const SS_TWITTER_PAGE_URL = 1210;
    
    const SS_ADMIN_SHOW_BREADCRUMB = 2001;
    const SS_ADMIN_PAGE_IMAGES_ENABLED = 2002;
    const SS_ADMIN_PAGE_VIDEO_ENABLED = 2003;

    const SS_PUBLIC_PAGE_URL_SLUG_BASED_ENABLED = 3001;
    const SS_PUBLIC_PAGE_URL_ID_BASED_ENABLED = 3002;
    /** Whether under construction mode is enabled or not.
     * If it is enabled all routes shows the under construction page.
     * You can disable/enable mode if you pass request param showDemo with value [show|hide]. */
    const SS_UNDER_CONSTRUCTION_MODE_ENABLED = 3003;

    /** Holds settings singleton. */
    protected static $settings = null;
    protected $fillable = ['setting', 'value'];

    /** Get value of attribute 'value'.
     * If it is translatable in currentModelLocale.
     * @return string */
    public function getValueAttribute() {
        return $this->getLocalizedValue();
    }

    /** Set value of attribute 'value'.
     * If it is translatable in currentModelLocale.
     * @param string $value */
    public function setValueAttribute($value) {
        $this->setLocalizedValue($value);
    }

    /** Clear cached settings that is saved on $setting property
     * @param boolean $refresh if it is true 
     * @return Collection
     */
    public static function refreshCache() {
        static::$settings = Setting::all(['id', 'setting', 'value'])->keyBy('setting');
    }

    /** Get all settings from database group by setting
     * @param boolean $refresh if it is true 
     * @return Collection
     */
    public static function getAll($refresh = false) {
        if (is_null(static::$settings) || $refresh) {
            static::refreshCache();
        }
        return static::$settings;
    }

    /** Get a setting or the default value.
     * @param string $setting
     * @param string $default if it is not set then ask for default value using getDefaultValue() class method
     * @return string */
    public static function getter($setting, $default = '', $locale = null) {
        $res = $default !== '' ? $default : static::getDefaultValue($setting, $locale);
        $settings = static::getAll();
        if ($settings->has($setting)) {
            if (!is_null($locale)) {
                $model = static::firstOrNew(['setting' => $setting]);
                $res = $model->getLocalizedValue($locale);
            } else {
                $res = $settings->get($setting)->value;
            }
        }
        return $res;
    }

    /** Create or Update setting with given value and keep sync settings property.
     * @param string $setting
     * @param string $value */
    public static function setter($setting, $value = '', $locale = null) {
        $currentSettings = static::getAll();
        //get setting model from settings property or from db or create new
        $model = $currentSettings->has($setting) ? $currentSettings->get($setting) : static::firstOrNew(['setting' => $setting]);
        if (is_null($locale)) {
        $model->value = trim($value);
        } else {
            $model->setLocalizedValue($value, $locale);
        }
        $model->save();
        //refresh settings property
        static::getAll(true);
    }

    /** Get all default values of settings.
     * If you want to a specific setting, use getDefaultValue().
     * @param string $setting
     * @return string */
    protected static function defaults() {
        $defaults = [
            static::SS_SEO_SITENAME => config('app.name'),
            static::SS_ARTICLE_CATEGORY_LIMIT => 5,
            static::SS_FACEBOOK_GLOBAL_APP_ID => '386628938376171',
            //static::SS_FOOTER_BY_LINK => static::getDefaultTranslations('labels.test', static::getDefaultFooterByLinkTranslations()),
            static::SS_CONTACT_PAGE_ENABLED => 1,
            static::SS_CONTACT_PAGE_SHOW_RECEIPT_EMAIL => 1,
            static::SS_CONTACT_PAGE_RECEIPT_EMAIL => config('mail.from.address'),
            static::SS_ADMIN_SHOW_BREADCRUMB => 0,
            static::SS_ADMIN_PAGE_IMAGES_ENABLED => 0,
            static::SS_ADMIN_PAGE_VIDEO_ENABLED => 0,
            static::SS_PUBLIC_PAGE_URL_SLUG_BASED_ENABLED => 0,
            static::SS_PUBLIC_PAGE_URL_ID_BASED_ENABLED => 1,
            static::SS_UNDER_CONSTRUCTION_MODE_ENABLED => 0,
        ];

        return $defaults;
    }

    /** Get Default translations of Footer ByLink setting in all modelLocales.
     * @param array $params params that is replaced in translation's literal
     * @return string  a json encoded string */
    protected static function getDefaultFooterByLinkTranslations() {
        $defaults = [];
        $targetDefaultLocale = 'en';
        $targetDomain = 'websitedelivery.gr';
        foreach (array_keys(AppLocales::getFrontend(true)) as $locale) {
            $targetLocale = $locale === 'el' ? $locale : $targetDefaultLocale;
            $defaults[$locale] = '<a'
                    . ' target="_blank"'
                    . ' title="www.' . $targetDomain . '"'
                    . ' href="http://www.' . $targetDomain . '/' . $targetLocale . '">'
                    . $targetDomain
                    . '</a>';
        }
        return $defaults;
    }

    /** Get all setting ids that can be edited in a single level array.
     * @param string $group If it is set return edditable setting for thiw group.
     * @return array */
    public static function editables($group = null) {
        $editables = [
            static::GROUP_GENERAL => [
                static::SS_SEO_SITENAME,
                static::SS_GOOGLE_ANALYTICS_ID,
            ],
            static::GROUP_START_PAGE => [
            ],
            static::GROUP_CONTACT_PAGE => [
                static::SS_CONTACT_PAGE_ENABLED,
                static::SS_CONTACT_PAGE_ADDRESS,
                static::SS_CONTACT_PAGE_PHONE,
                static::SS_CONTACT_PAGE_FAX,
                static::SS_CONTACT_PAGE_RECEIPT_EMAIL,
                static::SS_CONTACT_PAGE_SHOW_RECEIPT_EMAIL,
                static::SS_GOOGLE_MAP_API_KEY,
                static::SS_GOOGLE_MAP_LAT,
                static::SS_GOOGLE_MAP_LNG,
            ],
            static::GROUP_ADVANCED => [
                static::SS_ARTICLE_CATEGORY_LIMIT,
                static::SS_FACEBOOK_PAGE_PLUGIN_CODE,
                static::SS_FACEBOOK_PAGE_URL,
                static::SS_GOOGLE_PLUS_PAGE_URL,
                static::SS_TWITTER_PAGE_URL,
                static::SS_PUBLIC_PAGE_URL_SLUG_BASED_ENABLED,
                static::SS_PUBLIC_PAGE_URL_ID_BASED_ENABLED,
                static::SS_UNDER_CONSTRUCTION_MODE_ENABLED,
            ],
        ];

        if (!empty($group)) {
            return isset($editables[$group]) ? $editables[$group] : [];
        } else {
            return collect($editables)->flatten()->toArray();
        }
    }

    /** Get All settings group by editing type
     * @return array */
    public static function getAllbyType() {
        return [
            static::EDITTYPE_TEXT => [
                static::SS_SEO_SITENAME,
                static::SS_GOOGLE_ANALYTICS_ID,
                static::SS_CONTACT_PAGE_RECEIPT_EMAIL,
                static::SS_CONTACT_PAGE_ADDRESS,
                static::SS_CONTACT_PAGE_PHONE,
                static::SS_CONTACT_PAGE_FAX,
                static::SS_FACEBOOK_PAGE_URL,
                static::SS_GOOGLE_PLUS_PAGE_URL,
                static::SS_TWITTER_PAGE_URL,
                static::SS_GOOGLE_MAP_API_KEY,
                static::SS_GOOGLE_MAP_LAT,
                static::SS_GOOGLE_MAP_LNG,
            ],
            static::EDITTYPE_TEXT_PRICE => [
            ],
            static::EDITTYPE_TEXT_NUMBER => [
                static::SS_ARTICLE_CATEGORY_LIMIT,
            ],
            static::EDITTYPE_TEXTAREA => [
                static::SS_FACEBOOK_PAGE_PLUGIN_CODE,
            ],
            static::EDITTYPE_EDITOR => [
            ],
            static::EDITTYPE_CHECKBOX => [
                static::SS_CONTACT_PAGE_ENABLED,
                static::SS_CONTACT_PAGE_SHOW_RECEIPT_EMAIL,
                static::SS_ADMIN_SHOW_BREADCRUMB,
                static::SS_ADMIN_PAGE_IMAGES_ENABLED,
                static::SS_ADMIN_PAGE_VIDEO_ENABLED,
                static::SS_PUBLIC_PAGE_URL_SLUG_BASED_ENABLED,
                static::SS_PUBLIC_PAGE_URL_ID_BASED_ENABLED,
                static::SS_UNDER_CONSTRUCTION_MODE_ENABLED,
            ],
            static::EDITTYPE_SELECT => [
            ],
            static::EDITTYPE_SELECT_MULTIPLE => [
            ],
            static::EDITTYPE_COLOR => [
            ],
        ];
    }

    /** Get setting`s type
     * @param int $id
     * @return string|null */
    public static function getSettingType($id) {
        $types = static::getAllbyType();
        foreach ($types as $type => $settings) {
            if (in_array($id, $settings)) {
                return $type;
            }
        }
        return null;
    }

    /** Get All settings that have translatable value
     * @return array */
    public static function translatables() {
        return myApp()->hasLanguages() ? [
            static::SS_CONTACT_PAGE_ADDRESS,
                ] : [];
    }

    /** Get default value of given setting.
     * If it is translatable get value in given locale or currentModelLocale if it is null.
     * @param int $setting the setting id
     * @param string $locale
     * @return string */
    protected static function getDefaultValue($setting, $locale = null) {
        return static::getDefaultLocalizedValue($setting, $locale, '');
    }

    /** Get default value of attribute 'value' in currentModelLocale
     * @param int $setting the setting id
     * @param string $locale if it is null get currentModelLocale 
     * @param string $fallbackValue return if default value does not exists
     * @return string */
    public static function getDefaultLocalizedValue($setting, $locale = null, $fallbackValue = '') {
        $defaults = static::defaults();
        if (!isset($defaults[$setting]) || !static::isTranslatable($setting)) { //it is not translatable setting
            return isset($defaults[$setting]) ? $defaults[$setting] : $fallbackValue;
        }
        $locale = !empty($locale) ? $locale : currentModelLocale();
        $settingDefaults = json_decode($defaults[$setting], true);
        return (
                (json_last_error() === JSON_ERROR_NONE  //is valid json
                && is_array($settingDefaults) //is array
                && isset($settingDefaults[$locale]))//has given locale
                        ? $settingDefaults[$locale] : $fallbackValue);
    }

    /** Get translations of given key in all modelLocales.
     * @param string $key a key from of translations arrays
     * @param string|array $fallbackValue the default value if there is not in a locale
     * @param array $params params that is replaced in translation's literal
     * @return string  a json encoded string */
    protected static function getDefaultTranslations($key, $fallbackValue = '', $params = []) {
        $translations = [];
        foreach (array_keys(AppLocales::getFrontend()) as $locale) {
            if (\Lang::hasForLocale($key, $locale)) {
                $translations[$locale] = \Lang::get($key, $params, $locale);
            } else {
                $translations[$locale] = (
                        is_array($fallbackValue) ? (isset($fallbackValue[$locale]) ? $fallbackValue[$locale] : '') : $fallbackValue);
            }
        }
        return json_encode($translations);
    }

    /** Check if given setting is translatable
     * @param int $setting
     * @return boolean */
    public static function isTranslatable($setting) {
        return in_array($setting, static::translatables());
    }

    /** Get value of attribute 'value' in currentModelLocale if it is translatable
     * @param string $locale if it is null get currentModelLocale 
     * @return string */
    public function getLocalizedValue($locale = null) {
        if (!static::isTranslatable($this->setting)) { //it is not translatable setting
            return $this->attributes['value'];
        }
        $locale = !empty($locale) ? $locale : currentModelLocale();
        $allValues = json_decode($this->attributes['value'], true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($allValues)) { // it is not valid json
            $allValues = [];
        }

        if (!isset($allValues[$locale])) { // it is not exists translation in given locale
            $value = static::getDefaultValue($this->setting, $locale);
        } else { // get translation in given locale
            $value = $allValues[$locale];
        }
        return $value;
    }

    /** Set value of attribute 'value' in currentModelLocale if it is translatable
     * @param string $value
     * @param string $locale if it is null get currentModelLocale  */
    public function setLocalizedValue($value, $locale = null) {
        if (!static::isTranslatable($this->setting)) { //it is not translatable setting
            $this->attributes['value'] = $value;
        } else { //it is translatable setting
            $locale = !empty($locale) ? $locale : currentModelLocale();
            if (!isset($this->attributes['value'])) { //attribute 'value' is not defined
                $this->attributes['value'] = '';
            }
            $allValues = json_decode($this->attributes['value'], true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($allValues)) { //it is not valid json
                $allValues = [];
            }
            $allValues[$locale] = $value;
            $this->attributes['value'] = json_encode($allValues);
        }
    }

}
