<?php
/**
 * User: nathena
 * Date: 2017/6/19 0019
 * Time: 15:05
 */

namespace base;


class WelcomePlatformController extends LoginedPlatformController
{

    public function index()
    {
        $view = $this->getView("welcome");
        $view->display();
    }
}