<?php

namespace DIC\DicoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HireVoice\Neo4j\Annotation as OGM;

/**
 * Relations
 *
 * @OGM\Entity
 */
class Relations
{
    /**
     * @OGM\Id
     */
    private $id;

    /**
     * @OGM\rel
     */
    private $listRelations;


    public function getId()
    {
        return $this->id;
    }


    public function getRelations()
  	{
      $this->listRelations = array(
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
      return $this->listRelations;
    }
}
