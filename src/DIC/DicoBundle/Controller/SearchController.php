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

            }
        $listRelation[]= $RelFound;

      }
    }

      $content = $this->get('templating')->render('DICDicoBundle:Search:index.html.twig',array(
        'WordInfo' =>$Mots, 'Definition' => $defMot,
        'result'=>$listRelation

        )
      );
      return new Response($content);

    }
  
}
