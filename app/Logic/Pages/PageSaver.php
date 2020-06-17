<?php

namespace App\Logic\Pages;

use App\Http\Requests\AdminEntityRequest;
use App\Logic\App\Permission;
use App\Logic\Base\Saver;
use App\Logic\Template\Menu\Menus;
use App\MenuItem;
use App\Page;
use App\PageImage;
use App\Setting;
use Illuminate\Support\Str;
use function trans;

/** Used to save pages in database. 
 * @property AdminEntityRequest $request
 * @property Page $model
 */
class PageSaver extends Saver {

    /** Get the pageSaver. 
     * @param AdminEntityRequest $request
     * @return \static */
    public static function _get($request) {
        $saver = parent::initFromRequest($request);
        return $saver;
    }

    public static function _getForCreate($request) {
        $saver = new static();
        $saver->setUser($request->user());
        $saver->setAction(static::ACTION_STORE);
        $saver->model = static::getNewModel();
        return $saver;
    }

    public static function _getForUpdate($request, Page $model) {
        $saver = new static();
        $saver->setUser($request->user());
        $saver->setAction(static::ACTION_UPDATE);
        $saver->model = $model;
        return $saver;
    }

    public function setModelFromRequest() {
        //actions depends on requested route
        switch ($this->action) {
            case static::ACTION_STORE:
                //set model
                $this->model = static::getNewModel();
                break;
            case static::ACTION_UPDATE_ORDER:
                break;
            case static::ACTION_UPDATE:
            case static::ACTION_DELETE:
            default:
                //set model
                $this->model = Page::findOrFail($this->request->route('id'));
                break;
        }
    }

    /** Create a new Page model.
     * @return Page */
    public static function getNewModel() {
        return Page::_get();
    }

    /** Create a page.
     * @return Page */
    public function create(Page $parentPage = null) {
        return $this->createFromRequest($parentPage);
    }

    /** Create a Page.
     * @param Page $parentPage
     * @return Page */
    public function createFromRequest(Page $parentPage = null) {
        //convert sent data
        return $this->createFromData($this->getSentData(), $parentPage);
    }

    /** Create a page using given data.
     * @param array $data
     * @param Page $parentPage
     * @return Page */
    public function createFromData($data, Page $parentPage = null) {
        if ($this->action !== static::ACTION_STORE) {
            throw new \Exception("Saver is not set for creation!");
        }
        //convert data
        $data = $this->convertData($data);

        // fill model with sent data
        $this->model->fill($data); //be assigned only fillable fields
        $this->model->fillLocalizedAttributes($data);
        //save model
        if (!is_null($parentPage)) {
            $parentPage->subPages()->save($this->model);
        } else {
            $this->model->parent_id = null;
            $this->model->save();
        }
        
        if (Permission::canMangePageImages($this->user)) {
            $this->saveManyRelation('images', $data['images']);
        }
        $this->syncMainMenuItem($data);
        return true;
    }

    /** Update the page. 
     * @param Page $parentPage
     * @return Page */
    public function update(Page $parentPage = null) {
        return $this->updateFromRequest($parentPage);
    }

    /** Update a page.
     * @param Page $parentPage
     * @return Page */
    public function updateFromRequest(Page $parentPage = null) {
        //convert sent data
        return $this->updateFromData($this->getSentData(), $this->model->parentPage);
    }

    /** Update a page using given data.
     * @param array $data
     * @param Page $parentPage
     * @return Page */
    public function updateFromData($data, Page $parentPage = null) {
        if ($this->action !== static::ACTION_UPDATE) {
            throw new \Exception("Saver is not set for updation!");
        }

        //convert data
        $data = $this->convertData($data);

        // fill model with sent data
        $this->model->fill($data); //be assigned only fillable fields
        $this->model->fillLocalizedAttributes($data);
        //save model
        if (!is_null($parentPage)) {
            $this->model->parentPage()->associate($parentPage);
            $this->model->save();
        } else {
            $this->model->parentPage()->dissociate();
            $this->model->save();
        }
        if (Permission::canMangePageImages($this->user)) {
            $this->saveManyRelation('images', $data['images']);
        }
        $this->syncMainMenuItem($data);
        return $this->model;
    }
    
    /** Update the page. 
     * @param Page $parentPage
     * @return Page */
    public function updateOrder(Page $parentPage = null) {
        return $this->updateOrderFromRequest($parentPage);
    }

    /** Update a page.
     * @param Page $parentPage
     * @return Page */
    public function updateOrderFromRequest(Page $parentPage = null) {
        //convert sent data
        $result=$this->updateOrderFromData($this->getSentData(), $parentPage);
        return $result;
    }
    


    /** Update a page using given data.
     * @param array $data
     * @param Page $parentPage
     * @return Page */
    public function updateOrderFromData($data, Page $parentPage = null) {
        if ($this->action !== static::ACTION_UPDATE_ORDER) {
            throw new \Exception("Saver is not set for updation!");
        }
        //convert data
        $data = $this->convertData($data);
        
        $pagesOrder = $data['subPages'];
        $existingPages = is_null($parentPage) ? Page::topLevel($this->user)->get() : $parentPage->subPages()->get();
        foreach($existingPages as $existingPage) {
            if(array_key_exists($existingPage->id, $pagesOrder)) {
                $existingPage->sort = $pagesOrder[$existingPage->id];
                $existingPage->save();
            }
        }

        return true;
    }
    


