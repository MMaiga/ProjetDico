<?php

namespace DIC\DicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Everyman\neo4j\Client;
use DIC\DicoBundle\Entity\Words;


class SearchController extends Controller
{
    public function indexAction(Request $request)
    {
      $data = $request->get('q');

      $em = $this->container->get('neo4j.manager');
      $query = 'MATCH (m:Word) WHERE m.name = {term} or m.nf = {term} RETURN m LIMIT 1';
      $params = ['term' => $data];

      $result = $em->cypherQuery($query,$params);

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

      $content = $this->get('templating')->render('DICDicoBundle:Search:index.html.twig', array('WordInfo' =>$Mots, 'Definition' => $defMot));
      return new Response($content);
      /*$response = new JsonResponse();
      $response->setData($def);
      return $response;*/

    }
    public function relationAction(Request $request)
    {

      $data = $request->get('q');
      $em = $this->container->get('neo4j.manager');
      $query = 'MATCH (m:Word) WHERE m.name = {name} MATCH p=(m)-[r]->(a) RETURN m,p LIMIT 40';
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
        'def' => [],'cast_associated' => [],'cast_raff_sem' => [],'cast_raff_morpho' => [],'cast_domain' => [],'cast_pos' => [],
        'cast_syn' => [],'cast_isa' => [],'cast_anto' => [],'cast_hypo' => [],'cast_has_part' => [],
        'cast_holo' => [],
        'cast_locution' => [],
        'cast_flpot' => [],
        'cast_agent' => [],
        'cast_patient' => [],
        'cast_lieu' => [],
        'cast_instr' => [],
        'cast_carac' => [],
        'cast_data' => [],
        'cast_lemma' => [],
        'cast_magn' => [],
        'cast_antimagn' => [],
        'cast_family' => [],
        'cast_carac_1' => [],
        'cast_agent_1' => [],
        'cast_instr_1' => [],
        'cast_patient_1' => [],
        'cast_domain_1' => [],
        'cast_lieu_1' => [],
        'cast_chunk_pred' => [],
        'cast_lieu_action' => [],
        'cast_action_lieu' => [],
        'cast_sentiment' => [],
        'cast_error' => [],
        'cast_manner' => [],
        'cast_meaning' => [],
        'cast_infopot' => [],
        'cast_telic_role' => [],
        'cast_agentif_role' => [],
        'cast_verbe_action' => [],
        'cast_action_verbe' => [],
        'cast_conseq' => [],
        'cast_causatif' => [],
        'cast_adj_verbe' => [],
        'cast_verbe_adj' => [],
        'cast_chunk_sujet' => [],
        'cast_chunk_objet' => [],
        'cast_chunk_loc' => [],
        'cast_chunk_instr' => [],
        'cast_time' => [],
        'cast_object_mater' => [],
        'cast_mater_object' => [],
        'cast_successeur_time' => [],
        'cast_make' => [],
        'cast_product_of' => [],
        'cast_against' => [],
        'cast_against_1' => [],
        'cast_implication' => [],
        'cast_quantificateur' => [],
        'cast_masc' => [],
        'cast_fem' => [],
        'cast_equiv' => [],
        'cast_manner_1' => [],
        'cast_agentive_implication' => [],
        'cast_instance' => [],
        'cast_verb_real' => [],
        'cast_chunk_head' => [],
        'cast_similar' => [],
        'cast_set_item' => [],
        'cast_item_set' => [],
        'cast_processus_agent' => [],
        'cast_variante' => [],
        'cast_syn_strict' => [],
        'cast_is_smaller_than' => [],
        'cast_is_bigger_than' => [],
        'cast_accomp' => [],
        'cast_processus_patient' => [],
        'cast_verb_ppas' => [],
        'cast_cohypo' => [],
        'cast_der_morpho' => [],
        'cast_has_auteur' => [],
        'cast_has_personnage' => [],
        'cast_can_eat' => [],
        'cast_has_actors' => [],
        'cast_deplac_mode' => [],
        'cast_has_interpret' => [],
        'cast_color' => [],
        'cast_cible' => [],
        'cast_symptomes' => [],
        'cast_predecesseur_time' => [],
        'cast_diagnostique' => [],
        'cast_predecesseur_space' => [],
        'cast_successeur_space' => [],
        'cast_social_tie' => [],
        'cast_tributary' => [],
        'cast_sentiment_1' => [],
        'cast_linked_with' => [],
        'cast_foncteur' => [],
        'cast_comparison' => [],
        'cast_but' => [],
        'cast_but_1' => [],
        'cast_own' => [],
        'cast_own_1' => [],
        'cast_verb_aux' => [],
        'cast_predecesseur_logic' => [],
        'cast_successeur_logic' => [],
        'cast_isa_incompatible' => [],
        'cast_compl_agent' => [],
        'cast_beneficiaire' => [],
        'cast_descend_de' => [],
        'cast_domain_subst' => [],
        'cast_prop' => [],
        'cast_activ_voice' => [],
        'cast_make_use_of' => [],
        'cast_is_used_by' => [],
        'cast_adj_nomprop' => [],
        'cast_nomprop_adj' => [],
        'cast_adj_adv' => [],
        'cast_adv_adj' => [],
        'cast_link' => [],
        'cast_cooccurrence' => [],
        'cast_aki' => [],
        'cast_wiki' => [],
        'cast_annotation_exception' => [],
        'cast_annotation' => [],
        'cast_inhib' => [],
        'cast_prev' => [],
        'cast_succ' => [],
        'cast_termgroup' => [],
        'cast_learning_model' => []
            ];

