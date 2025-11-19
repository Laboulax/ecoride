<?php

require_once 'code/model/SearchModel.php';

class SearchController extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new SearchModel();
        $this->formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    }

    public function handle()
    {


        $ville = "";


        if (isset($_POST['ville']) && !empty($_POST['ville'])) {
            $ville = htmlspecialchars($_POST['ville']);
        } else {
            $ville = htmlspecialchars($_POST['search-a'] ?? '');
        }

        $search_trajet = "";

        $nb_text_traj = '';

        if (isset($_POST['go'])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $this->checkCsrf();
                $depart = $_POST['search-d'];
                $arrivee = $_POST['search-a'];
                $nbplace = $_POST['voyageurs'];
                $filtre_duree_heure = $_POST['duree'] ?? null;
                $prix = $_POST['prix'] ?? null;;
                $energie = $_POST['energie'] ?? null;
                $filtre_duree = null;
                $note_min = $_POST['note_min'] ?? null;

                if ($filtre_duree_heure !== null && $filtre_duree_heure !== '') {
                    $str = str_replace(',', '.', trim($filtre_duree_heure));
                    if (is_numeric($str)) {
                        $filtre_duree = floatval($str) * 3600;
                        $filtre_duree = (int) round($filtre_duree);
                    } else {
                        $filtre_duree = null;
                        $_SESSION['flash_message'] = "Format durée invalide, utilisez un nombre (ex: 2 ou 1.5).";
                    }
                }
                if ($depart != "" && $arrivee != "") {
                    $trajet_user = $this->model->searchTrajet($depart, $arrivee, $nbplace, $filtre_duree, $prix, $energie, $note_min);


                    $nb_traj = count($trajet_user);

                    if ($nb_traj > 1) {
                        $nb_text_traj = " Il y a " . $nb_traj . " covoiturages correspondant à vos critères";
                    } elseif ($nb_traj == 1) {
                        $nb_text_traj = " Il y a " . $nb_traj . " covoiturage correspondant à vos critères";
                    } else {
                        $nb_text_traj = "Il n'y a malheureusement aucun trajet correspondant à vos critères";
                    }



                    foreach ($trajet_user as $value) {

                        $timestamp = strtotime($value['date_depart']);
                        $dateDepart = ucfirst($this->formatter->format($timestamp));

                        $timestamp2 = strtotime($value['date_arrivee']);
                        $dateArrivee = ucfirst($this->formatter->format($timestamp2));

                        $heureDepart = date('H\hi', strtotime($value['heure_depart']));
                        $heureArrivee = date('H\hi', strtotime($value['heure_arrivee']));
                        $duree = date('H\hi', strtotime($value['hdif']));

                        $info_user = $this->model->getUserById($value['utilisateur_id']);
                        $photo = "data:image/jpeg;base64," . base64_encode($info_user['photo']);

                        $noNote = "Pas de note";
                        $info_note = $this->model->getNoteById($value['utilisateur_id']);

                        if ($info_note != NULL) {
                            $note = number_format((float)$info_note['note'], 1);
                            $nbNote = htmlspecialchars((int)$info_note['nb_note']);
                            $noNote = $note . "/5 <span class='text-muted'>(" . $nbNote . " avis)</span>";
                        }

                        $search_trajet .=

                            "<tr>
            <td style='display:flex; align-items:center; gap:10px;'>
        <img src='" . $photo . "' alt='Photo utilisateur' style='width:40px; height:40px; object-fit:cover; border-radius:50%;'>
        <form action='index.php?avisUser' method='post' style='display:inline; margin:0; padding:0;'>
        <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='utilisateur_id' value='" . $value['utilisateur_id'] . "'>
            <button type='submit' style='background:none; border:none; padding:0; margin:0; font-size:14px; color:#007bff; cursor:pointer; text-decoration:underline;'>
                " . htmlspecialchars($value['Prenom']) . "
            </button>
        </form>
    </td>
            <td> " . $noNote . " </td>
            <td> " . $dateDepart . " </td>
            <td> " . $dateArrivee . " </td>
            <td> " . $heureDepart . " </td>
            <td> " . $heureArrivee . " </td>
            <td> " . $duree . " </td>
            <td> " . $value['prix_personne'] . " €</td>
            <td> " . $value['energie'] . " </td>
            <td> " . $value['nb_place'] . " </td>

            <td>
                <form method='post' action='index.php?book' class='no-main-style'>
                <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
                <input type='hidden' name='utilisateur_id' value='" . htmlspecialchars($value['utilisateur_id'], ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='pseudo' value='" . htmlspecialchars($value['Prenom'], ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='depart' value='" . htmlspecialchars($depart, ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='arrivee' value='" . htmlspecialchars($arrivee, ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='date_depart' value='" . htmlspecialchars($dateDepart, ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='heure_depart' value='" . htmlspecialchars($heureDepart, ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='date_arrivee' value='" . htmlspecialchars($dateArrivee, ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='heure_arrivee' value='" . htmlspecialchars($heureArrivee, ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='prix' value='" . htmlspecialchars($value['prix_personne'], ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='places' value='" . htmlspecialchars($value['nb_place'], ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($value['covoiturage_id'], ENT_QUOTES, "UTF-8") . "'>
                    <input type='hidden' name='nb_voyageurs' value='" . htmlspecialchars($_POST['voyageurs'] ?? "1", ENT_QUOTES, "UTF-8") . "'>

                    <button type='submit' class='btn-reserver' >Réserver</button>
                </form>
                
            </td>
            </tr>";
                    }
                }
            }
        }

        $this->genViews();
        require_once 'code/views/search.php';
        require_once 'code/views/footer.php';
    }
}
