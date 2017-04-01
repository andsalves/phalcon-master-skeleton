<?php

namespace Main\Controller;

use Main\Form\GenericForm;
use Phalcon\Mvc\Controller;

/**
 * Example of implementation of a Controller with annotation routes
 *
 * @author Ands
 *
 * @RoutePrefix("/")
 */
class IndexController extends Controller {

    /**
     * @Get('/')
     */
    public function indexAction() {
        $form = new GenericForm();

        $this->view->setVar('form', $form);

        return $this->view;
    }

    /**
     * @Get('app/path/to/route.html')
     */
    public function pathAction() {
        return $this->view;
    }

    /**
     * @Get('([a-z])')
     */
    public function notFoundAction() {
        $this->response->setStatusCode(404);
        $this->response->setHeader("HTTP/1.0 404 Not Found", 404);
        $this->view->setMainView('error/notFound');

        return $this->view;
    }

    /**
     * @Get('[/]{0,1}')
     * @Get('/index[/]{0,1}')
     * @Get('/list[/]{0,1}')
     */
    public function multiRouteAction() {
        return $this->view;
    }
}
