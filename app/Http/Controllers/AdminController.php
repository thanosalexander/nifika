<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminEntityRequest;
use App\Logic\App\EntityManager;
use App\Logic\Locales\AdminLocale;
use App\Logic\Locales\ModelLocale;
use App\Logic\Pages\ArticleDatatable;
use App\Logic\Pages\ArticleSaver;
use App\Logic\Pages\PageDatatable;
use App\Logic\Pages\PageSaver;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\Datatables\Engines\QueryBuilderEngine;

class AdminController extends Controller {

    /** Create a new controller instance.
     * @return void */
    public function __construct() {
    }

    /** Show the application dashboard.
     * @return Response */
    public function index() {
        $user = auth()->user();
        if($user->isAdmin()){
            return \Redirect::to(EntityManager::entityListUrl(EntityManager::PAGE));
        }else if($user->isManager()){
            return \Redirect::to(EntityManager::entityListUrl(EntityManager::PAGE));
        } else {
            return abort(Response::HTTP_UNAUTHORIZED);
        }
    }

    /** Update current data Language.
     * @return Response */
    public function updateModelLocale(Request $request) {
        if (!myApp()->hasLanguages()) {
            return response()->json(['status' => 'fail'], Response::HTTP_NOT_FOUND);
        }
        ModelLocale::get()->setCurrent($request->input('lang', currentModelLocale()));
        return response()->json(['status' => 'ok'], Response::HTTP_OK);
    }

    /** Update current admin locale.
     * @return Response */
    public function updateAdminLocale(Request $request) {
        if (!myApp()->hasLanguages()) {
            return response()->json(['status' => 'fail'], Response::HTTP_NOT_FOUND);
        }
        AdminLocale::get()->setCurrent($request->input('lang', currentAdminLocale()));
        return response()->json(['status' => 'ok'], Response::HTTP_OK);
    }

    /** Display a listing of the resource.
     * @param Request $request
     * @param string $entity
     * @param string $entityId
     * @param string $relationEntityName
     * @return Response
     */
    public function listEntity(Request $request, $entity, $entityId = null, $relationEntityName = null) {
        abort_if(!empty($entityId) && empty($relationEntityName), Response::HTTP_NOT_FOUND);
        $customContentView = null;
        switch ($entity) {
            case EntityManager::PAGE:
                if (!empty($entityId)) {
                    $model = Page::find($entityId);
                    abort_if(is_null($model), Response::HTTP_NOT_FOUND);
                    $pageTitle = 'Σελίδα: ' . $model->getMyName();
                    switch ($relationEntityName) {
                        case EntityManager::PAGE:
                            $pageData = ['pageTitle' => $pageTitle];
                            break;
                    }
                } else {
                    $pageData = ['pageTitle' => EntityManager::entityLabel($entity)];
                }
                break;
            case EntityManager::ARTICLE:
                $pageData = ['pageTitle' => EntityManager::entityLabel($entity)];
                break;
            default:
                abort(Response::HTTP_NOT_FOUND);
                break;
        }

        return $this->getHtmlResponse(view(myApp()->getConfig('adminViewBasePath') . '.listEntity')
                ->with('customContentView', $customContentView)
                ->with('pageData', $pageData)
                ->with('entityName', $entity)
                ->with('relationEntityName', $relationEntityName)
                ->with('user', auth()->user())
                ->with('parentModel', isset($model) ? $model : null));
    }

