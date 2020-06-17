<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Logic\Pages\PageOrderType;
use App\Http\Requests\RecaptchaFormRequest;
use App\Logic\Locales\AppLocales;
use App\Logic\ReCaptcha\ReCaptcha;
use App\Logic\SEO\SEO;
use App\Logic\Template\Breadcrumb;
use App\Logic\Template\Menu\MenuManager;
use App\Logic\Template\StartPage;
use App\Mail\ContactFormMail;
use App\Page;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublicController extends Controller {

    public function __construct() {
    }

    /** Find the index page and show it. */
    public function index() {

        //run for default config
        $page = Page::frontEndVisible()->whereType(Page::TYPE_HOME)->first();
        $webPage = StartPage::get($page);

        /* @var $mainMenu MenuManager */
        $mainMenu = \View::shared('mainMenu');
        $mainMenu->checkActiveFromSourcePage($webPage);
        /* @var $breadcrumb Breadcrumb */
        $breadcrumb = \View::shared('breadcrumb');
        $breadcrumb->createPublicPageBreadcrumb($webPage);
        \View::share('webPage', $webPage);
        SEO::run($webPage->model());

        $response = view($webPage->viewName())
                ->with('webPage', $webPage);

        return $response;
    }

    public function showPage(Request $request, $pageIdentifier) {
        /* @var $page Page */
        $page = Page::frontEndVisible()->slug($pageIdentifier)->first();
        if (is_null($page) && ss(Setting::SS_PUBLIC_PAGE_URL_ID_BASED_ENABLED)) {
            $page = Page::frontEndVisible()->whereId($pageIdentifier)->first();
        }
        abort_if(is_null($page), Response::HTTP_NOT_FOUND);
        $webPage = $page->getMyWebPage();
        //run for default config
        switch ($page->type) {
            case Page::TYPE_HOME:
                return redirect()->to($webPage->url());
            case Page::TYPE_PAGE:
            case Page::TYPE_ARTICLE:
            case Page::TYPE_CONTACT:
                $response = view($webPage->viewName());
                break;
            case Page::TYPE_PAGE_LIST:
                $subPagesQuery = $page->subPages()
                        ->frontEndVisible()
                        ->subPagesSort(
                        PageOrderType::column($page->sortType),
                        PageOrderType::direction($page->sortType)
                );
                $subPages = $subPagesQuery->myPaginate($page);
                $subPages->appends($request->except(['page']));
                $response = view($webPage->viewName())->with('subPages', $subPages);
                break;
            default:
                abort(Response::HTTP_NOT_FOUND);
        }

        SEO::run($page);
        /* @var $mainMenu MenuManager */
        $mainMenu = \View::shared('mainMenu');
        $mainMenu->checkActiveFromSourcePage($webPage);
        /* @var $breadcrumb Breadcrumb */
        $breadcrumb = \View::shared('breadcrumb');
        $breadcrumb->createPublicPageBreadcrumb($webPage);
        \View::share('webPage', $webPage);

        return $response;
    }

    public function sendContactForm(ContactFormRequest $request) {

        $response = [
            'success' => ['status' => 'Your message was successfully sent!'],
            'fail' => ['status' => 'Error! Your message could not be sent.'],
        ];
        $fullname = $request->input('name', '');
        $email = $request->input('email', '');
        $subject = $request->input('subject', '');
        $message = $request->input('message', '');
        try {
            \Mail::to(ss(Setting::SS_CONTACT_PAGE_RECEIPT_EMAIL))
                    ->send(new ContactFormMail($fullname, $email, $subject, $message));
            $result = 'success';
        } catch (\Exception $e) {
            $result = 'fail';
        }

        $res = $response[$result]['status'];
        return \Redirect::back()
                ->with('status', $result)
                ->with('message', $response[$result]['status']);
    }

    public function verifyRecaptcha(RecaptchaFormRequest $request) {

        // Register You API keys at https://www.google.com/recaptcha/admin
        // And write it here
        $siteKey = env('GOOGLE_RECAPTCHA_KEY');
        $secret = env('GOOGLE_RECAPTCHA_SECRET');

        // reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
        $lang = AppLocales::getCurrentLocale();

        // If No key
        if ($siteKey === '' || $secret === '') {
            return response('CPT001');
        } elseif ($request->has('g-recaptcha-response')) {

            // If the form submission includes the "g-captcha-response" field
            // Create an instance of the service using your secret
            $recaptcha = new ReCaptcha($secret);

            // Make the call to verify the response and also pass the user's IP address
            $resp = $recaptcha->verify($request->get('g-recaptcha-response'), $request->server('REMOTE_ADDR'));
            if ($resp->isSuccess()) {
                // If the response is a success, that's it!
                return response('CPT000');
            } else {
                // Something wrong
//                return response('CPT000');
                return response('CPT002');
            }
        }
    }
}
