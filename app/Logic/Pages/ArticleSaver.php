<?php

namespace App\Logic\Pages;

use App\Page;
use Illuminate\Http\Response;

/** Used to save pages in database. 
 * @property AdminEntityRequest $request
 * @property Page $model
 */
class ArticleSaver extends PageSaver {
    

    public function setModelFromRequest() {
        //actions depends on requested route
        switch ($this->action) {
            case static::ACTION_STORE:
                //set model
                $this->model = static::getNewModel();
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
}
