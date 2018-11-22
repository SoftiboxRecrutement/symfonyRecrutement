<?php

namespace Sfb\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Controller2Controller extends Controller
{
    public function mes_infosAction()
    {
        return $this->render('@SfbSite/Default/mes_infos.html.twig');
    }
}
