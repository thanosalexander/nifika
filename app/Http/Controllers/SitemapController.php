<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Http\Response;

/** Description of PageController
 * @author Patroklos */
class SitemapController extends Controller{

    public function __construct(){
    }
    
    /** Print the rss list for the facebook page.
     * @return Response */
    public function showSitemapXml() {
        $pages = Page::all();
        return response()
            ->view('pages.public.sitemap', ['pages' => $pages])
            ->header('Content-Type','application/xml; charset=UTF-8');
    }
}