      foreach ($relations as $rel){
          //$relation[] =  $rel->getType();getStartNode()
      //  $type = $relation->getType();
        $Mot1 = $rel->getEndNode();
        $relType = strtolower($rel->getRelationships()[0]->getType());

    		if ($relType ==="def"){

    				$mov['def'][] =$Mot1->getProperty('def');
    			}
          else{
      		$Mo = $Mot1->getProperty('name');
      		if(verif_alpha($Mo)==false and verif_alphaNum($Mo)==false)
      			if($Mot1->getProperty('nf')=='')
      				$Mot2 = $Mot1->getProperty('name');
      			else
      				$Mot2 = $Mot1->getProperty('nf');
      		else
      			$Mot2 = $Mot1->getProperty('name');
              if ($relType ==="r_associated"){
      				$mov['cast_associated'][] =$Mot2;
      			}
      			else
      		if ($relType ==="r_raff_sem"){
      			$mov['cast_raff_sem'][] =$Mot2;

      		} else
      		if ($relType ==="r_raff_morpho"){
      			$mov['cast_raff_morpho'][] =$Mot2;

      		} else
      		if ($relType ==="r_domain"){
      			$mov['cast_domain'][] =$Mot2;

      		} else
      		if ($relType ==="r_pos"){
      			$mov['cast_pos'][] =$Mot2;

      		} else
      		if ($relType ==="r_syn"){
      			$mov['cast_syn'][] =$Mot2;

      		} else
      		if ($relType ==="r_isa"){
      			$mov['cast_isa'][] =$Mot2;

      		} else
      		if ($relType ==="r_anto"){
      			$mov['cast_anto'][] =$Mot2;

      		} else
      		if ($relType ==="r_hypo"){
      			$mov['cast_hypo'][] =$Mot2;

      		} else
      		if ($relType ==="r_has_part"){
      			$mov['cast_has_part'][] =$Mot2;

      		} else
      		if ($relType ==="r_holo"){
      			$mov['cast_holo'][] =$Mot2;

      		} else
      		if ($relType ==="r_locution"){
      			$mov['cast_locution'][] =$Mot2;

      		} else
      		if ($relType ==="r_flpot"){
      			$mov['cast_flpot'][] =$Mot2;

      		} else
      		if ($relType ==="r_agent"){
      			$mov['cast_agent'][] =$Mot2;

      		} else
      		if ($relType ==="r_patient"){
      			$mov['cast_patient'][] =$Mot2;

      		} else
      		if ($relType ==="r_lieu"){
      			$mov['cast_lieu'][] =$Mot2;

      		} else
      		if ($relType ==="r_instr"){
      			$mov['cast_instr'][] =$Mot2;

      		} else
      		if ($relType ==="r_carac"){
      			$mov['cast_carac'][] =$Mot2;

      		} else
      		if ($relType ==="r_data"){
      			$mov['cast_data'][] =$Mot2;

      		} else
      		if ($relType ==="r_lemma"){
      			$mov['cast_lemma'][] =$Mot2;

      		} else
      		if ($relType ==="r_magn"){
      			$mov['cast_magn'][] =$Mot2;

      		} else
      		if ($relType ==="r_antimagn"){
      			$mov['cast_antimagn'][] =$Mot2;

      		} else
      		if ($relType ==="r_family"){
      			$mov['cast_family'][] =$Mot2;

      		} else
      		if ($relType ==="r_carac_1"){
      			$mov['cast_carac_1'][] =$Mot2;

      		} else
      		if ($relType ==="r_agent_1"){
      				$mov['cast_agent_1'][] =$Mot2;

      		} else
      		if ($relType ==="r_instr_1"){
      			$mov['cast_instr_1'][] =$Mot2;

      		} else
      		if ($relType ==="r_patient_1"){
      			$mov['cast_patient_1'][] =$Mot2;

      		} else
      		if ($relType ==="r_domain_1"){
      			$mov['cast_domain_1'][] =$Mot2;

      		} else
      		if ($relType ==="r_lieu_1"){
      			$mov['cast_lieu_1'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_pred"){
      			$mov['cast_chunk_pred'][] =$Mot2;

      		} else
      		if ($relType ==="r_lieu_action"){
      			$mov['cast_lieu_action'][] =$Mot2;

      		} else
      		if ($relType ==="r_action_lieu"){
      			$mov['cast_action_lieu'][] =$Mot2;

      		} else
      		if ($relType ==="r_sentiment"){
      			$mov['cast_sentiment'][] =$Mot2;

      		} else
      		if ($relType ==="r_error"){
      			$mov['cast_error'][] =$Mot2;

      		} else
      		if ($relType ==="r_manner"){
      			$mov['cast_manner'][] =$Mot2;

      		} else
      		if ($relType ==="r_meaning"){
      			$mov['cast_meaning'][] =$Mot2;

      		} else
      		if ($relType ==="r_infopot"){
      			$mov['cast_infopot'][] =$Mot2;

      		} else
      		if ($relType ==="r_telic_role"){
      			$mov['cast_telic_role'][] =$Mot2;

      		} else
      		if ($relType ==="r_agentif_role"){
      			$mov['cast_agentif_role'][] =$Mot2;

      		} else
      		if ($relType ==="r_verbe_action"){
      			$mov['cast_verbe_action'][] =$Mot2;

      		} else
      		if ($relType ==="r_action_verbe"){
      			$mov['cast_action_verbe'][] =$Mot2;

      		} else
      		if ($relType ==="r_conseq"){
      			$mov['cast_conseq'][] =$Mot2;

      		} else
      		if ($relType ==="r_causatif"){
      			$mov['cast_causatif'][] =$Mot2;

      		} else
      		if ($relType ==="r_adj_verbe"){
      			$mov['cast_adj_verbe'][] =$Mot2;

      		} else
      		if ($relType ==="r_verbe_adj"){
      			$mov['cast_verbe_adj'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_sujet"){
      			$mov['cast_chunk_sujet'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_objet"){
      			$mov['cast_chunk_objet'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_loc"){
      			$mov['cast_chunk_loc'][] =$Mot2;

      		} else
      		if ($relType ==="r_chunk_instr"){
      			$mov['cast_chunk_instr'][] =$Mot2;

      		} else
      		if ($relType ==="r_time"){
      			$mov['cast_time'][] =$Mot2;

      		} else
      		if ($relType ==="r_object_mater"){
      			$mov['cast_object_mater'][] =$Mot2;

      		} else
      		if ($relType ==="r_mater_object"){
      			$mov['cast_mater_object'][] =$Mot2;

      		} else
      		if ($relType ==="r_successeur_time"){
      			$mov['cast_successeur_time'][] =$Mot2;

      		} else
      		if ($relType ==="r_make"){
      			$mov['cast_make'][] =$Mot2;

      		} else
      		if ($relType ==="r_product_of"){
      			$mov['cast_product_of'][] =$Mot2;

      		} else
      		if ($relType ==="r_against"){
      			$mov['cast_against'][] =$Mot2;

      		} else
      		if ($relType ==="r_against_1"){
      			$mov['cast_against_1'][] =$Mot2;

      		} else
      		if ($relType ==="r_implication"){
      			$mov['cast_implication'][] =$Mot2;

      		} else
      		if ($relType ==="r_quantificateur"){
      			$mov['cast_quantificateur'][] =$Mot2;

      		} else
      		if ($relType ==="r_masc"){
      			$mov['cast_masc'][] =$Mot2;
      		} else
      		if ($relType ==="r_fem"){
      			$mov['cast_fem'][] =$Mot2;
      		} else
      		if ($relType ==="r_equiv"){
      			$mov['cast_equiv'][] =$Mot2;
      		} else
      		if ($relType ==="r_manner_1"){
      			$mov['cast_manner_1'][] =$Mot2;
      		} else
      		if ($relType ==="r_agentive_implication"){
      			$mov['cast_agentive_implication'][] =$Mot2;
      		} else
      		if ($relType ==="r_instance"){
      			$mov['cast_instance'][] =$Mot2;
      		} else
      		if ($relType ==="r_verb_real"){
      			$mov['cast_verb_real'][] =$Mot2;
      		} else
      		if ($relType ==="r_chunk_head"){
      			$mov['cast_chunk_head'][] =$Mot2;
      		} else
      		if ($relType ==="r_similar"){
      			$mov['cast_similar'][] =$Mot2;
      		} else
      		if ($relType ==="r_set_item"){
      			$mov['cast_set_item'][] =$Mot2;
      		} else
      		if ($relType ==="r_item_set"){
      			$mov['cast_item_set'][] =$Mot2;
      		} else
      		if ($relType ==="r_processus_agent"){
      			$mov['cast_processus_agent'][] =$Mot2;
      		} else
      		if ($relType ==="r_variante"){
      			$mov['cast_variante'][] =$Mot2;
      		} else
      		if ($relType ==="r_syn_strict"){
      			$mov['cast_syn_strict'][] =$Mot2;
      		} else
      		if ($relType ==="r_is_smaller_than"){
      			$mov['cast_is_smaller_than'][] =$Mot2;
      		} else
      		if ($relType ==="r_is_bigger_than"){
      			$mov['cast_is_bigger_than'][] =$Mot2;
      		} else
      		if ($relType ==="r_accomp"){
      			$mov['cast_accomp'][] =$Mot2;
      		} else
      		if ($relType ==="r_processus_patient"){
      			$mov['cast_processus_patient'][] =$Mot2;
      		} else
      		if ($relType ==="r_verb_ppas"){
      			$mov['cast_verb_ppas'][] =$Mot2;
      		} else
      		if ($relType ==="r_cohypo"){
      			$mov['cast_cohypo'][] =$Mot2;
      		} else
      		if ($relType ==="r_der_morpho"){
      			$mov['cast_der_morpho'][] =$Mot2;
      		} else
      		if ($relType ==="r_has_auteur"){
      			$mov['cast_has_auteur'][] =$Mot2;
      		} else
      		if ($relType ==="r_has_personnage"){
      			$mov['cast_has_personnage'][] =$Mot2;
      		} else
      		if ($relType ==="r_can_eat"){
      			$mov['cast_can_eat'][] =$Mot2;
      		} else
      		if ($relType ==="r_has_actors"){
      			$mov['cast_has_actors'][] =$Mot2;
      		} else
      		if ($relType ==="r_deplac_mode"){
      			$mov['cast_deplac_mode'][] =$Mot2;
      		} else
      		if ($relType ==="r_has_interpret"){

      			$mov['cast_has_interpret'][] =$Mot2;
      		} else
      		if ($relType ==="r_color"){
      			$mov['cast_color'][] =$Mot2;
      		} else
      		if ($relType ==="r_cible"){
      			$mov['cast_cible'][] =$Mot2;
      		} else
      		if ($relType ==="r_symptomes"){
      			$mov['cast_symptomes'][] =$Mot2;
      		} else
      		if ($relType ==="r_predecesseur_time"){
      			$mov['cast_predecesseur_time'][] =$Mot2;
      		} else
      		if ($relType ==="r_diagnostique"){
      			$mov['cast_diagnostique'][] =$Mot2;
      		} else
      		if ($relType ==="r_predecesseur_space"){
      			$mov['cast_predecesseur_space'][] =$Mot2;
      		} else
      		if ($relType ==="r_successeur_space"){
      			$mov['cast_successeur_space'][] =$Mot2;
      		} else
      		if ($relType ==="r_social_tie"){
      			$mov['cast_social_tie'][] =$Mot2;
      		} else
      		if ($relType ==="r_tributary"){
      			$mov['cast_tributary'][] =$Mot2;
      		} else
      		if ($relType ==="r_sentiment_1"){
      			$mov['cast_sentiment_1'][] =$Mot2;
      		} else
      		if ($relType ==="r_linked_with"){
      			$mov['cast_linked_with'][] =$Mot2;
      		} else
      		if ($relType ==="r_foncteur"){
      			$mov['cast_foncteur'][] =$Mot2;
      		} else
      		if ($relType ==="r_comparison"){
      			$mov['cast_comparison'][] =$Mot2;
      		} else
      		if ($relType ==="r_but"){
      			$mov['cast_but'][] =$Mot2;
      		} else
      		if ($relType ==="r_but_1"){

      		$mov['cast_but_1'][] =$Mot2;
      		} else
      		if ($relType ==="r_own"){
      			$mov['cast_own'][] =$Mot2;
      		} else
      		if ($relType ==="r_own_1"){
      			$mov['cast_own_1'][] =$Mot2;
      		} else
      		if ($relType ==="r_verb_aux"){
      			$mov['cast_verb_aux'][] =$Mot2;
      		} else
      		if ($relType ==="r_predecesseur_logic"){
      			$mov['cast_predecesseur_logic'][] =$Mot2;
      		} else
      		if ($relType ==="r_successeur_logic"){
      			$mov['cast_successeur_logic'][] =$Mot2;
      		} else
      		if ($relType ==="r_isa_incompatible"){
      			$mov['cast_isa_incompatible'][] =$Mot2;
      		} else
      		if ($relType ==="r_compl_agent"){
      			$mov['cast_compl_agent'][] =$Mot2;
      		} else
      		if ($relType ==="r_beneficiaire"){
      			$mov['cast_beneficiaire'][] =$Mot2;
      		} else
      		if ($relType ==="r_descend_de"){
      			$mov['cast_descend_de'][] =$Mot2;
      		} else
      		if ($relType ==="r_domain_subst"){
      			$mov['cast_domain_subst'][] =$Mot2;
      		} else
      		if ($relType ==="r_prop"){
      			$mov['cast_prop'][] =$Mot2;
      		} else
      		if ($relType ==="r_activ_voice"){
      			$mov['cast_activ_voice'][] =$Mot2;
      		} else
      		if ($relType ==="r_make_use_of"){
      			$mov['cast_make_use_of'][] =$Mot2;
      		} else
      		if ($relType ==="r_is_used_by"){
      			$mov['cast_is_used_by'][] =$Mot2;
      		} else
      		if ($relType ==="r_adj_nomprop"){
      			$mov['cast_adj_nomprop'][] =$Mot2;
      		} else
      		if ($relType ==="r_nomprop_adj"){
      			$mov['cast_nomprop_adj'][] =$Mot2;
      		} else
      		if ($relType ==="r_adj_adv"){
      			$mov['cast_adj_adv'][] =$Mot2;
      		} else
      		if ($relType ==="r_adv_adj"){
      			$mov['cast_adv_adj'][] =$Mot2;
      		} else
      		if ($relType ==="r_link"){
      			$mov['cast_link'][] =$Mot2;
      		} else
      		if ($relType ==="r_cooccurrence"){
      			$mov['cast_cooccurrence'][] =$Mot2;
      		} else
      		if ($relType ==="r_aki"){
      			$mov['cast_aki'][] =$Mot2;
      		} else
      		if ($relType ==="r_wiki"){
      			$mov['cast_wiki'][] =$Mot2;
      		} else
      		if ($relType ==="r_annotation_exception"){
      			$mov['cast_annotation_exception'][] =$Mot2;
      		} else
      		if ($relType ==="r_annotation"){
      			$mov['cast_annotation'][] =$Mot2;
      		} else
      		if ($relType ==="r_inhib"){
      			$mov['cast_inhib'][] =$Mot2;
      		} else
      		if ($relType ==="r_prev"){
      			$mov['cast_prev'][] =$Mot2;
      		} else
      		if ($relType ==="r_succ"){
      			$mov['cast_succ'][] =$Mot2;
      		} else
      		if ($relType ==="r_termgroup"){
      			$mov['cast_termgroup'][] =$Mot2;
      		} else
      		if ($relType ==="r_learning_model"){
      		$mov['cast_learning_model'][] =$Mot2;
              }
          }
      }

      $content = $this->get('templating')->render('DICDicoBundle:Search:relation.html.twig', array('result' =>$mov));
      return new Response($content);




    /*
      $response = new JsonResponse();
      $response->setData($mov);

      return $response;*/
    }
    function verif_alpha($str){
        preg_match("/([^A-Za-z])/",$str,$result);
    //On cherche tt les caractères autre que [A-z]
        if(!empty($result)){//si on trouve des caractère autre que A-z
    		return false;
        }
    	else
        return true;
    }

    function verif_alphaNum($str){
        preg_match("/([^A-Za-z0-9])/",$str,$result);
    //On cherche tt les caractères autre que [A-Za-z] ou [0-9]
        if(!empty($result)){//si on trouve des caractère autre que A-Za-z ou 0-9
    		return false;
        }
    	else
        return true;
    }
}
