<?php

namespace Sfb\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Controller1Controller extends Controller
{
    public function ma_pageAction()
    {
        return $this->render('@SfbSite/Default/ma_page.html.twig');
    }
}
