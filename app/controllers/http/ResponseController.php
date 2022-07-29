<?php 
/**
 * ResponseController
 * 
 * @author Tim Daniëls
 * @version 0.1.0
 */

namespace app\controllers\http;

use app\controllers\Controller;

class ResponseController extends Controller {

    /**
     * Require 404 view
     * 
     * @return object ResponseController 
     */

    public function pageNotFound() {

        return $this->view('/404/404');
    }
}