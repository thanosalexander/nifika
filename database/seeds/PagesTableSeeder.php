<?php

use App\Logic\Locales\AppLocales;
use App\Logic\Pages\PageOrderType;
use App\Logic\Pages\PageSaver;
use App\MenuItem;
use App\Page;
use App\PageImage;
use App\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PagesTableSeeder extends Seeder {

    protected $_demoArticles = 0;
    protected $_demoDescription = false;
    protected $_demoContent = false;
    protected $_inMenu = false;
    protected $_delayBetweenInsert = 1;

    public function run() {
        /*
         * !IMPORTANT: IF EXISTS FOLDER 'photos'
         * copy it into <siteRootPage>/pubic/storage/
         */

        if (!(env('APP_ENV') == 'local')) {
            return false;
        }
        $demoData = [
            'Home' => [
                'en' => [
                    'title' => 'HOME',
                    'description' => 'Anna Veneti',
                    'content' => '',
                ],
                'el' => [
                    'title' => 'ΑΡΧΙΚΉ',
                    'description' => 'Αννα Βενέτη',
                    'content' => '',
                ],
                '_inMenu' => false,
                'data' => [
                    'id' => Page::PAGE_HOME_ID,
                    'type' => Page::TYPE_HOME,
                    'slug' => Str::slug('Anna Veneti'),
                    'image' => 'home/01.jpg',
                ],
                'relations' => [
                    'images' => [
                        ['filename' => 'home/02.jpg'],
                        ['filename' => 'home/01 (1).jpg'],
                    ]
                ],
                'children' => [],
            ],


            'About' => [
                'en' => [
                    'title' => 'ABOUT',
                    'description' => '',
                    'content' => '',
                ],
                'el' => [
                    'title' => 'ΣΧΕΤΙΚΆ',
                    'description' => '',
                    'content' => '',
                ],
                '_inMenu' => true,
                'data' => [
                    'type' => Page::TYPE_PAGE,
                    'customView' => 'eveningCouture',
                    'slug' => Str::slug( 'About'),
                ],

            ],

            'Bridal Couture' => [
                'en' => [
                    'title' => 'BRIDAL COUTURE',
                    'description' => '',
                    'content' => '',
                ],
                'el' => [
                    'title' => 'ΝΥΦΙΚΉ ​​ΡΑΠΤΙΚΉ',
                    'description' => '',
                    'content' => '',
                ],
                '_inMenu' => true,
                'data' => [
                    'type' => Page::TYPE_PAGE,
                    'customView' => 'bridalCouture',
                    'slug' => Str::slug( 'Bridal Couture'),
                ],
                'relations' => [
                    'images' => [
                        ['filename' => 'bridal/01.jpg'],
                        ['filename' => 'bridal/01 (1).jpg'],
                        ['filename' => 'bridal/02.jpg'],
                    ]
                ],
                'children' => [
                    'dbs' => [
                        'en' => [
                            'title' => e('DURING A BACKLESS STICH'),
                            'description' => '',
                            'content' => '',
                        ],
                        'el' => [
                            'title' => e('DURING A BACKLESS STICH'),
                            'description' => '',
                            'content' => '',
                        ],
                        'data' => [
                            'type' => Page::TYPE_PAGE,
                            'customView' => 'bridalCoutureSingle',
                            'slug' => Str::slug('During a Backless Stich'),
                            'image' => 'bridal/01.jpg',
                        ],
                        'relations' => [
                            'images' => [
                                ['filename' => 'bridal/dbs/thumbnails/01cropped (1).jpg'],
                                ['filename' => 'bridal/dbs/thumbnails/02.jpg'],
                                ['filename' => 'bridal/dbs/thumbnails/03 (1).jpg'],

                                ['filename' => 'bridal/dbs/01cropped.jpg'],
                                ['filename' => 'bridal/dbs/02.jpg'],
                                ['filename' => 'bridal/dbs/03.jpg'],
                            ]
                        ],
                        'children' => [],
                    ],
                    'hbs' => [
                        'en' => [
                            'title' => e('HANDMADE BRIDAL STORIES'),
                            'description' => '',
                            'content' => '',
                        ],
                        'el' => [
                            'title' => e('HANDMADE BRIDAL STORIES'),
                            'description' => '',
                            'content' => '',
                        ],
                        'data' => [
                            'type' => Page::TYPE_PAGE,
                            'customView' => 'bridalCoutureSingle',
                            'slug' => Str::slug('Handmade Bridal Stories'),
                            'image' => 'bridal/01 (1).jpg',
                        ],
                        'children' => [],
                    ],
                    'lir' => [
                        'en' => [
                            'title' => e('LOST IN THE RUFFLES'),
                            'description' => '',
                            'content' => '',
                        ],
                        'el' => [
                            'title' => e('LOST IN THE RUFFLES'),
                            'description' => '',
                            'content' => '',
                        ],
                        'data' => [
                            'type' => Page::TYPE_PAGE,
                            'customView' => 'bridalCoutureSingle',
                            'slug' => Str::slug('Lost in the Ruffles'),
                            'image' => 'bridal/01.jpg',
                        ],
                        'children' => [],
                    ],
                ],
            ],


            'Evening Couture' => [
                'en' => [
                    'title' => 'EVENING COUTURE',
                    'description' => '',
                    'content' => '',
                ],
                'el' => [
                    'title' => 'ΑΠΟΓΕΥΜΑΤΙΝΉ ΈΞΟΔΟΣ',
                    'description' => '',
                    'content' => '',
                ],
                '_inMenu' => true,
                'data' => [
                    'type' => Page::TYPE_PAGE,
                    'customView' => 'eveningCouture',
                    'slug' => Str::slug( 'Evening Couture'),
                ],

            ],

            'Brides Stories' => [
                'en' => [
                    'title' => 'BRIDES STORIES',
                    'description' => '',
                    'content' => '',
                ],
                'el' => [
                    'title' => 'ΙΣΤΟΡΊΕΣ ΓΙΑ ΝΎΦΕΣ',
                    'description' => '',
                    'content' => '',
                ],
                '_inMenu' => true,
                'data' => [
                    'type' => Page::TYPE_PAGE,
                    'customView' => 'bridesStories',
                    'slug' => Str::slug( 'Brides Stories'),
                ],
            ],

            'Videos' => [
                'en' => [
                    'title' => 'VIDEOS',
                    'description' => '',
                    'content' => '',
                ],
                'el' => [
                    'title' => 'ΒΊΝΤΕΟ',
                    'description' => '',
                    'content' => '',
                ],
                '_inMenu' => true,
                'data' => [
                    'type' => Page::TYPE_PAGE,
                    'customView' => 'videos',
                    'slug' => Str::slug( 'Videos'),
                ],
            ],

            'News' => [
                'en' => [
                    'title' => 'NEWS',
                    'description' => '',
                    'content' => '',
                ],
                'el' => [
                    'title' => 'ΝΈΑ',
                    'description' => '',
                    'content' => '',
                ],
                '_inMenu' => true,
                'data' => [
                    'type' => Page::TYPE_PAGE,
                    'customView' => 'news',
                    'slug' => Str::slug( 'News'),
                ],
            ],

            'Shop' => [
                'en' => [
                    'title' => 'SHOP',
                    'description' => '',
                    'content' => '',
                ],
                'el' => [
                    'title' => 'SHOP',
                    'description' => '',
                    'content' => '',
                ],
                '_inMenu' => true,
                'data' => [
                    'type' => Page::TYPE_EXTERNAL,
                    'slug' => Str::slug('Shop'),
                ],
            ],

            'Contact' => [
                'en' => [
                    'title' => 'CONTACT',
                    'description' => '',
                    'content' => '',
                ],
                'el' => [
                    'title' => 'CONTACT',
                    'description' => '',
                    'content' => '',
                ],
                '_inMenu' => true,
                'data' => [
                    'type' => Page::TYPE_CONTACT,
                    'customView' => 'contact',
                    'slug' => Str::slug( 'Contact'),
                ],
            ],


        ];

        $this->deleteAll();
        $this->createPages($demoData);
        $this->copyFileManagerFiles();
        $this->copyPageImageFiles();
    }

    /** Delete all Pages
     * @return void */
    public function deleteAll() {
        try {
            if ((env('APP_ENV') == 'local')) {
                Page::all()->each(function($model) {
                    $model->delete();
                });
            } else {
                Page::whereNotIn('type', Page::TYPE_ARTICLE)->get()->each(function($model) {
                    $model->delete();
                });
            }
        } catch (Exception $e) {
            echo 'SOMETHING WENT WRONG ON PAGES DELETION';
        }
        if ((env('APP_ENV') == 'local')) {
            DB::table((new Page())->getTable())->truncate();
            DB::table((new PageImage())->getTable())->truncate();
            DB::table((new Translation())->getTable())->truncate();
        }
    }

    public function createPages($data, $parentPage = null, $parentMenuItem = null) {
        $menuIndex = 0;
        $pageIndex = 0;
        foreach ($data as $key => $pageData) {

            if (!array_key_exists('relations', $pageData) || !is_array($pageData['relations'])) {
                $pageData['relations'] = [];
            }
            if ($pageData['data']['type'] == Page::TYPE_PAGE_LIST) {
                if (!array_key_exists('_demoArticles', $pageData)) {
                    $pageData['_demoArticles'] = $this->_demoArticles;
                }
            }
            //create page model
            //add parent_id to child data
            $pageData['data']['parent_id'] = (is_null($parentPage) ? null : $parentPage->id);
            $pageData['data']['sort'] = $pageIndex++;
            /* @var $page Page */
            $page = $this->createPageModel($pageData);

            //create page images
            if (array_key_exists('images', $pageData['relations']) && !empty($pageData['relations']['images'])) {
                $this->createPageImages($page, $pageData['relations']['images']);
            }
            //create demo articles
            if (array_key_exists('_demoArticles', $pageData) && intval($pageData['_demoArticles']) > 0) {
                $this->createDemoArticles($page, intval($pageData['_demoArticles']));
            }
            //check page is in menu
            $isInMenu = array_key_exists('_inMenu', $pageData) ? $pageData['_inMenu'] : $this->_inMenu;
            //added in menu if
            // _inMenu field is true
            // and
            //     it is topLevel page (parentPage === null)
            //     or its parentPage is in menu

            $pageMenuItem = null;
            if ($isInMenu && (is_null($parentPage) || !is_null($parentMenuItem))
            ) {
                //create menu item for page
                $menuData = [
                    'sort' => $menuIndex++,
                    'content' => $page->id,
                    'parent_id' => (is_null($parentMenuItem) ? null : $parentMenuItem->id),
                ];
                $pageMenuItem = $this->createMenuItem($menuData);
            }
            //check if has children
            if (array_key_exists('children', $pageData) && !empty($pageData['children'])) {
                $this->createPages($pageData['children'], $page, $pageMenuItem);
            }
        }
    }

    /**
     *
     * @param array $data
     * @return Page
     */
    public function createPageModel($data) {
        $page = factory(Page::class, 1)->make();
        if (!$this->_demoDescription) {
            $page->description = null;
        }
        if (!$this->_demoContent) {
            $page->content = null;
        }
        $filteredData = $this->fixRichContentUrls($data);
        $filteredData = $this->createMissingLocaleData($filteredData);
        $filteredData['data']['slug'] = PageSaver::generateUniquePageSlug($filteredData['data']['slug']);
        Model::unguard();
        $page->fill($filteredData['data']);
        $page->fillLocalizedAttributes($filteredData);
        if(intval($this->_delayBetweenInsert) > 0 && intval($this->_delayBetweenInsert) < 5) {
            sleep(intval($this->_delayBetweenInsert));
        }
        $page->save();
        return $page;
    }

    /**
     *
     * @param Page $page
     * @param array $data
     * @return PageImage[]
     */
    public function createPageImages($page, $data) {
        $pageImages = [];
        $sort = 0;
        foreach ($data as $imageData) {
            $pageImage = PageImage::_get();
            $pageImage->fill([
                'sort' => $sort,
                'enabled' => PageImage::ENABLED_YES,
            ]);
            $pageImage->fill($imageData);
            $page->images()->save($pageImage);
            $pageImages[] = $pageImage;
            $sort++;
        }
        return $pageImages;
    }

    /**
     *
     * @param array $data
     * @return MenuItem
     */
    public function createMenuItem($data) {
        $menuItem = factory(MenuItem::class, 1)->make();
        $menuItem->fill($data);
        $menuItem->save();
        return $menuItem;
    }

    /**
     *
     * @param Page $parentPage
     * @return Collection
     */
    public function createDemoArticles(Page $parentPage, $count) {
        $sampleArticles = collect();
        if ((env('APP_ENV') == 'local')) {
            $defaultLocale = config('app.locale');
            if (intval($count) > 0) {
                $sampleArticles = factory(Page::class, 'Article', $count)->make();
                foreach ($sampleArticles as $article) {
                    $localizedData = [
                        $defaultLocale => ['title' => $article->title],
                    ];
                    $article->fillLocalizedAttributes($this->createMissingLocaleData($localizedData));
                    $parentPage->subPages()->save($article);
                }
            }
        }

        return $sampleArticles;
    }

    /**
     *
     * @param Page $data
     * @return Collection
     */
    public function createMissingLocaleData($data) {
        $frontendLocales = AppLocales::getFrontend();
        $frontendLocaleCodes = array_keys($frontendLocales);
        $existingLocaleData = array_filter($frontendLocales, function($code) use ($frontendLocaleCodes) {
            return in_array($code, $frontendLocaleCodes);
        }, ARRAY_FILTER_USE_KEY);
        $defaultLocale = config('app.locale');
        $defaultLocaleData = (!array_key_exists($defaultLocale, $data) ? null : $data[$defaultLocale]);
        if (empty($existingLocaleData) || empty($defaultLocaleData)) {
            return $data;
        }
        foreach ($defaultLocaleData as $fieldName => $defaultValue) {
            foreach ($frontendLocales as $code => $langData) {
                if (!array_key_exists($code, $data)) {
                    $data[$code] = [];
                }
                if (!array_key_exists($fieldName, $data[$code])) {
                    $data[$code][$fieldName] = '';
                }
                if (!empty($defaultValue) && empty($data[$code][$fieldName])) {
                    $data[$code][$fieldName] = strtoupper($code) . '-' . $defaultValue;
                }
            }
        }

        return $data;
    }

    public function fixRichContentUrls($data) {
        $frontendLocales = AppLocales::getFrontend();
        $frontendLocaleCodes = array_keys($frontendLocales);
        $existingLocaleData = array_filter($frontendLocales, function($code) use ($frontendLocaleCodes) {
            return in_array($code, $frontendLocaleCodes);
        }, ARRAY_FILTER_USE_KEY);
        $defaultLocale = config('app.locale');
        $defaultLocaleData = (!array_key_exists($defaultLocale, $data) ? null : $data[$defaultLocale]);
        if (empty($existingLocaleData) || empty($defaultLocaleData)) {
            return $data;
        }
        $localSiteUrl = env('APP_LOCAL_URL');
        $siteUrl = env('APP_URL');
        foreach ($frontendLocaleCodes as $localeCode) {
            if (array_key_exists($localeCode, $data) && array_key_exists('content', $data[$localeCode])) {
                $data[$localeCode]['content'] = str_replace($localSiteUrl, $siteUrl, $data[$localeCode]['content']);
            }
        }

        return $data;
    }

    public function copyFileManagerFiles() {
        $photosSeedPath = database_path('seeds/photos');
        $destBasePath = public_path('storage');
        $destPath = public_path('storage/photos');
        if (File::isDirectory($photosSeedPath)) {
            if (!File::isDirectory($destBasePath)) {
                File::makeDirectory($destBasePath, 0755);
            }
            if (!File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755);
            } else {
                File::deleteDirectory($destPath);
                File::makeDirectory($destPath, 0755);
            }
            File::copyDirectory($photosSeedPath, $destPath);
        }
    }

    public function copyPageImageFiles() {
        $photosSeedPath = database_path('seeds/images');
        $destBasePath = public_path('storage');
        $destPath = public_path('storage/images');
        if (File::isDirectory($photosSeedPath)) {
            if (!File::isDirectory($destBasePath)) {
                File::makeDirectory($destBasePath, 0755);
            }
            if (!File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755);
            } else {
                File::deleteDirectory($destPath);
                File::makeDirectory($destPath, 0755);
            }
            File::copyDirectory($photosSeedPath, $destPath);
        }
    }

}
