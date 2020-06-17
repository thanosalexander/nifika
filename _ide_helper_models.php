<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Page
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $sort
 * @property int $type
 * @property string|null $customView
 * @property int $enabled
 * @property string|null $slug
 * @property string|null $image
 * @property string|null $fileInfo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $content
 * @property mixed $description
 * @property mixed $meta_description
 * @property mixed $meta_keywords
 * @property mixed $meta_title
 * @property mixed $the_file
 * @property mixed $title
 * @property mixed $video
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PageImage[] $images
 * @property-read \App\MenuItem $mainMenuItem
 * @property-read \App\Page|null $parentPage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Page[] $subPages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation[] $translations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page frontEndVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel like($column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel likeMyName($value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseTranslatableModel likeTColumn($columnName, $value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel orderByMyName($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseTranslatableModel orderByTColumn($columnName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page slug($slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page type($type)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereCustomView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereFileInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereUpdatedAt($value)
 */
	class Page extends \Eloquent {}
}

namespace App{
/**
 * App\MenuItem
 *
 * @property int $id
 * @property int $menu_id
 * @property int|null $parent_id
 * @property int $sort
 * @property int $type
 * @property string|null $content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Page|null $page
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MenuItem[] $subItems
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel like($column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel likeMyName($value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel orderByMyName($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereUpdatedAt($value)
 */
	class MenuItem extends \Eloquent {}
}

namespace App{
/**
 * App\Language
 *
 * @property int $id
 * @property int $type
 * @property string $code
 * @property int $sort
 * @property int $enabled
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel like($column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel likeMyName($value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel orderByMyName($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereType($value)
 */
	class Language extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property int $type
 * @property int $enabled
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Translation
 *
 * @property int $id
 * @property int $translationable_id
 * @property string $translationable_type
 * @property string $lang
 * @property int $column
 * @property string|null $value
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $translationable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel like($column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel likeMyName($value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel orderByMyName($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation whereColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation whereTranslationableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation whereTranslationableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation whereValue($value)
 */
	class Translation extends \Eloquent {}
}

namespace App{
/**
 * App\Setting
 *
 * @property int $id
 * @property int $setting
 * @property string $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereSetting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
}

namespace App{
/**
 * App\PageImage
 *
 * @property int $id
 * @property int $page_id
 * @property int $sort
 * @property int $enabled
 * @property string $filename
 * @property string|null $fileInfo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $description
 * @property mixed $the_file
 * @property-read \App\Page $page
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation[] $translations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageImage frontEndVisible()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel like($column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel likeMyName($value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseTranslatableModel likeTColumn($columnName, $value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel orderByMyName($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseTranslatableModel orderByTColumn($columnName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageImage whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageImage whereFileInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageImage whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageImage wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageImage whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageImage whereUpdatedAt($value)
 */
	class PageImage extends \Eloquent {}
}

namespace App\Logic\Base{
/**
 * App\Logic\Base\BaseFileModel
 *
 * @property mixed $the_file
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation[] $translations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel like($column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel likeMyName($value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseTranslatableModel likeTColumn($columnName, $value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel orderByMyName($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseTranslatableModel orderByTColumn($columnName, $direction = 'asc')
 */
	class BaseFileModel extends \Eloquent {}
}

namespace App\Logic\Base{
/**
 * App\Logic\Base\BaseTranslatableModel
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation[] $translations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel like($column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel likeMyName($value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseTranslatableModel likeTColumn($columnName, $value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel orderByMyName($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseTranslatableModel orderByTColumn($columnName, $direction = 'asc')
 */
	class BaseTranslatableModel extends \Eloquent {}
}

namespace App\Logic\Base{
/**
 * App\Logic\Base\BaseModel;
 *
 * @method static \Illuminate\Database\Query\Builder|BaseModel like($column, $value)
 * @method static \Illuminate\Database\Query\Builder|BaseModel joinRelation($relationName, $operator = '=', $type = 'left', $where = false)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel likeMyName($value = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logic\Base\BaseModel orderByMyName($direction = 'asc')
 */
	class BaseModel extends \Eloquent {}
}