    /** Delete the page. 
     * @return boolean */
    public function destroy() {
        if ($this->action !== static::ACTION_DELETE) {
            throw new \Exception("Saver is not set for deletion!");
        }

        $transBaseName = \View::shared('transBaseName');
        if ($this->canModelBeDeleted()) {
            if ($this->model->delete()) {
                $this->setResultMessage(trans("{$transBaseName}.deleteEntity.success"));
                return true;
            } else {
                $this->setResultMessage(trans("{$transBaseName}.deleteEntity.fail"));
            }
        } else {
            $this->setResultMessage(trans("{$transBaseName}.page.message.cannotDelete"));
        }
        
        return false;
    }

    protected function syncMainMenuItem($data) {
        if (Permission::canMangeMenu($this->user) && $data['displayOnMainMenu'] && $this->model->canBeAssignedOnMenu()) {
            $mainMenuItem = $this->model->mainMenuItem;
            if (is_null($mainMenuItem)) {
                $parentMenuItem = (!is_null($this->model->parentPage) && !is_null($this->model->parentPage->mainMenuItem) ? $this->model->parentPage->mainMenuItem : null
                        );
                $lastMenuItemSort = (!is_null($parentMenuItem) ? $parentMenuItem->subItems()->max('sort') : MenuItem::menuItemsByMenu(Menus::MENU_ID_MAIN)->max('sort')
                        );

                $mainMenuItem = MenuItem::_get();
                $mainMenuItem->fill([
                    'menu_id' => Menus::MENU_ID_MAIN,
                    'parent_id' => (is_null($parentMenuItem) ? null : $parentMenuItem->id),
                    'sort' => (intval($lastMenuItemSort) + 1),
                    'type' => MenuItem::TYPE_PAGE,
                    'content' => $this->model->id,
                ]);
                $mainMenuItem->save();
                $this->model->load('mainMenuItem');
            }
        } else if (!is_null($this->model->mainMenuItem)) {
            $this->model->mainMenuItem()->delete();
        }
    }

    /** Extract sent data 
     * @return array 
     */
    protected function getSentData() {
        if (is_null($this->request)) {
            throw new \Exception("Request is not set!");
        }
        $data = $this->request->all();
        return $data;
    }

    /** Convert data 
     * @param array $data Description 
     * @return array 
     */
    protected function convertData($data) {
        $convertedData = $data;
        //actions depends on requested route
        switch ($this->action) {
            case static::ACTION_STORE:
            case static::ACTION_UPDATE:
                static::checkIfDeleteFileSent($this->model, $convertedData);
                if($this->user->isAdmin()) {
                    if(!array_key_exists('sortType', $convertedData) || empty($convertedData['sortType'])){
                        $convertedData['sortType'] = null;
                    }
                }
                $convertedData['enabled'] = !isset($convertedData['enabled']) ? Page::ENABLED_NO : Page::ENABLED_YES;
                $convertedData['displayOnMainMenu'] = !isset($convertedData['displayOnMainMenu']) ? false : true;

                if (Permission::canMangePageImages($this->user)) {
                    
                    $imagesData = (empty($convertedData['images']) ? [] : $convertedData['images']);
                    $convertedData['images'] = $this->convertImagesData($imagesData);
                }

                    $convertedData = $this->handleSlugFromData($convertedData);

                break;
            case static::ACTION_UPDATE_ORDER:
                $subPages = [];
                if(array_key_exists('subPages', $convertedData) && is_array($convertedData['subPages'])) {
                    $subPages = array_flip(array_keys($convertedData['subPages']));
                }
                $convertedData['subPages'] = $subPages;
                break;
            case static::ACTION_DELETE:
                break;
        }

        return $convertedData;
    }

    /** Convert data of images
     * @param array $data Description 
     * @return array 
     */
    protected function convertImagesData($data) {
        //convert images data
        $data = (empty($data) || !is_array($data)) ? [] : $data;
        $order = 0;
        foreach ($data as $key => $item) {
            $data[$key]['sort'] = $order++;
            $data[$key]['enabled'] = (!array_key_exists('enabled', $data[$key]) 
                    ? PageImage::ENABLED_NO 
                    : PageImage::ENABLED_YES);
        }
        return $data;
    }

    /** Return if the model can be deleted
     * @return boolean */
    public function canModelBeDeleted() {
        return $this->model->canBeDeleted();
    }

    /**
     * @param type $data
     * @return array
     */
    protected function handleSlugFromData($data) {
        switch ($this->action) {
            case static::ACTION_STORE:
                $data['slug'] = static::generateUniquePageSlug($data['title']);
                break;
            case static::ACTION_UPDATE:
                if (isset($data['slug'])) {
                    unset($data['slug']);
                }
                break;
        }

        return $data;
    }

    /** 
     * @return boolean */
    public static function generateUniquePageSlug($pageTitle) {
        $uniqueSlug = '';
        $pageTitleSlug = Str::slug($pageTitle);
        if (!empty($pageTitleSlug)) {
            $attempt = 0;
            do {
                $slug = "{$pageTitleSlug}".($attempt > 0 ? "-{$attempt}"  : "");
                $samePages = Page::whereSlug($slug)->get();
                $isUnique = ($samePages->count() === 0);
                if ($isUnique = ($samePages->count() === 0)) {
                    $uniqueSlug = $slug;
                }
                $attempt++;
                
            } while(!$isUnique && $attempt <= 30);
        }
        if (empty($uniqueSlug)) {
            $uniqueSlug = static::generateUniquePageSlug(
                    $pageTitleSlug . '-' . strtolower(str_random(6))
            );
        }
        return $uniqueSlug;
    }
    

}
