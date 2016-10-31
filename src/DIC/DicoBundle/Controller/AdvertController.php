<?php

namespace DIC\DicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\vendor\hirevoice\neo4jphp-ogm\lib\HireVoice\Neo4j\Tests;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
  public function menuAction($limit)
  {
    $listLiens = array(
      array('id' => 0, 'title' => 'idée associée'),
      array('id' => 1, 'title' => 'raffinement sémantique'),
      array('id' => 2, 'title' => 'raffinement morphologique'),
      array('id' => 3, 'title' => 'domaine'),
      array('id' => 4, 'title' => 'POS'),
      array('id' => 5, 'title' => 'synonyme'),
      array('id' => 6, 'title' => 'générique'),
      array('id' => 7, 'title' => 'contraire'),
      array('id' => 8, 'title' => 'spécifique'),
      array('id' => 9, 'title' => 'partie'),
      array('id' => 10, 'title' => 'tout'),
      array('id' => 11, 'title' => 'locution'),
      array('id' => 12, 'title' => 'action>agent'),
      array('id' => 13, 'title' => 'action>patient'),
      array('id' => 14, 'title' => 'FL'),
      array('id' => 15, 'title' => 'chose>lieu'),
      array('id' => 16, 'title' => 'action>instrument'),
      array('id' => 17, 'title' => 'caractéristique'),
      array('id' => 18, 'title' => 'r_data'),
      array('id' => 19, 'title' => 'r_lemma'),
      array('id' => 20, 'title' => 'magn'),
      array('id' => 21, 'title' => 'antimagn'),
      array('id' => 22, 'title' => 'famille'),
      array('id' => 23, 'title' => 'caractéristique-1'),
      array('id' => 24, 'title' => 'agent typique-1')
         );

   return $this->render('DICDicoBundle:Advert:menu.html.twig', array(
     // Tout l'intérêt est ici : le contrôleur passe
     // les variables nécessaires au template !
     'listLiens' => $listLiens
   ));
  }
    public function viewAction($id)
    {
     // $id vaut 5 si l'on a appelé l'URL /platform/advert/5

     // Ici, on récupèrera depuis la base de données
     // l'annonce correspondant à l'id $id.
     // Puis on passera l'annonce à la vue pour
     // qu'elle puisse l'afficher

     return new Response("Affichage de l'annonce d'id : ".$id);
    }
    public function indexAction()
    {

        $em = $this->container->get('neo4j.manager');

          $query = 'MATCH (m:Word) RETURN m LIMIT 30';
          
          $words = $em->cypherQuery($query);

          //print_r($users);
          $content = $this->get('templating')->render('DICDicoBundle:Advert:index.html.twig', array('words' =>$words));
          return new Response($content);
    }
}
