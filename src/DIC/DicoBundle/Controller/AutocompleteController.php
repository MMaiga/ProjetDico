<?php

namespace DIC\DicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DIC\DicoBundlee\Entity\Words;


class AutocompleteController extends Controller
{
    public function indexAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('DicoBundle:Words')
          ;

        $listWords = $repository->findBy(
            array(),                      // Critere
            array('id' => 'desc'),        // Tri
            null,                         // Limite
            null                          // Offset
          );


      return $this->render('DicoBundle:Autocomplete:index.html.twig', array(
          'listWords' => $listWords,
      ));

    }
}
