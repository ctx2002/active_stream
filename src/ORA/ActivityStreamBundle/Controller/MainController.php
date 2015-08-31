<?php
namespace ORA\ActivityStreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class MainController extends Controller
{
    
    public function homepageAction()
    {
       return $this->render('ActivityStreamBundle:Main:homepage.html.twig');
    }
}
