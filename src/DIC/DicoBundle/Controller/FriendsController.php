<?php

namespace DIC\DicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FriendsController extends Controller
{
    public function indexAction()
    {
        //return new Response("Hello World !! created by Mam");
        //$content = $this->get('templating')->render('DICDicoBundle:Advert:index.html.twig');
        $content = $this
            ->get('templating')
            ->render('DICDicoBundle:Friends:index.html.twig', array(
                'nom' => 'Mamchou'
            )
        );
        return new Response($content);
    }
}
