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

    public function relationsAction()
    {
      $rels = array(
        array('name' =>'idée associée', 'definition' => 'Il est demandé d"énumérer les termes les plus étroitement associés au mot cible... Ce mot vous fait penser à quoi ?'),
        array('name' =>'raffinement sémantique', 'definition' => 'Raffinement sémantique vers un usage particulier du terme source'),
        array('name' =>'raffinement morphologique', 'definition' => 'Raffinement morphologique vers un usage particulier du terme source'),
        array('name' =>'domaine', 'definition' => 'Il est demandé de fournir des domaines relatifs au mot cible. Par exemple, pour "corner", on pourra donner les domaines "football" ou "sport".'),
        array('name' =>'POS', 'definition' => 'Partie du discours (Nom, Verbe, Adjectif, Adverbe, etc.)'),
        array('name' =>'synonyme', 'definition' => 'Il est demandé d"énumérer les synonymes ou quasi-synonymes de ce terme.'),
        array('name' =>'générique', 'definition' => 'Il est demandé d"énumérer les GENERIQUES/hyperonymes du terme. Par exemple, "animal" et "mammifère" sont des génériques de "chat".'),
        array('name' =>'contraire', 'definition' => 'Il est demandé d"énumérer des contraires du terme. Par exemple, "chaud" est le contraire de "froid".'),
        array('name' =>'spécifique', 'definition' => 'Il est demandé d"énumérer des SPECIFIQUES/hyponymes du terme. Par exemple, "mouche", "abeille", "guêpe" pour "insecte".'),
        array('name' =>'partie', 'definition' => 'Il faut donner des PARTIES/constituants/éléments (a pour méronymes) du mot cible. Par exemple, "voiture" a comme parties : "porte", "roue", "moteur", ...'),
        array('name' =>'tout', 'definition' => 'Il est démandé d"énumérer des "TOUT" (a pour holonymes)  de l"objet en question. Pour "main",  on aura "bras", "corps", "personne", etc... Le tout est aussi l"ensemble comme "classe" pour "élève".'),
        array('name' =>'locution', 'definition' => 'A partir d"un terme, il est demandé d"énumérer les locutions, expression ou mots composés en rapport avec ce terme. Par exemple, pour "moulin",  ou pourra avoir "moulin à vent", "moulin à eau", "moulin à café". Pour "vendre",  on pourra avoir "vendre la peau de l"ours avant de l"avoir tué", "vendre à perte", etc..'),
        array('name' =>'action>agent', 'definition' => 'L"agent (qu"on appelle aussi le sujet) est l"entité qui effectue l"action, OU la subit pour des formes passives ou des verbes d"état. Par exemple, dans - Le chat mange la souris -, l"agent est le chat. Des agents typiques de "courir" peuvent être "sportif", "enfant",...'),
        array('name' =>'action>patient', 'definition' => 'Le patient (qu"on appelle aussi l"objet) est l"entité qui subit l"action. Par exemple dans - Le chat mange la souris -, le patient est la souris. Des patients typiques de manger peuvent être "viande", "légume", "pain",  ...'),
        array('name' =>'', 'definition' => '(interne) potentiel de relation'),
        array('name' =>'chose>lieu', 'definition' => 'Il est demandé d"énumérer les LIEUX typiques où peut se trouver le terme/objet en question.'),
        array('name' =>'action>instrument', 'definition' => 'L"instrument est l"objet avec lequel on fait l"action. Dans - Il mange sa salade avec une fourchette -, fourchette est l"instrument. Des instruments typiques de "tuer" peuvent être "arme", "pistolet", "poison", ...'),
        array('name' =>'caractéristique', 'definition' => 'Pour un terme donné, souvent un objet, il est demandé d"en énumérer les CARACtéristiques (adjectifs) possibles/typiques. Par exemple, "liquide", "froide", "chaude",  pour "eau".'),
        array('name' =>'r_data', 'definition' => 'Informations diverses'),
        array('name' =>'r_lemma', 'definition' => 'Le lemme'),
        array('name' =>'magn', 'definition' => 'La magnification ou amplification, par exemple - forte fièvre - ou - fièvre de cheval - pour fièvre. Ou encore - amour fou - pour amour, - peur bleue - pour peur.'),
        array('name' =>'antimagn', 'definition' => 'L"inverse de la magnification, par exemple - bruine - pour pluie.'),
        array('name' =>'famille', 'definition' => 'Des mots de la même famille lexicale sont demandés (dérivation morphologique, par exemple). Par exemple, pour "lait" on pourrait mettre "laitier", "laitage", "laiterie",  etc.'),
        array('name' =>'caractéristique-1', 'definition' => 'Quels sont les objets (des noms) possédant typiquement/possiblement la caractérisque suivante ? Par exemple, "soleil", "feu",  pour "chaud".'),
        array('name' =>'agent typique-1', 'definition' => 'Que peut faire ce SUJET ? (par exemple chat => miauler, griffer, etc.)'),
        array('name' =>'instrument>action', 'definition' => 'L"instrument est l"objet avec lequel on fait l"action. Dans - Il mange sa salade avec une fourchette -, fourchette est l"instrument. On demande ici, ce qu"on peut faire avec un instrument donné...'),
        array('name' =>'patient-1', 'definition' => '(inverse de r_patient) Que peut-on faire à cet OBJET. Pour "pomme", on pourrait avoir "manger", "croquer", couper", "éplucher",  etc.'),
        array('name' =>'domaine-1', 'definition' => 'inverse de r_domain : à un domaine, on associe des termes'),
        array('name' =>'lieu>chose', 'definition' => 'A partir d"un lieu, il est demandé d"énumérer ce qui peut typiquement s"y trouver.'),
        array('name' =>'predicat', 'definition' => '(interne) d"un prédicat quel chunk ?'),
        array('name' =>'lieu>action', 'definition' => 'A partir d"un lieu, énumérer les actions typiques possibles dans ce lieu.'),
        array('name' =>'action>lieu', 'definition' => 'A partir d"une action (un verbe), énumérer les lieux typiques possibles où peut être réalisée cette action.'),
        array('name' =>'sentiment', 'definition' => 'Pour un terme donné, évoquer des mots liés à des SENTIMENTS ou des EMOTIONS que vous pourriez associer à ce terme. Par exemple, la joie, le plaisir, le dégoût, la peur, la haine, l"amour, l"indifférence, l"envie, avoir peur, horrible, etc.'),
        array('name' =>'erreur', 'definition' => 'lien d"erreur'),
        array('name' =>'manière', 'definition' => 'De quelles MANIERES peut être effectuée l"action (le verbe) proposée. Il s"agira d"un adverbe ou d"un équivalent comme une locution adverbiale, par exemple : "rapidement", "sur le pouce", "goulûment", "salement" ... pour "manger".'),
        array('name' =>'sens/signification', 'definition' => 'Quels SENS/SIGNIFICATIONS pouvez vous donner au terme proposé. Il s"agira de termes évoquant chacun des sens possibles, par exemple : "forces de l"ordre", "contrat d"assurance", "police typographique", ... pour "police".'),
        array('name' =>'information potentielle', 'definition' => 'Information sémantique potentielle'),
        array('name' =>'rôle télique', 'definition' => 'Le rôle télique indique le but ou la fonction du nom ou du verbe. Par exemple, couper pour couteau, scier pour scie, etc. C"est le rôle qu"on lui destine communément pour un artéfact, ou bien un rôle qu"on peut attribuer à un objet naturel (réchauffer, éclairer pour soleil).'),
        array('name' =>'rôle agentif', 'definition' => 'De quelle(s)  manière(s)  peut être CRÉE/CONSTRUIT le terme suivant. On demande des verbes transitifs (le terme en est un complément d"objet) qui DONNENT NAISSANCE à l"entité désignée par le terme,  par exemple, "construire" pour "maison", "rédiger"/"imprimer" pour "livre" ou "lettre".'),
        array('name' =>'verbe>action', 'definition' => 'du verbe vers l"action. Par exemple, construire -> construction , jardiner -> jardinage . C"est un terme directement dérivé (ayant la même racine). Applicable que pour un verbe et inverse de la relation 40 (action vers verbe).'),
        array('name' =>'action>verbe', 'definition' => 'de l"action vers le verbe. Par exemple, construction -> construire, jardinage -> jardiner. C"est un terme directement dérivé (ayant la même racine). Applicable que pour un nom et inverse de la relation 39 (verbe vers action).'),
        array('name' =>'cause', 'definition' => 'B (que vous devez donner) est une CAUSE possible de A. A et B sont des verbes ou des noms.  Exemples : se blesser -> tomber ; vol -> pauvreté ; incendie -> négligence ; mort --> maladie ; etc.'),
        array('name' =>'conséquence', 'definition' => 'B (que vous devez donner) est une CONSEQUENCE possible de A. A et B sont des verbes ou des noms.  Exemples : tomber -> se blesser ; faim -> voler ; allumer -> incendie ; négligence --> accident ; etc.'),
        array('name' =>'adj>verbe', 'definition' => 'Pour un adjectif de potentialité/possibilité, son verbe correspondant. Par exemple pour "lavable" -> "laver"'),
        array('name' =>'verbe>adj', 'definition' => 'Pour un verbe, son adjectif de potentialité/possibilité correspondant. Par exemple pour "laver" -> "lavable"'),
        array('name' =>'r_chunk_sujet', 'definition' => '(interne)'),
        array('name' =>'r_chunk_objet', 'definition' => '(interne)'),
        array('name' =>'r_chunk_loc', 'definition' => '(interne)'),
        array('name' =>'r_chunk_instr', 'definition' => '(interne)'),
        array('name' =>'r_aki', 'definition' => '(AKI) equivalent pour AKI de l"association libre'),
        array('name' =>'action>temps', 'definition' => 'Donner une valeur temporelle -quel moment- peut-on associer au terme indiqué (par exemple "dormir" -> nuit, "bronzer" -> été, "fatigue" -> "soir")'),
        array('name' =>'r_prev', 'definition' => '(interne)'),
        array('name' =>'r_succ', 'definition' => '(interne)'),
        array('name' =>'r_inhib', 'definition' => 'relation d"inhibition, le terme inhibe les termes suivants... ce terme a tendance à exclure le terme associé.'),
        array('name' =>'objet>matiere', 'definition' => 'Quel est la ou les MATIERE/SUBSTANCE pouvant composer l"objet qui suit. Par exemple, "bois" pour "poutre".'),
        array('name' =>'matière>objet', 'definition' => 'Quel est la ou les CHOSES qui sont composés de la MATIERE/SUBSTANCE qui suit (exemple "bois" -> poutre, table, ...).'),
        array('name' =>'successeur', 'definition' => 'Qu"est ce qui peut SUIVRE temporellement (par exemple Noêl -> jour de l"an, guerre -> paix, jour -> nuit,  pluie -> beau temps, repas -> sieste, etc) le terme suivant :'),
        array('name' =>'produit', 'definition' => 'Que peut PRODUIRE le terme ? (par exemple abeille -> miel, usine -> voiture, agriculteur -> blé,  moteur -> gaz carbonique ...)'),
        array('name' =>'est le produit de', 'definition' => 'Le terme est le RESULTAT/PRODUIT de qui/quoi ?'),
        array('name' =>'s"oppose à', 'definition' => 'A quoi le terme suivant S"OPPOSE/COMBAT/EMPECHE ? Par exemple, un médicament s"oppose à la maladie.'),
        array('name' =>'a comme opposition', 'definition' => 'Inverse de r_against (s"oppose à) - a comme opposition active (S"OPPOSE/COMBAT/EMPECHE). Par exemple, une bactérie à comme opposition antibiotique.'),
        array('name' =>'implication', 'definition' => 'Qu"est-ce que le terme implique logiquement ? Par exemple : ronfler implique dormir, courir implique se déplacer, câlin implique contact physique. (attention ce n"est pas la cause ni le but...)'),
        array('name' =>'quantificateur', 'definition' => 'Quantificateur(s) typique(s) pour le terme,  indiquant une quantité. Par exemples, sucre -> grain, morceau - sel -> grain, pincée - herbe -> brin, touffe - ...'),
        array('name' =>'équivalent masc', 'definition' => 'L"équivalent masculin du terme : lionne --> lion.'),
        array('name' =>'équivalent fem', 'definition' => 'L"équivalent féminin du terme : lion --> lionne.'),
        array('name' =>'équivalent', 'definition' => 'Termes strictement équivalent/identique : acronymes et sigles (PS -> parti socialiste), apocopes (ciné -> cinéma), entités nommées (Louis XIV -> Le roi soleil), etc. (attention il ne s"agit pas de synonyme)'),
        array('name' =>'maniere-1', 'definition' => 'Quelles ACTIONS (verbes) peut-on effectuer de cette manière ? Par exemple, rapidement -> courir, manger, ...'),
        array('name' =>'implication agentive', 'definition' => 'Les verbes ou actions qui sont impliqués dans la création de l"objet. Par exemple pour "construire" un livre, il faut, imprimer, relier, brocher, etc. Il s"agit des étapes nécessaires à la réalisation du rôle agentif.'),
        array('name' =>'instance', 'definition' => 'Une instance d"un "type" est un individu particulier de ce type. Il s"agit d"une entité nommée (personne, lieu, organisation, etc) - par exemple, "Jolly Jumper" est une instance de "cheval", "Titanic" en est une de "transatlantique".'),
        array('name' =>'verbe>real', 'definition' => 'Pour un verbe, celui qui réalise l"action (par dérivation morphologique). Par exemple, chasser -> chasseur, naviguer -> navigateur.'),
        array('name' =>'r_termgroup', 'definition' => "),
        array('name' =>'r_chunk_head', 'definition' => "),
        array('name' =>'similaire', 'definition' => 'Similaire/ressemble à ; par exemple le congre est similaire à une anguille, ...'),
        array('name' =>'ensemble>item', 'definition' => 'Quel est l"ELEMENT qui compose l"ENSEMBLE qui suit (par exemple, un essaim est composé d"aveilles)'),
        array('name' =>'item>ensemble', 'definition' => 'Quel est l"ENSEMBLE qui est composé de l"ELEMENT qui suit (par exemple, un essaim est composé d"aveilles)'),
        array('name' =>'processus>agent', 'definition' => 'Quel est l"acteur de ce processus/événement ? Par exemple,  "nettoyage" peut avoir comme acteur "technicien de surface".'),
        array('name' =>'variante', 'definition' => 'Variantes du termes cible. Par exemple, yaourt, yahourt, ou encore évènement, événement.'),
        array('name' =>'a comme personnages', 'definition' => 'Quels sont les personnages présents dans l"oeuvre qui suit ?'),
        array('name' =>'a comme auteur', 'definition' => 'Quel est l"auteur de l"oeuvre suivante ?'),
        array('name' =>'se nourrit de', 'definition' => 'De quoi peut se nourir l"animal suivant ?'),
        array('name' =>'r_syn_strict', 'definition' => 'Termes strictement substituables, pour des termes hors du domaine général, et pour la plupart des noms (exemple : endométriose intra-utérine --> adénomyose)'),
        array('name' =>'a comme acteurs', 'definition' => 'A comme acteurs (pour un film ou similaire).'),
        array('name' =>'mode de déplacement', 'definition' => 'Mode de déplacement'),
        array('name' =>'dérivation morphologique', 'definition' => 'Des termes dériviés morphologiquement sont demandés). Par exemple, pour "lait" on pourrait mettre "laitier", "laitage",  "laiterie", etc. (mais pas "lactose"). Pour "jardin", on mettra "jardinier", "jardinage", "jardiner",  etc. '),
        array('name' =>'a comme interprètes', 'definition' => "),
        array('name' =>'couleur', 'definition' => 'A comme couleur(s)...'),
        array('name' =>'r_learning_model', 'definition' => "),
        array('name' =>'r_wiki', 'definition' => 'Associations issues de wikipedia...'),
        array('name' =>'r_annotation', 'definition' => "),
        array('name' =>'a comme cible', 'definition' => 'Cible de la maladie : myxomatose => lapin, rougeole => enfant, ...'),
        array('name' =>'a comme symptomes', 'definition' => 'Symptomes de la maladie : myxomatose => yeux rouges, rougeole => boutons, ...'),
        array('name' =>'r_annotation_exception', 'definition' => "),
        array('name' =>'prédécesseur', 'definition' => 'Qu"est ce qui peut PRECEDER temporellement (par exemple -  inverse de successeur) le terme suivant :'),
        array('name' =>'diagnostique', 'definition' => 'Diagnostique pour la maladie : diabète => prise de sang, rougeole => examen clinique, ...'),
        array('name' =>'est plus petit que', 'definition' => 'Qu"est-ce qui est physiquement plus gros que... (la comparaison doit être pertinente)'),
        array('name' =>'est plus gros que', 'definition' => 'Qu"est-ce qui est physiquement moins gros que... (la comparaison doit être pertinente)'),
        array('name' =>'accompagne', 'definition' => 'Est souvent accompagné de... Par exemple : Astérix et Obelix, le pain et le fromage, les fraises et la chantilly.'),
        array('name' =>'prédécesseur', 'definition' => 'Qu"est ce qui peut PRECEDER spatialement (par exemple -  inverse de successeur spatial) le terme suivant :'),
        array('name' =>'successeur', 'definition' => 'Qu"est ce qui peut SUIVRE spatialement (par exemple Locomotive à vapeur -> tender, wagon etc.) le terme suivant :'),
        array('name' =>'action>bénéficiaire', 'definition' => 'Le bénéficiaire est l"entité qui tire bénéfice/préjudice de l"action (un complément d"objet indirect introduit par "à", "pour", ...). Par exemple dans - La sorcière donne une pomme à Blanche Neige -, la bénéficiaire est Blanche Neige ... enfin, bref, vous avez compris l"idée.'),
        array('name' =>'descend de', 'definition' => 'Descend de (évolution)...'),
        array('name' =>'relation sociale/famille', 'definition' => 'Relation sociale entre les individus...'),
        array('name' =>'r_tributary', 'definition' => ''),
        array('name' =>'sentiment-1', 'definition' => 'Pour un SENTIMENT ou EMOTION donné, il est demandé d’énumérer les termes que vous pourriez associer. Par exemple, pour "joie", on aurait "cadeau", "naissance","bonne nouvelle", etc.'),
        array('name' =>'linked-with', 'definition' => 'A quoi est-ce relié (un wagon est relié à un autre wagon ou à une locomotive) ?'),
        array('name' =>'domain_subst', 'definition' => 'Quels sont le ou les domaines de substitution pour ce terme quand il est utilisé comme domaine (par exemple, "muscle" => "anatomie du système musculaire")'),
        array('name' =>'propriété', 'definition' => 'Pour le terme donné, il faut indiquer les noms de propriétés pertinents (par exemple pour "voiture", le "prix", la "puissance", la "longueur", le "poids", etc. On ne met que des noms et pas des adjectifs).'),
        array('name' =>'r_foncteur', 'definition' => 'La fonction de ce terme par rapport à d"autres. Pour les prépositions notamment, "chez" => relation r_location. (demande un type de relation comme valeur)'),
        array('name' =>'r_comparison', 'definition' => ''),
        array('name' =>'r_but', 'definition' => 'But de l"action'),
        array('name' =>'processus>patient', 'definition' => 'Quel est le patient de ce processus/événement ? Par exemple,  "découpe" peut avoir comme patient "viande".'),
        array('name' =>'r_but-1', 'definition' => 'Quel sont les action ou verbe qui le terme cible comme but ?'),
        array('name' =>'pers>possession', 'definition' => 'Que POSSEDE le terme suivant ? (un soldat possède un fusil, une cavalière des bottes, ...)'),
        array('name' =>'possession>pers', 'definition' => 'Qui ou quoi POSSEDE le terme suivant ?'),
        array('name' =>'complément d"agent', 'definition' => 'Le complément d"agent est celui qui effectue l"action dans les formes passives. Par exemple, pour "être mangé", la souris est l"agent et le chat le complément d"agent.'),
        array('name' =>'voix active', 'definition' => 'Pour un verbe à la voix passive, sa voix active. Par exemple, pour "être mangé" on aura "manger".'),
        array('name' =>'r_cooccurrence', 'definition' => ''),
        array('name' =>'r_make_use_of', 'definition' => ''),
        array('name' =>'r_is_used_by', 'definition' => ''),
        array('name' =>'r_verb_ppas', 'definition' => 'Le participe passé (au masculin singulier) du verbe infinitif. Par exemple, pour manger => mangé'),
        array('name' =>'r_verb_aux', 'definition' => 'Auxiliaire utilisé pour ce verbe'),
        array('name' =>'co-hyponyme', 'definition' => 'Il est demandé d"énumérer les CO-HYPONYMES du terme. Par exemple, "chat" et "tigre" sont des co-hyponymes (de "félin").'),
        array('name' =>'adj>nomprop', 'definition' => 'Pour un adjectif, donner le nom de propriété correspondant. Par exemple, pour "friable" -> "friabilité"'),
        array('name' =>'nomprop>adj', 'definition' => 'Pour un nom de propriété, donner l"adjectif correspondant. Par exemple, pour "friabilité" -> "friable"'),
        array('name' =>'adj>adv', 'definition' => 'Pour un adjectif, donner l"adverbe correspondant. Par exemple, pour "rapide" -> "rapidement"'),
        array('name' =>'adv>adj', 'definition' => 'Pour un adverbe, donner l"adjectif correspondant. Par exemple, pour "rapidement" -> "rapide"'),
        array('name' =>'prédécesseur logique', 'definition' => 'Qu"est ce qui peut PRECEDER logiquement (par exemple : A précède B -  inverse de successeur logique) le terme suivant :'),
        array('name' =>'successeur logique', 'definition' => 'Qu"est ce qui peut SUIVRE logiquement (par exemple A -> B, C etc.) le terme suivant :'),
        array('name' =>'r_link', 'definition' => 'Lien vers une ressource externe (WordNet, RadLex, UMLS, Wikipedia, etc...)'),
        array('name' =>'r_isa-incompatible', 'definition' => 'Relation d"incompatibilité. Si A r_isa-incompatible B alors X ne peut pas être à la fois A et B ou alors X est polysémique. Par exemple, poisson r_isa-incompatible oiseau. Colin est à la fois un oiseau et un poisson, donc colin est polysémique.')
           );
      $content = $this->get('templating')->render('DICDicoBundle:Advert:relations.html.twig', array('relations' =>$rels));
      return new Response($content);
      

    }
    public function typesAction()
    {
      $content = $this->get('templating')->render('DICDicoBundle:Advert:types.html.twig');
      return new Response($content);
    }
}
