<?php

namespace Auth\Controller;

use Phalcon\Mvc\Controller;

/**
 * @author Ands
 * @RoutePrefix("/auth")
 */
class IndexController extends Controller {

    /**
     * @Get('/')
     */
    public function indexAction() {
        return $this->view;
    }

    /**
     * @Get('example-route')
     */
    public function exampleRouteAction() {
        $this->view->setMainView('index/example');

    }
}
