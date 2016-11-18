<?php

namespace DIC\DicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Everyman\neo4j\Client;
use DIC\DicoBundle\Entity\Relations;
use DIC\DicoBundle\Repository;


class SearchController extends Controller
{
    public function indexAction(Request $request)
    {
      $data = $request->get('q');

      $em = $this->container->get('neo4j.manager');
      $query = 'MATCH (m:Word) WHERE m.name = {term} or m.nf = {term} RETURN m LIMIT 1';
      $params = ['term' => $data];

      $result = $em->cypherQuery($query,$params);
      $defMot = [];
      foreach ($result as $Word){
          $Mot =$Word['m'] ;
      }

      $Mots = $Mot->getProperties();
      $defMots = $Mot->getRelationships('DEF', '');
      if(!empty($defMots))
      {
        $defMot[] = $defMots[0]->getEndNode()->getProperty('def');
      }
      $RelrafSem = $Mot->getRelationships('r_raff_sem', '');

      if(!empty($RelrafSem)){
        foreach ($RelrafSem as $rel){
            $MotRafSem[] =$rel->getEndNode();
        }

        foreach ($MotRafSem as $raf){
          $defRaf = $raf->getRelationships('DEF', '');
          if(!empty($defRaf))
          {
            $defMot[] = $defRaf[0]->getEndNode()->getProperty('def');
          }
        }
      }

      $listRelations = array(
                array('rel' => 'r_associated', 'relName' => 'idée associée','elts'=>[]),
                array('rel' => 'r_raff_sem', 'relName' => 'raffinement sémantique','elts'=>[]),
                array('rel' => 'r_raff_morpho', 'relName' => 'raffinement morphologique','elts'=>[]),
                array('rel' => 'r_domain', 'relName' => 'domaine','elts'=>[]),
                array('rel' => 'r_pos', 'relName' => 'POS','elts'=>[]),
                array('rel' => 'r_syn', 'relName' => 'synonyme','elts'=>[]),
                array('rel' => 'r_isa', 'relName' => 'générique','elts'=>[]),
                array('rel' => 'r_anto', 'relName' => 'contraire','elts'=>[]),
                array('rel' => 'r_hypo', 'relName' => 'spécifique','elts'=>[]),
                array('rel' => 'r_has_part', 'relName' => 'partie','elts'=>[]),
                array('rel' => 'r_holo', 'relName' => 'tout','elts'=>[]),
                array('rel' => 'r_locution', 'relName' => 'locution','elts'=>[]),
                array('rel' => 'r_agent', 'relName' => 'action>agent','elts'=>[]),
                array('rel' => 'r_patient', 'relName' => 'action>patient','elts'=>[]),
                array('rel' => 'r_flpot', 'relName' => 'flpot','elts'=>[]),
                array('rel' => 'r_lieu', 'relName' => 'chose>lieu','elts'=>[]),
                array('rel' => 'r_instr', 'relName' => 'action>instrument','elts'=>[]),
                array('rel' => 'r_carac', 'relName' => 'caractéristique','elts'=>[]),
                array('rel' => 'r_data', 'relName' => 'r_data','elts'=>[]),
                array('rel' => 'r_lemma', 'relName' => 'r_lemma','elts'=>[]),
                array('rel' => 'r_magn', 'relName' => 'magn','elts'=>[]),
                array('rel' => 'r_antimagn', 'relName' => 'antimagn','elts'=>[]),
                array('rel' => 'r_family', 'relName' => 'famille','elts'=>[]),
                array('rel' => 'r_carac_1', 'relName' => 'caractéristique_1','elts'=>[]),
                array('rel' => 'r_agent_1', 'relName' => 'agent typique_1','elts'=>[]),
                array('rel' => 'r_instr_1', 'relName' => 'instrument>action','elts'=>[]),
                array('rel' => 'r_patient_1', 'relName' => 'patient_1','elts'=>[]),
                array('rel' => 'r_domain_1', 'relName' => 'domaine_1','elts'=>[]),
                array('rel' => 'r_lieu_1', 'relName' => 'lieu>chose','elts'=>[]),
                array('rel' => 'r_chunk_pred', 'relName' => 'predicat','elts'=>[]),
                array('rel' => 'r_lieu_action', 'relName' => 'lieu>action','elts'=>[]),
                array('rel' => 'r_action_lieu', 'relName' => 'action>lieu','elts'=>[]),
                array('rel' => 'r_sentiment', 'relName' => 'sentiment','elts'=>[]),
                array('rel' => 'r_error', 'relName' => 'erreur','elts'=>[]),
                array('rel' => 'r_manner', 'relName' => 'manière','elts'=>[]),
                array('rel' => 'r_meaning', 'relName' => 'sens/signification','elts'=>[]),
                array('rel' => 'r_infopot', 'relName' => 'information potentielle','elts'=>[]),
                array('rel' => 'r_telic_role', 'relName' => 'rôle télique','elts'=>[]),
                array('rel' => 'r_agentif_role', 'relName' => 'rôle agentif','elts'=>[]),
                array('rel' => 'r_verbe_action', 'relName' => 'verbe>action','elts'=>[]),
                array('rel' => 'r_action_verbe', 'relName' => 'action>verbe','elts'=>[]),
                array('rel' => 'r_causatif', 'relName' => 'cause','elts'=>[]),
                array('rel' => 'r_conseq', 'relName' => 'conséquence','elts'=>[]),
                array('rel' => 'r_adj_verbe', 'relName' => 'adj>verbe','elts'=>[]),
                array('rel' => 'r_verbe_adj', 'relName' => 'verbe>adj','elts'=>[]),
                array('rel' => 'r_chunk_sujet', 'relName' => 'r_chunk_sujet','elts'=>[]),
                array('rel' => 'r_chunk_objet', 'relName' => 'r_chunk_objet','elts'=>[]),
                array('rel' => 'r_chunk_loc', 'relName' => 'r_chunk_loc','elts'=>[]),
                array('rel' => 'r_chunk_instr', 'relName' => 'r_chunk_instr','elts'=>[]),
                array('rel' => 'r_aki', 'relName' => 'r_aki','elts'=>[]),
                array('rel' => 'r_time', 'relName' => 'action>temps','elts'=>[]),
                array('rel' => 'r_prev', 'relName' => 'r_prev','elts'=>[]),
                array('rel' => 'r_succ', 'relName' => 'r_succ','elts'=>[]),
                array('rel' => 'r_inhib', 'relName' => 'r_inhib','elts'=>[]),
                array('rel' => 'r_object>mater', 'relName' => 'objet>matiere','elts'=>[]),
                array('rel' => 'r_mater>object', 'relName' => 'matière>objet','elts'=>[]),
                array('rel' => 'r_successeur_time', 'relName' => 'successeur','elts'=>[]),
                array('rel' => 'r_make', 'relName' => 'produit','elts'=>[]),
                array('rel' => 'r_product_of', 'relName' => 'est le produit de','elts'=>[]),
                array('rel' => 'r_against', 'relName' => 's"oppose à','elts'=>[]),
                array('rel' => 'r_against_1', 'relName' => 'a comme opposition','elts'=>[]),
                array('rel' => 'r_implication', 'relName' => 'implication','elts'=>[]),
                array('rel' => 'r_quantificateur', 'relName' => 'quantificateur','elts'=>[]),
                array('rel' => 'r_masc', 'relName' => 'équivalent masc','elts'=>[]),
                array('rel' => 'r_fem', 'relName' => 'équivalent fem','elts'=>[]),
                array('rel' => 'r_equiv', 'relName' => 'équivalent','elts'=>[]),
                array('rel' => 'r_manner_1', 'relName' => 'maniere_1','elts'=>[]),
                array('rel' => 'r_agentive_implication', 'relName' => 'implication agentive','elts'=>[]),
                array('rel' => 'r_instance', 'relName' => 'instance','elts'=>[]),
                array('rel' => 'r_verb_real', 'relName' => 'verbe>real','elts'=>[]),
                array('rel' => 'r_termgroup', 'relName' => 'r_termgroup','elts'=>[]),
                array('rel' => 'r_chunk_head', 'relName' => 'r_chunk_head','elts'=>[]),
                array('rel' => 'r_similar', 'relName' => 'similaire','elts'=>[]),
                array('rel' => 'r_set>item', 'relName' => 'ensemble>item','elts'=>[]),
                array('rel' => 'r_item>set', 'relName' => 'item>ensemble','elts'=>[]),
                array('rel' => 'r_processus>agent', 'relName' => 'processus>agent','elts'=>[]),
                array('rel' => 'r_variante', 'relName' => 'variante','elts'=>[]),
                array('rel' => 'r_has_personnage', 'relName' => 'a comme personnages','elts'=>[]),
                array('rel' => 'r_has_auteur', 'relName' => 'a comme auteur','elts'=>[]),
                array('rel' => 'r_can_eat', 'relName' => 'se nourrit de','elts'=>[]),
                array('rel' => 'r_syn_strict', 'relName' => 'r_syn_strict','elts'=>[]),
                array('rel' => 'r_has_actors', 'relName' => 'a comme acteurs','elts'=>[]),
                array('rel' => 'r_deplac_mode', 'relName' => 'mode de déplacement','elts'=>[]),
                array('rel' => 'r_der_morpho', 'relName' => 'dérivation morphologique','elts'=>[]),
                array('rel' => 'r_has_interpret', 'relName' => 'a comme interprètes','elts'=>[]),
                array('rel' => 'r_color', 'relName' => 'couleur','elts'=>[]),
                array('rel' => 'r_learning_model', 'relName' => 'r_learning_model','elts'=>[]),
                array('rel' => 'r_wiki', 'relName' => 'r_wiki','elts'=>[]),
                array('rel' => 'r_annotation', 'relName' => 'r_annotation','elts'=>[]),
                array('rel' => 'r_cible', 'relName' => 'a comme cible','elts'=>[]),
                array('rel' => 'r_symptomes', 'relName' => 'a comme symptomes','elts'=>[]),
                array('rel' => 'r_annotation_exception', 'relName' => 'r_annotation_exception','elts'=>[]),
                array('rel' => 'r_predecesseur_time', 'relName' => 'prédécesseur','elts'=>[]),
                array('rel' => 'r_diagnostique', 'relName' => 'diagnostique','elts'=>[]),
                array('rel' => 'r_is_smaller_than', 'relName' => 'est plus petit que','elts'=>[]),
                array('rel' => 'r_is_bigger_than', 'relName' => 'est plus gros que','elts'=>[]),
                array('rel' => 'r_accomp', 'relName' => 'accompagne','elts'=>[]),
                array('rel' => 'r_predecesseur_space', 'relName' => 'prédécesseur','elts'=>[]),
                array('rel' => 'r_successeur_space', 'relName' => 'successeur','elts'=>[]),
                array('rel' => 'r_beneficiaire', 'relName' => 'action>bénéficiaire','elts'=>[]),
                array('rel' => 'r_descend_de', 'relName' => 'descend de','elts'=>[]),
                array('rel' => 'r_social_tie', 'relName' => 'relation sociale/famille','elts'=>[]),
                array('rel' => 'r_tributary', 'relName' => 'r_tributary','elts'=>[]),
                array('rel' => 'r_sentiment_1', 'relName' => 'sentiment_1','elts'=>[]),
                array('rel' => 'r_linked_with', 'relName' => 'linked_with','elts'=>[]),
                array('rel' => 'r_domain_subst', 'relName' => 'domain_subst','elts'=>[]),
                array('rel' => 'r_prop', 'relName' => 'propriété','elts'=>[]),
                array('rel' => 'r_foncteur', 'relName' => 'r_foncteur','elts'=>[]),
                array('rel' => 'r_comparison', 'relName' => 'r_comparison','elts'=>[]),
                array('rel' => 'r_but', 'relName' => 'r_but','elts'=>[]),
                array('rel' => 'r_processus>patient', 'relName' => 'processus>patient','elts'=>[]),
                array('rel' => 'r_but_1', 'relName' => 'r_but_1','elts'=>[]),
                array('rel' => 'r_own', 'relName' => 'pers>possession','elts'=>[]),
                array('rel' => 'r_own_1', 'relName' => 'possession>pers','elts'=>[]),
                array('rel' => 'r_compl_agent', 'relName' => 'complément d"agent','elts'=>[]),
                array('rel' => 'r_activ_voice', 'relName' => 'voix active','elts'=>[]),
                array('rel' => 'r_cooccurrence', 'relName' => 'r_cooccurrence','elts'=>[]),
                array('rel' => 'r_make_use_of', 'relName' => 'r_make_use_of','elts'=>[]),
                array('rel' => 'r_is_used_by', 'relName' => 'r_is_used_by','elts'=>[]),
                array('rel' => 'r_verb_ppas', 'relName' => 'r_verb_ppas','elts'=>[]),
                array('rel' => 'r_verb_aux', 'relName' => 'r_verb_aux','elts'=>[]),
                array('rel' => 'r_cohypo', 'relName' => 'co_hyponyme','elts'=>[]),
                array('rel' => 'r_adj_nomprop', 'relName' => 'adj>nomprop','elts'=>[]),
                array('rel' => 'r_nomprop_adj', 'relName' => 'nomprop>adj','elts'=>[]),
                array('rel' => 'r_adj_adv', 'relName' => 'adj>adv','elts'=>[]),
                array('rel' => 'r_adv_adj', 'relName' => 'adv>adj','elts'=>[]),
                array('rel' => 'r_predecesseur_logic', 'relName' => 'prédécesseur logique','elts'=>[]),
                array('rel' => 'r_successeur_logic', 'relName' => 'successeur logique','elts'=>[]),
                array('rel' => 'r_link', 'relName' => 'r_link','elts'=>[]),
                array('rel' => 'r_isa_incompatible', 'relName' => 'r_isa_incompatible','elts'=>[])
      );
      $listPots = array(
        array('Potentiel' => '_INFO-SEM-PERS', 'desc' => 'une personne'),
        array('Potentiel' => '_INFO-SEM-ORGA', 'desc' => 'une organisation'),
        array('Potentiel' => '_INFO-SEM-PLACE', 'desc' => 'un lieu'),
        array('Potentiel' => '_INFO-SEM-EVENT', 'desc' => 'un evenement'),
        array('Potentiel' => '_INFO-SEM-THING-CONCRETE', 'desc' => 'une chose concrète'),
        array('Potentiel' => '_INFO-SEM-SUBST', 'desc' => 'une substance'),
        array('Potentiel' => '_INFO-SEM-THING-ABSTR', 'desc' => 'une chose abstraite'),
        array('Potentiel' => '_INFO-LTECH', 'desc' => 'vocabulaire technique'),
        array('Potentiel' => '_INFO-BAD-ORTHO', 'desc' => 'mauvaise orthographe'),
        array('Potentiel' => '_INFO-SEM-CARAC', 'desc' => 'une caractéristique'),
        array('Potentiel' => '_INFO-SEM-THING-NATURAL', 'desc' => 'un chose naturelle'),
        array('Potentiel' => '_INFO-SEM-LIVING-BEING', 'desc' => 'un etre vivant'),
        array('Potentiel' => '_INFO-SEM-THING-ARTEFACT', 'desc' => 'un artefact (chose construite par l’homme)'),
        array('Potentiel' => '_INFO-SEM-ACTION', 'desc' => 'une action'),
        array('Potentiel' => '_INFO-SEM-TIME', 'desc' => 'rapport avec le temps'),
        array('Potentiel' => '_INFO-SEM-SET', 'desc' => 'un ensemble'),
        array('Potentiel' => '_INFO-VOC-COMMON', 'desc' => 'vocabulaire courant'),
        array('Potentiel' => '_INFO-SEM-IMAGINARY', 'desc' => 'chose imaginaire'),
        array('Potentiel' => '_INFO-MEANING-FIGURED', 'desc' => 'sens figuré'),
        array('Potentiel' => '_INFO-POLYSEMIC', 'desc' => 'terme polysemique'),
        array('Potentiel' => '_POL-NEG', 'desc' => 'polarité negative'),
        array('Potentiel' => '_POL-POS', 'desc' => 'polarité positive'),
        array('Potentiel' => '_INFO-LHYPOCORISTIC', 'desc' => 'hypocoristiques'),
        array('Potentiel' => '_POL-NEUTRE', 'desc' => 'polarité neutre'),
        array('Potentiel' => '_INFO-LRARE ', 'desc' => 'terme rare'),
        array('Potentiel' => '_SEX_YES', 'desc' => 'rapport avec le sexe'),
        array('Potentiel' => '_SEX_NO', 'desc' => 'sans rapport avec le sexe'),
        array('Potentiel' => '_INFO-SEM-PERS-FEM', 'desc' => 'une personne fem'),
        array('Potentiel' => '_INFO-SEM-PERS-MASC', 'desc' => 'une personne masc'),
        array('Potentiel' => '_INFO-MEANING-LITERAL', 'desc' => 'sens literal'),
        array('Potentiel' => '_INFO-MONOSEMIC', 'desc' => 'terme monosemique'),
        array('Potentiel' => '_INFO-SEM-NAMED-ENTITY', 'desc' => 'entité nommée'),
        array('Potentiel' => '_INFO-SEM-COLOR-RELATED', 'desc' => 'en rapport avec les couleurs/apparences'),
        array('Potentiel' => '_INFO-SEM-EMOTION-RELATED', 'desc' => 'en rapport avec les émotions'),
        array('Potentiel' => '_INFO-MEANING-PREFERED', 'desc' => 'sens préféré'),
        array('Potentiel' => '_INFO-POLYMORPHIC', 'desc' => 'termes ayant plusieurs morphologie'),
        array('Potentiel' => '_INFO-SEM-PLACE-GEO', 'desc' => 'lieu géographique'),
        array('Potentiel' => '_INFO-SEM-PLACE-HUMAN', 'desc' => 'lieu humain'),
        array('Potentiel' => '_INFO-BIZARRE', 'desc' => 'entrée bizarre'),
        array('Potentiel' => '_INFO-VOC-HARD', 'desc' => 'vocaulaire difficile'),
        array('Potentiel' => '_INFO-SEM-THING', 'desc' => 'chose'),
        array('Potentiel' => '_INFO-SEM-PLACE-ANATOMICAL', 'desc' => 'lieu anatomique'),
        array('Potentiel' => '_INFO-SEM-PLACE-ABSTRACT', 'desc' => 'lieu abstrait'),
        array('Potentiel' => '_INFO-COUNTABLE-YES', 'desc' => 'countable/énumérable'),
        array('Potentiel' => '_INFO-COUNTABLE-NO', 'desc' => 'non countable/énumérable'),
        array('Potentiel' => '_INFO-SEM-PROPERTY-NAME', 'desc' => 'nom de propriété'),
        array('Potentiel' => '_INFO-SEM-QUANTIFIER', 'desc' => 'quantifieur (comme brin dans brin d’herbe, ou pincée, dans pincée de sel)'),
        array('Potentiel' => '_INFO-BAD-CASE', 'desc' => 'mauvaise casse (maj, min)'),
        array('Potentiel' => '_INFO-SEM-PHENOMENA', 'desc' => 'phénomène'),
        array('Potentiel' => '_INFO-SEM-STATE', 'desc' => 'état')
      );
      $Name = $Mot->getProperty('name');
      foreach ($listRelations as $relation){
          $MyRel = $relation['rel'];

        $RelsMot = $Mot->getRelationships($MyRel, '');
        if(!empty($RelsMot))
        {
          $RelFound = array('name' => $relation['relName'], 'eltsFound' => []);
          foreach ($RelsMot as $relMots){
              $RelEndNode = $relMots->getEndNode();
              if(strpos($RelEndNode->getProperty('name'), ":r") !== 0){
                  $RelEndNodeName = $RelEndNode->getProperty('name');
                  $RelEndNodeNF = $RelEndNode->getProperty('nf');
                  if($RelEndNodeName!==$Name){
                      if($MyRel!=='r_lemma'){
                          preg_match("/([^A-Za-z])/",$RelEndNodeName,$alpha);
                          preg_match("/([^A-Za-z0-9])/",$RelEndNodeName,$alphaNum);

                          if(!empty($alpha) and !empty($alphaNum))
                            if($RelEndNodeNF == '')
                              $Mot2 = $RelEndNodeName;
                            else
                              $Mot2 = $RelEndNodeNF;
                          else
                            $Mot2 = $RelEndNodeName;

                          $RelFound['eltsFound'][] = $Mot2;
                       }
                       else {
                           $RelFound['eltsFound'][] = $RelEndNode->getProperty('name');
                       }
                    }
                }
                else{
                      /*  $RelTypr =$RelEndNode->getProperty('name');
                        $RelTypr_r_rels =$RelEndNode->getRelationships('','');
                        foreach ($RelTypr_r_rels as $RelationShip){
                          $EndName = $RelationShip->getEndNode()->getProperty('name');
                          $EndNF = $RelationShip->getEndNode()->getProperty('nf')
                            preg_match("/([^A-Za-z])/",$EndName,$alpha);
                            preg_match("/([^A-Za-z0-9])/",$EndName,$alphaNum);
                            if($EndName!==$RelTypr){
                                if(!empty($alpha) and !empty($alphaNum))
                                  if($EndNF == '')
                                    $RelTypr_r[] = $EndName;
                                  else
                                    $RelTypr_r[] = $EndNF;
                                else
                                  $RelTypr_r[] = $EndName;
                            }

                        }*/
                }
                /*else {
                  if($MyRel==='r_infopot'){
                    foreach ($listPots as $row){
                        if($row['Potentiel'] ===$RelEndNodeName )
                        $relations[] = $row['p'];
                    }
                    $RelFound['eltsFound'][] = $RelEndNode->getProperty('name');
                  }
                }*/


            }
        $listRelation[]= $RelFound;
        //$RelType_r=$RelTypr_r;
      //  $RelType_r_rels=$RelationShipEnd;
      }
    }

      $content = $this->get('templating')->render('DICDicoBundle:Search:index.html.twig',array(
        'WordInfo' =>$Mots, 'Definition' => $defMot,
        'result'=>$listRelation
        //,'reltype_r'=>$RelType_r
        //,'relType_r_rels' =>$RelType_r_rels
        )
      );
      return new Response($content);
      /*$response = new JsonResponse();
      $response->setData($listRelation);
      return $response;*/

    }
  /*  public function relationAction(Request $request)
    {

      $data = $request->get('q');
      $em = $this->container->get('neo4j.manager');
      $query = 'MATCH (m:Word) WHERE m.name = {name} or m.nf = {name} MATCH p=(m)-[r]->(a) RETURN m,p LIMIT 130';
      $params = ['name' => $data];

      $result = $em->cypherQuery($query,$params);
      $Mots = [];
      foreach ($result as $row){
          $Mot =  $row['m'];
          $relations[] = $row['p'];
      }

      if($Mot->getProperty('nf')==''){
    		$nom = $Mot->getProperty('name');
    		}
    	else
    		$nom = $Mot->getProperty('nf');
        $mov = [
            'name' => $nom,
            'cast_associated' => ['name'=>'idée associée','elts'=>[]],
            'cast_raff_sem' => ['name'=>'raffinement semantique','elts'=>[]],
            'cast_raff_morpho' => ['name'=>'raffinement morphologique','elts'=>[]],
            'cast_domain' => ['name'=>'domaine','elts'=>[]],
            'cast_pos' => ['name'=>'POS','elts'=>[]],
        'cast_syn' => ['name'=>'synonyme','elts'=>[]],
        'cast_isa' => ['name'=>'générique','elts'=>[]],
        'cast_anto' => ['name'=>'contraire','elts'=>[]],
        'cast_hypo' => ['name'=>'spécifique','elts'=>[]],
        'cast_has_part' => ['name'=>'partie','elts'=>[]],
        'cast_holo' => ['name'=>'tout','elts'=>[]],
        'cast_locution' => ['name'=>'locution','elts'=>[]],
        'cast_flpot' => ['name'=>'Flpot','elts'=>[]],
        'cast_agent' => ['name'=>'action>agent','elts'=>[]],
        'cast_patient' => ['name'=>'action>patient','elts'=>[]],
        'cast_lieu' => ['name'=>'chose>lieu','elts'=>[]],
        'cast_instr' => ['name'=>'action>instrument','elts'=>[]],
        'cast_carac' => ['name'=>'caractéristique','elts'=>[]],
        'cast_data' => ['name'=>'r_data','elts'=>[]],
        'cast_lemma' => ['name'=>'r_lemma','elts'=>[]],
        'cast_magn' => ['name'=>'magn','elts'=>[]],
        'cast_antimagn' => ['name'=>'antimagn','elts'=>[]],
        'cast_family' => ['name'=>'famille','elts'=>[]],
        'cast_carac_1' => ['name'=>'caractéristique-1','elts'=>[]],
        'cast_agent_1' => ['name'=>'agent typique-1','elts'=>[]],
        'cast_instr_1' => ['name'=>'instrument>action','elts'=>[]],
        'cast_patient_1' => ['name'=>'patient-1','elts'=>[]],
        'cast_domain_1' => ['name'=>'domaine-1','elts'=>[]],
        'cast_lieu_1' => ['name'=>'lieu>chose','elts'=>[]],
        'cast_chunk_pred' => ['name'=>'predicat','elts'=>[]],
        'cast_lieu_action' => ['name'=>'lieu>action','elts'=>[]],
        'cast_action_lieu' => ['name'=>'action>lieu','elts'=>[]],
        'cast_sentiment' => ['name'=>'sentiment','elts'=>[]],
        'cast_error' => ['name'=>'erreur','elts'=>[]],
        'cast_manner' => ['name'=>'manière','elts'=>[]],
        'cast_meaning' => ['name'=>'sens/signification','elts'=>[]],
        'cast_infopot' => ['name'=>'information potentielle','elts'=>[]],
        'cast_telic_role' => ['name'=>'rôle télique','elts'=>[]],
        'cast_agentif_role' => ['name'=>'rôle agentif','elts'=>[]],
        'cast_verbe_action' => ['name'=>'verbe>action','elts'=>[]],
        'cast_action_verbe' => ['name'=>'action>verbe','elts'=>[]],
        'cast_conseq' => ['name'=>'cause','elts'=>[]],
        'cast_causatif' => ['name'=>'conséquence','elts'=>[]],
        'cast_adj_verbe' => ['name'=>'adj>verbe','elts'=>[]],
        'cast_verbe_adj' => ['name'=>'verbe>adj','elts'=>[]],
        'cast_chunk_sujet' => ['name'=>'r_chunk_sujet','elts'=>[]],
        'cast_chunk_objet' => ['name'=>'r_chunk_objet','elts'=>[]],
        'cast_chunk_loc' => ['name'=>'r_chunk_loc','elts'=>[]],
        'cast_chunk_instr' => ['name'=>'r_chunk_instr','elts'=>[]],
        'cast_time' => ['name'=>'action>temps','elts'=>[]],
        'cast_object_mater' => ['name'=>'objet>matiere','elts'=>[]],
        'cast_mater_object' => ['name'=>'matière>objet','elts'=>[]],
        'cast_successeur_time' => ['name'=>'successeur','elts'=>[]],
        'cast_make' => ['name'=>'produit','elts'=>[]],
        'cast_product_of' => ['name'=>'est le produit de','elts'=>[]],
        'cast_against' => ['name'=>'s"oppose à','elts'=>[]],
        'cast_against_1' => ['name'=>'a comme opposition','elts'=>[]],
        'cast_implication' => ['name'=>'implication','elts'=>[]],
        'cast_quantificateur' => ['name'=>'quantificateur','elts'=>[]],
        'cast_masc' => ['name'=>'équivalent masc','elts'=>[]],
        'cast_fem' => ['name'=>'équivalent fem','elts'=>[]],
        'cast_equiv' => ['name'=>'équivalent','elts'=>[]],
        'cast_manner_1' => ['name'=>'maniere-1','elts'=>[]],
        'cast_agentive_implication' => ['name'=>'implication agentive','elts'=>[]],
        'cast_instance' => ['name'=>'instance','elts'=>[]],
        'cast_verb_real' => ['name'=>'verbe>real','elts'=>[]],
        'cast_chunk_head' => ['name'=>'r_chunk_head','elts'=>[]],
        'cast_similar' => ['name'=>'similaire','elts'=>[]],
        'cast_set_item' => ['name'=>'ensemble>item','elts'=>[]],
        'cast_item_set' => ['name'=>'item>ensemble','elts'=>[]],
        'cast_processus_agent' => ['name'=>'processus>agent','elts'=>[]],
        'cast_variante' => ['name'=>'variante','elts'=>[]],
        'cast_syn_strict' => ['name'=>'r_syn_strict','elts'=>[]],
        'cast_is_smaller_than' => ['name'=>'est plus petit que','elts'=>[]],
        'cast_is_bigger_than' => ['name'=>'est plus gros que','elts'=>[]],
        'cast_accomp' => ['name'=>'accompagne','elts'=>[]],
        'cast_processus_patient' => ['name'=>'processus>patient','elts'=>[]],
        'cast_verb_ppas' => ['name'=>'r_verb_ppas','elts'=>[]],
        'cast_cohypo' => ['name'=>'co-hyponyme','elts'=>[]],
        'cast_der_morpho' => ['name'=>'dérivation morphologique','elts'=>[]],
        'cast_has_auteur' => ['name'=>'a comme auteur','elts'=>[]],
        'cast_has_personnage' => ['name'=>'a comme personnages','elts'=>[]],
        'cast_can_eat' => ['name'=>'se nourrit de','elts'=>[]],
        'cast_has_actors' => ['name'=>'a comme acteurs','elts'=>[]],
        'cast_deplac_mode' => ['name'=>'mode de déplacement','elts'=>[]],
        'cast_has_interpret' => ['name'=>'a comme interprètes','elts'=>[]],
        'cast_color' => ['name'=>'couleur','elts'=>[]],
        'cast_cible' => ['name'=>'a comme cible','elts'=>[]],
        'cast_symptomes' => ['name'=>'a comme symptomes','elts'=>[]],
        'cast_predecesseur_time' => ['name'=>'prédécesseur','elts'=>[]],
        'cast_diagnostique' => ['name'=>'diagnostique','elts'=>[]],
        'cast_predecesseur_space' => ['name'=>'prédécesseur','elts'=>[]],
        'cast_successeur_space' => ['name'=>'successeur','elts'=>[]],
        'cast_social_tie' => ['name'=>'relation sociale/famille','elts'=>[]],
        'cast_tributary' => ['name'=>'r_tributary','elts'=>[]],
        'cast_sentiment_1' => ['name'=>'sentiment-1','elts'=>[]],
        'cast_linked_with' => ['name'=>'linked-with','elts'=>[]],
        'cast_foncteur' => ['name'=>'r_foncteur','elts'=>[]],
        'cast_comparison' => ['name'=>'r_comparison','elts'=>[]],
        'cast_but' => ['name'=>'r_but','elts'=>[]],
        'cast_but_1' => ['name'=>'r_but-1','elts'=>[]],
        'cast_own' => ['name'=>'pers>possession','elts'=>[]],
        'cast_own_1' => ['name'=>'possession>pers','elts'=>[]],
        'cast_verb_aux' => ['name'=>'r_verb_aux','elts'=>[]],
        'cast_predecesseur_logic' => ['name'=>'prédécesseur logique','elts'=>[]],
        'cast_successeur_logic' => ['name'=>'successeur logique','elts'=>[]],
        'cast_isa_incompatible' => ['name'=>'r_isa-incompatible','elts'=>[]],
        'cast_compl_agent' => ['name'=>'complément d"agent','elts'=>[]],
        'cast_beneficiaire' => ['name'=>'action>bénéficiaire','elts'=>[]],
        'cast_descend_de' => ['name'=>'descend de','elts'=>[]],
        'cast_domain_subst' => ['name'=>'domain_subst','elts'=>[]],
        'cast_prop' => ['name'=>'propriété','elts'=>[]],
        'cast_activ_voice' => ['name'=>'voix active','elts'=>[]],
        'cast_make_use_of' => ['name'=>'r_make_use_of','elts'=>[]],
        'cast_is_used_by' => ['name'=>'r_is_used_by','elts'=>[]],
        'cast_adj_nomprop' => ['name'=>'adj>nomprop','elts'=>[]],
        'cast_nomprop_adj' => ['name'=>'nomprop>adj','elts'=>[]],
        'cast_adj_adv' => ['name'=>'adj>adv','elts'=>[]],
        'cast_adv_adj' => ['name'=>'adv>adj','elts'=>[]],
        'cast_link' => ['name'=>'r_link','elts'=>[]],
        'cast_cooccurrence' => ['name'=>'r_cooccurrence','elts'=>[]],
        'cast_aki' => ['name'=>'r_aki','elts'=>[]],
        'cast_wiki' => ['name'=>'r_wiki','elts'=>[]],
        'cast_annotation_exception' => ['name'=>'r_annotation_exception','elts'=>[]],
        'cast_annotation' => ['name'=>'r_annotation','elts'=>[]],
        'cast_inhib' => ['name'=>'r_inhib','elts'=>[]],
        'cast_prev' => ['name'=>'r_prev','elts'=>[]],
        'cast_succ' => ['name'=>'r_succ','elts'=>[]],
        'cast_termgroup' => ['name'=>'r_termgroup','elts'=>[]],
        'cast_learning_model' => ['name'=>'r_learning_model','elts'=>[]]
            ];

      foreach ($relations as $rel){
          //$relation[] =  $rel->getType();getStartNode()
      //  $type = $relation->getType();
        $Mot1 = $rel->getEndNode();
        $relType = strtolower($rel->getRelationships()[0]->getType());

      		$Mo = $Mot1->getProperty('name');
          preg_match("/([^A-Za-z])/",$Mo,$alpha);
          preg_match("/([^A-Za-z0-9])/",$Mo,$alphaNum);

          if(!empty($alpha) and !empty($alphaNum))
      			if($Mot1->getProperty('nf')=='')
      				$Mot2 = $Mot1->getProperty('name');
      			else
      				$Mot2 = $Mot1->getProperty('nf');
      		else
      			$Mot2 = $Mot1->getProperty('name');
              if ($relType ==="r_associated"){
      				$mov['cast_associated']['elts'][] =$Mot2;
      			}
      			else
      		if ($relType ==="r_raff_sem"){
      			$mov['cast_raff_sem']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_raff_morpho"){
      			$mov['cast_raff_morpho']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_domain"){
      			$mov['cast_domain']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_pos"){
      			$mov['cast_pos']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_syn"){
      			$mov['cast_syn']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_isa"){
      			$mov['cast_isa']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_anto"){
      			$mov['cast_anto']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_hypo"){
      			$mov['cast_hypo']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_has_part"){
      			$mov['cast_has_part']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_holo"){
      			$mov['cast_holo']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_locution"){
      			$mov['cast_locution']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_flpot"){
      			$mov['cast_flpot']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_agent"){
      			$mov['cast_agent']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_patient"){
      			$mov['cast_patient']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_lieu"){
      			$mov['cast_lieu']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_instr"){
      			$mov['cast_instr']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_carac"){
      			$mov['cast_carac']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_data"){
      			$mov['cast_data']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_lemma"){
      			$mov['cast_lemma']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_magn"){
      			$mov['cast_magn']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_antimagn"){
      			$mov['cast_antimagn']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_family"){
      			$mov['cast_family']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_carac_1"){
      			$mov['cast_carac_1']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_agent_1"){
      				$mov['cast_agent_1']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_instr_1"){
      			$mov['cast_instr_1']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_patient_1"){
      			$mov['cast_patient_1']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_domain_1"){
      			$mov['cast_domain_1']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_lieu_1"){
      			$mov['cast_lieu_1']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_pred"){
      			$mov['cast_chunk_pred']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_lieu_action"){
      			$mov['cast_lieu_action']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_action_lieu"){
      			$mov['cast_action_lieu']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_sentiment"){
      			$mov['cast_sentiment']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_error"){
      			$mov['cast_error']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_manner"){
      			$mov['cast_manner']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_meaning"){
      			$mov['cast_meaning']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_infopot"){
      			$mov['cast_infopot']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_telic_role"){
      			$mov['cast_telic_role']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_agentif_role"){
      			$mov['cast_agentif_role']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_verbe_action"){
      			$mov['cast_verbe_action']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_action_verbe"){
      			$mov['cast_action_verbe']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_conseq"){
      			$mov['cast_conseq']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_causatif"){
      			$mov['cast_causatif']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_adj_verbe"){
      			$mov['cast_adj_verbe']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_verbe_adj"){
      			$mov['cast_verbe_adj']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_sujet"){
      			$mov['cast_chunk_sujet']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_objet"){
      			$mov['cast_chunk_objet']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_loc"){
      			$mov['cast_chunk_loc']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_instr"){
      			$mov['cast_chunk_instr']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_time"){
      			$mov['cast_time']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_object_mater"){
      			$mov['cast_object_mater']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_mater_object"){
      			$mov['cast_mater_object']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_successeur_time"){
      			$mov['cast_successeur_time']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_make"){
      			$mov['cast_make']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_product_of"){
      			$mov['cast_product_of']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_against"){
      			$mov['cast_against']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_against_1"){
      			$mov['cast_against_1']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_implication"){
      			$mov['cast_implication']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_quantificateur"){
      			$mov['cast_quantificateur']['elts'][] =$Mot2;

      		} else
      		if ($relType ==="r_masc"){
      			$mov['cast_masc']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_fem"){
      			$mov['cast_fem']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_equiv"){
      			$mov['cast_equiv']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_manner_1"){
      			$mov['cast_manner_1']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_agentive_implication"){
      			$mov['cast_agentive_implication']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_instance"){
      			$mov['cast_instance']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_verb_real"){
      			$mov['cast_verb_real']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_chunk_head"){
      			$mov['cast_chunk_head']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_similar"){
      			$mov['cast_similar']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_set_item"){
      			$mov['cast_set_item']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_item_set"){
      			$mov['cast_item_set']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_processus_agent"){
      			$mov['cast_processus_agent']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_variante"){
      			$mov['cast_variante']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_syn_strict"){
      			$mov['cast_syn_strict']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_is_smaller_than"){
      			$mov['cast_is_smaller_than']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_is_bigger_than"){
      			$mov['cast_is_bigger_than']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_accomp"){
      			$mov['cast_accomp']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_processus_patient"){
      			$mov['cast_processus_patient']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_verb_ppas"){
      			$mov['cast_verb_ppas']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_cohypo"){
      			$mov['cast_cohypo']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_der_morpho"){
      			$mov['cast_der_morpho']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_has_auteur"){
      			$mov['cast_has_auteur']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_has_personnage"){
      			$mov['cast_has_personnage']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_can_eat"){
      			$mov['cast_can_eat']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_has_actors"){
      			$mov['cast_has_actors']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_deplac_mode"){
      			$mov['cast_deplac_mode']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_has_interpret"){

      			$mov['cast_has_interpret']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_color"){
      			$mov['cast_color']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_cible"){
      			$mov['cast_cible']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_symptomes"){
      			$mov['cast_symptomes']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_predecesseur_time"){
      			$mov['cast_predecesseur_time']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_diagnostique"){
      			$mov['cast_diagnostique']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_predecesseur_space"){
      			$mov['cast_predecesseur_space']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_successeur_space"){
      			$mov['cast_successeur_space']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_social_tie"){
      			$mov['cast_social_tie']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_tributary"){
      			$mov['cast_tributary']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_sentiment_1"){
      			$mov['cast_sentiment_1']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_linked_with"){
      			$mov['cast_linked_with']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_foncteur"){
      			$mov['cast_foncteur']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_comparison"){
      			$mov['cast_comparison']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_but"){
      			$mov['cast_but']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_but_1"){

      		$mov['cast_but_1']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_own"){
      			$mov['cast_own']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_own_1"){
      			$mov['cast_own_1']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_verb_aux"){
      			$mov['cast_verb_aux']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_predecesseur_logic"){
      			$mov['cast_predecesseur_logic']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_successeur_logic"){
      			$mov['cast_successeur_logic']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_isa_incompatible"){
      			$mov['cast_isa_incompatible']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_compl_agent"){
      			$mov['cast_compl_agent']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_beneficiaire"){
      			$mov['cast_beneficiaire']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_descend_de"){
      			$mov['cast_descend_de']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_domain_subst"){
      			$mov['cast_domain_subst']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_prop"){
      			$mov['cast_prop']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_activ_voice"){
      			$mov['cast_activ_voice']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_make_use_of"){
      			$mov['cast_make_use_of']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_is_used_by"){
      			$mov['cast_is_used_by']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_adj_nomprop"){
      			$mov['cast_adj_nomprop']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_nomprop_adj"){
      			$mov['cast_nomprop_adj']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_adj_adv"){
      			$mov['cast_adj_adv']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_adv_adj"){
      			$mov['cast_adv_adj']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_link"){
      			$mov['cast_link']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_cooccurrence"){
      			$mov['cast_cooccurrence']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_aki"){
      			$mov['cast_aki']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_wiki"){
      			$mov['cast_wiki']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_annotation_exception"){
      			$mov['cast_annotation_exception']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_annotation"){
      			$mov['cast_annotation']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_inhib"){
      			$mov['cast_inhib']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_prev"){
      			$mov['cast_prev']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_succ"){
      			$mov['cast_succ']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_termgroup"){
      			$mov['cast_termgroup']['elts'][] =$Mot2;
      		} else
      		if ($relType ==="r_learning_model"){
      		$mov['cast_learning_model']['elts'][] =$Mot2;
              }

      }

    $content = $this->get('templating')->render('DICDicoBundle:Search:relation.html.twig', array('result' =>$mov));
      return new Response($content);


      $response = new JsonResponse();
      $response->setData($mov);

      return $response;
    }*/

}