    /** Return the datatable data.
     * @param Request $request
     * @param string $entity
     * @param string $entityId if exists must be exist 'relation' variable
     * @param string $relationEntityName
     * @return Response */
    public function listDataEntity(Request $request, $entity, $entityId = null, $relationEntityName = null) {
        abort_if(!empty($entityId) && empty($relationEntityName), Response::HTTP_NOT_FOUND);
        switch ($entity) {
            case EntityManager::PAGE:
                $model = Page::find($entityId);
                if (!empty($entityId)) {
                    $parentModel = $model;
                    abort_if(is_null($parentModel), Response::HTTP_NOT_FOUND);
                    switch ($relationEntityName) {
                        case EntityManager::PAGE:
                            $dataTableBuilder = PageDatatable::_get();
                            $dataTableBuilder->setParentEntity($parentModel);
                            $dataTableBuilder->setUser(auth()->user());
                            $datatable = $dataTableBuilder->build();
                            break;
                        default:
                            abort(Response::HTTP_NOT_FOUND);
                            break;
                    }
                } else {
                    $dataTableBuilder = PageDatatable::_get();
                    $dataTableBuilder->setUser(auth()->user());
                    $datatable = $dataTableBuilder->build();
                }
                break;
            case EntityManager::ARTICLE:
                $dataTableBuilder = ArticleDatatable::_get();
                $datatable = $dataTableBuilder->build();
                break;
            default:
                abort(Response::HTTP_NOT_FOUND);
                break;
        }
        //filter models with given request parameters
        /* @var $datatable QueryBuilderEngine */
        return $datatable->make(true);
    }

    /** Display a listing of the resource.
     * @return Response */
    public function createEntity(Request $request, $entity, $entityId = null, $relationEntityName = null) {
        abort_if(!empty($entityId) && empty($relationEntityName), Response::HTTP_NOT_FOUND);
        
        $customContentView = null;
        switch ($entity) {
            case EntityManager::PAGE:
                if (!empty($entityId)) {
                    $parentModel = Page::find($entityId);
                    abort_if(is_null($parentModel), Response::HTTP_NOT_FOUND);
                    $pageTitle = trans(myApp()->getConfig('adminTransBaseName') . '.pageTitle.page') . ': ' . $parentModel->getMyName();
                    switch ($relationEntityName) {
                        case EntityManager::PAGE:
                            $pageData['pageTitle'] = trans(myApp()->getConfig('adminTransBaseName') . '.pageTitle.addPage');
                            break;
                        default:
                            abort(Response::HTTP_NOT_FOUND);
                            break;
                    }
                } else {
                    $pageData = ['pageTitle' => trans(myApp()->getConfig('adminTransBaseName') . '.pageTitle.addPage')];
                    if(auth()->user()->isManager()) {
                        $customContentView = myApp()->getConfig('adminViewBasePath') . ".{$entity}.formProject";
                    }
                }
                break;
            case EntityManager::ARTICLE:
                $pageData = ['pageTitle' => trans(myApp()->getConfig('adminTransBaseName') . '.pageTitle.addArticle')];
                break;
            default:
                abort(Response::HTTP_NOT_FOUND);
                break;
        }

        return $this->getHtmlResponse(view(myApp()->getConfig('adminViewBasePath') . '.editEntity')
                ->with('model', null)
                ->with('pageData', $pageData)
                ->with('customContentView', $customContentView)
                ->with('entityName', $entity)
                ->with('relationEntityName', $relationEntityName)
                ->with('user', auth()->user())
                ->with('parentModel', isset($parentModel) ? $parentModel : null));
    }

