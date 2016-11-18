<?php

namespace DIC\DicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use DIC\DicoBundlee\Entity\Words;


class AjaxAutocompleteController extends Controller
{
    public function updateDataAction(Request $request)
    {
        $data = $request->get('input');

        $em = $this->container->get('neo4j.manager');
        $term = $data.'.*';
        $query = 'MATCH (m:Word) WHERE m.name =~ {term} or m.nf =~ {term} RETURN m LIMIT 6';
        $params = ['term' => $term];
        $words = $em->cypherQuery($query,$params);

        $wordList = '<ul id="matchList">';
        foreach ($words as $result) {
            $matchStringBold =$result['m']->getproperty('name'); // Replace text field input by bold one
            $wordList .= '<li style="color: #FFFFFF;" id="'.$result['m']->getproperty('name').'">'.$matchStringBold.'</li>'; // Create the matching list - we put maching name in the ID too
        }
        $wordList .= '</ul>';

        $response = new JsonResponse();
        $response->setData(array('wordList' => $wordList));
        return $response;
    }
}