    /** Display a listing of the resource.
     * @return Response */
    public function storeEntity(AdminEntityRequest $request, $entity, $entityId = null, $relationEntityName = null) {
        $response = [
            'success' => ['status' => static::STATUS_OK, 'message' => trans(myApp()->getConfig('adminTransBaseName') . '.form.message.createSuccess')],
            'fail' => ['status' => static::STATUS_FAIL, 'message' => trans(myApp()->getConfig('adminTransBaseName') . '.form.message.saveFail')],
        ];
        $result = 'fail';
        try {
            \DB::beginTransaction();
            switch ($entity) {
                case EntityManager::PAGE:
                    if (!empty($entityId)) {
                        $parentModel = Page::find($entityId);
                        abort_if(is_null($parentModel), Response::HTTP_NOT_FOUND);
                        switch ($relationEntityName) {
                            case EntityManager::PAGE:
                                $saver = PageSaver::_get($request);
                                if ($saver->create($parentModel)) {
                                    $result = 'success';
                                }
                                break;
                            default:
                                abort(Response::HTTP_NOT_FOUND);
                                break;
                        }
                    } else {
                        $saver = PageSaver::_get($request);
                        $parentModel = Page::find(Page::userTopLevelPage(auth()->user()));
                        if ($saver->create($parentModel)) {
                            $result = 'success';
                        }
                    }
                    break;
                case EntityManager::ARTICLE:
                    $saver = ArticleSaver::_get($request);
                    $parentModel = Page::find(Page::PAGE_NEWS_ID);
                    if ($saver->create($parentModel)) {
                        $result = 'success';
                    }
                    break;
                default:
                    abort(Response::HTTP_NOT_FOUND);
                    break;
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }

        return \Redirect::back()
                ->with('message', $response[$result]['message'])
                ->with('status', $response[$result]['status']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function editEntity(Request $request, $entity, $id = null) {
        $customContentView = null;
        switch ($entity) {
            case EntityManager::PAGE:
                $pageData = ['pageTitle' => trans(myApp()->getConfig('adminTransBaseName') . '.pageTitle.editPage')];
                $model = Page::find($id);
                if(auth()->user()->isManager()) {
                    $customContentView = myApp()->getConfig('adminViewBasePath') . ".{$entity}.formProject";
                }
                abort_if(is_null($model), Response::HTTP_NOT_FOUND);
                break;
            case EntityManager::ARTICLE:
                $pageData = ['pageTitle' => trans(myApp()->getConfig('adminTransBaseName') . '.pageTitle.editArticle')];
                $model = Page::findArticle($id);
                abort_if(is_null($model), Response::HTTP_NOT_FOUND);
                break;
            default:
                abort(Response::HTTP_NOT_FOUND);
                break;
        }
        return view(myApp()->getConfig('adminViewBasePath') . '.editEntity')
                ->with('model', $model)
                ->with('pageData', $pageData)
                ->with('customContentView', $customContentView)
                ->with('user', auth()->user())
                ->with('entityName', $entity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  AdminEntityRequest $request
     * @return Response
     */
    public function updateEntity(AdminEntityRequest $request, $entity, $id) {
//        dd($request->all());
        $response = [
            'success' => ['status' => static::STATUS_OK, 'message' => trans(myApp()->getConfig('adminTransBaseName') . '.form.message.updateSuccess')],
            'fail' => ['status' => static::STATUS_FAIL, 'message' => trans(myApp()->getConfig('adminTransBaseName') . '.form.message.saveFail')],
        ];
        $result = 'fail';
        try {
            \DB::beginTransaction();
            switch ($entity) {
                case EntityManager::PAGE:
                    $saver = PageSaver::_get($request);
                    if ($saver->update()) {
                        $result = 'success';
                    }
                    break;
                   case EntityManager::ARTICLE:
                    $saver = ArticleSaver::_get($request);
                    if ($saver->update()) {
                        $result = 'success';
                    }
                    break;
                default:
                    abort(Response::HTTP_NOT_FOUND);
                    break;
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }

        return \Redirect::back()
                ->with('message', $response[$result]['message'])
                ->with('status', $response[$result]['status']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function editEntityOrder(Request $request, $entity, $entityId = null, $relationEntityName = null) {
        $customContentView = null;
        switch ($entity) {
            case EntityManager::PAGE:
                $pageData = ['pageTitle' => trans(myApp()->getConfig('adminTransBaseName') . '.pageTitle.editPageOrder')];
                $model = Page::find($entityId);
                break;
            default:
                abort(Response::HTTP_NOT_FOUND);
                break;
        }
        return view(myApp()->getConfig('adminViewBasePath') . '.editEntityOrder')
                ->with('model', $model)
                ->with('pageData', $pageData)
                ->with('relationEntityName', $relationEntityName)
                ->with('customContentView', $customContentView)
                ->with('user', auth()->user())
                ->with('entityName', $entity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $entityId
     * @param  AdminEntityRequest $request
     * @return Response
     */
    public function updateEntityOrder(AdminEntityRequest $request, $entity, $entityId = null, $relationEntityName = null) {
//        dd($request->all());
        $response = [
            'success' => ['status' => static::STATUS_OK, 'message' => trans(myApp()->getConfig('adminTransBaseName') . '.form.message.updateSuccess')],
            'fail' => ['status' => static::STATUS_FAIL, 'message' => trans(myApp()->getConfig('adminTransBaseName') . '.form.message.saveFail')],
        ];
        $result = 'fail';
        try {
            \DB::beginTransaction();
            switch ($entity) {
                case EntityManager::PAGE:
                    $parentModel = null;
                    if (!empty($entityId)) {
                        $parentModel = Page::find($entityId);
                        abort_if(is_null($parentModel), Response::HTTP_NOT_FOUND);
//                        switch ($relationEntityName) {
//                            case EntityManager::PAGE:
//                                break;
//                            default:
//                                abort(Response::HTTP_NOT_FOUND);
//                                break;
//                        }
                    }
                    $saver = PageSaver::_get($request);
                    if ($saver->updateOrder($parentModel)) {
                        $result = 'success';
                    }
                    break;
                default:
                    abort(Response::HTTP_NOT_FOUND);
                    break;
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }

        return \Redirect::back()
                ->with('message', $response[$result]['message'])
                ->with('status', $response[$result]['status']);
    }

    /** Update status attribute of given entity.
     * @param Request $request
     * @param string $entity
     * @param int  $id
     * @return Response */
    public function switchStatusEntity(Request $request, $entity, $id) {
        switch ($entity) {
            case EntityManager::PAGE:
                $model = Page::find($id);
//                dd($model);
                break;
            case EntityManager::ARTICLE:
                $model = Page::findArticle($id);
                break;
            default:
                $model = null;
                break;
        }
        //not existing model
        abort_if(is_null($model), Response::HTTP_FORBIDDEN, Response::$statusTexts[Response::HTTP_FORBIDDEN]);

        switch ($entity) {
            case EntityManager::PAGE:
            case EntityManager::ARTICLE:
                // abort request if user has not permission to manage this order
                //abort_if(!auth()->user()->canManageArticle($model), Response::HTTP_UNPROCESSABLE_ENTITY);
                $model->enabled = ($model->enabled == Page::ENABLED_YES ? Page::ENABLED_NO : Page::ENABLED_YES);
                break;
            default:
                break;
        }
        $saved = false;
        try {
            \DB::beginTransaction();
            $saved = $model->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
        $code = $saved ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;
        return response()->json([], $code);
    }

    /** Delete the specified entity.
     * @param  AdminEntityRequest $request
     * @param string $entity
     * @param  int  $id
     * @return Response */
    public function destroyEntity(AdminEntityRequest $request, $entity, $id) {
        abort_if(!$request->isXmlHttpRequest(), Response::HTTP_NOT_FOUND);
        $status = static::STATUS_FAIL;
        $statusMessages = [
            static::STATUS_OK => trans(myApp()->getConfig('adminTransBaseName') . '.deleteEntity.success'),
            static::STATUS_FAIL => trans(myApp()->getConfig('adminTransBaseName') . '.deleteEntity.fail')
        ];
        $responseData = [];
        try {
            \DB::beginTransaction();
            switch ($entity) {
                case EntityManager::PAGE:
                case EntityManager::ARTICLE:
                    $saver = PageSaver::_get($request);
                    abort_if(is_null($saver->getModel()), Response::HTTP_NOT_FOUND);
                    $onSuccessGoToUrl = '';
                    $res = $saver->destroy();
                    $saverResultMessage = $saver->getResultMessage();
                    $status = $res ? static::STATUS_OK : static::STATUS_FAIL;
                    $statusMessages[$status] = empty($saverResultMessage) ? $statusMessages[$status] : $saverResultMessage;

                    if ($res) {
                        $responseData = ['goToUrl' => $onSuccessGoToUrl];
                        $this->putResponseNotification($status, $statusMessages[$status]);
                    }
                    break;
                default:
                    abort(Response::HTTP_NOT_FOUND);
                    break;
            }
            \DB::commit();
        } catch (\Exception $e) {
            $status = static::STATUS_FAIL;
            \DB::rollback();
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, $e->getMessage());
        }
        return $this->getJsonResponse($status, $statusMessages, $responseData)
                ->header('charset', 'utf-8');
    }

}
