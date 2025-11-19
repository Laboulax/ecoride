<?php

require_once 'code/model/UserModel.php';




class ProfileController extends MainController
{

    public function __construct()
    {
        parent::__construct();

        $this->model = new UserModel();
        $this->formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    }

    public function handle()
    {



        $this->requireLogin();

        $utilisateur_id = $_SESSION['user']['id'];
        $this->dlImage($utilisateur_id);
        $info_user = $this->model->getUserById($utilisateur_id);

        if ($info_user != NULL && $info_user['utilisateur_id'] != false) {
            $nom = $info_user['Nom'];
            $prenom = $info_user['Prenom'];
            $email = $info_user['email'];
            $adress = $info_user['adress'];
            $pseudo = $info_user['pseudo'];


            if (!empty($info_user['photo'])) {
                $photo = "data:image/jpeg;base64," . base64_encode($info_user['photo']);
            } else {
                $photo = "img/iconsuser.png";
            }

            if (!empty($_SESSION['alert_success'])) {
                $message = addslashes($_SESSION['alert_success']);
                echo "<script>alert('{$message}');</script>";
                unset($_SESSION['alert_success']);
            }
        } else {
            header("index.php?login");
        }

        $etoiles_html = "";

        if ($_SESSION['user']['role'] === 'chauffeur') {
            $info_note = $this->model->getNoteById($utilisateur_id);
            if ($info_note != NULL) {
                $note = $info_note['note'];
                $nb_note = $info_note['nb_note'];
                $etoiles_html = $this->genererEtoiles($note, $nb_note);
            }
        }




        $this->addTrajet();

        $trajet_futur = "";

        $trajet_user = $this->model->futurTrajets($utilisateur_id);



        foreach ($trajet_user as $value) {

            $timestamp = strtotime($value['date_depart']);
            $dateDepart = ucfirst($this->formatter->format($timestamp));

            $heureDepart = date('H\hi', strtotime($value['heure_depart']));
            $heureArrivee = date('H\hi', strtotime($value['heure_arrivee']));
            $duree = date('H\hi', strtotime($value['hdif']));

            $trajet_futur .=

                "<tr>
            <td>" . htmlspecialchars($value['lieu_depart'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($value['lieu_arrivee'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($dateDepart, ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($heureDepart, ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($heureArrivee, ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($duree, ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars(($value['place_res'] ?? 0), ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($value['prix_personne'], ENT_QUOTES, 'UTF-8') . " €</td>
            <td>" . htmlspecialchars($value['nb_place'], ENT_QUOTES, 'UTF-8') . "</td>

            <td>";


            switch ($value['statut']) {

                case "EnCours":
                    $trajet_futur .=  "<form action='index.php?cancelBook' method='post' onsubmit=\"return confirm('Voulez vous valider la fin du trajet ?');\">
                    <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='covoiturage_id' value='" . htmlspecialchars($value['covoiturage_id'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='role' value='endTraj'>
            <button type='submit' class='cancel_book'>Terminer</button>
        </form><td></td>";
                    break;

                case "Termine":
                    $trajet_futur .= "Terminé<td></td>";

                    break;


                case "":
                default:
                    $trajet_futur .=   "
                    
                    <form action='index.php?cancelBook' method='post' onsubmit=\"return confirm('Voulez-vous vraiment annuler ce trajet ?');\">
                    <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='covoiturage_id' value='" . htmlspecialchars($value['covoiturage_id'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='role' value='" . htmlspecialchars('chauffeur', ENT_QUOTES, 'UTF-8') . "'>
            <button type='submit' class='cancel_book'>Annuler</button>
        </form>
    </td><td>
                    
                    <form action='index.php?cancelBook' method='post' onsubmit=\"return confirm('Tout le monde est prêt ? On lance le trajet ?');\">
                    <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='covoiturage_id' value='" . htmlspecialchars($value['covoiturage_id'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='role' value='goTraj'>
            <button type='submit' class='avis_pass'>Lancer</button>
        </form>";
            }
            $trajet_futur .= "</td></tr>";
        }

        $trajet_fait = "";

        $trajet_user = $this->model->faitTrajets($utilisateur_id);

        foreach ($trajet_user as $value) {

            $timestamp = strtotime($value['date_depart']);
            $dateDepart = ucfirst($this->formatter->format($timestamp));
            $heureDepart = date('H\hi', strtotime($value['heure_depart']));
            $heureArrivee = date('H\hi', strtotime($value['heure_arrivee']));
            $duree = date('H\hi', strtotime($value['hdif']));

            $avisExiste = $this->model->CheckAvis($utilisateur_id, $value['covoiturage_id']);
            $trajet_fait .=

                "<tr>
            <td> <img src='img/icon-" . htmlspecialchars($value['role'], ENT_QUOTES, 'UTF-8') . ".png' class = 'icon_role_trajet'></td>
            <td>" . htmlspecialchars($value['lieu_depart'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($value['lieu_arrivee'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($dateDepart, ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($heureDepart, ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($heureArrivee, ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($duree, ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($value['prix_personne'], ENT_QUOTES, 'UTF-8') . " €</td>
            <td>";
            if ($avisExiste && $value['role'] == 'passager') {
                $trajet_fait .= "<span style='color: green;'>Avis envoyé</span></td></tr>";
            } else if ($value['role'] == 'passager') {
                $trajet_fait .= "<button type='button' class='avis_pass' onclick='openModal({$value['covoiturage_id']})'>Laisser un avis</button></td></tr>";
            } else {
                $trajet_fait .= "</td></tr>";
            }
        }





        $trajet_futurPass = "";

        $trajet_user = $this->model->futurTrajetsPass($utilisateur_id);


        foreach ($trajet_user as $value) {


            $timestamp = strtotime($value['date_depart']);
            $dateDepart = ucfirst($this->formatter->format($timestamp));

            $heureDepart = date('H\hi', strtotime($value['heure_depart']));
            $heureArrivee = date('H\hi', strtotime($value['heure_arrivee']));
            $duree = date('H\hi', strtotime($value['hdif']));

            $trajet_futurPass .=

                "<tr>
                    <td>" . htmlspecialchars($value['lieu_depart'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($value['lieu_arrivee'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($dateDepart, ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($heureDepart, ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($heureArrivee, ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($duree, ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($value['place_res'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($value['prix_personne'], ENT_QUOTES, 'UTF-8') . " €</td>
                    <td>" . htmlspecialchars($value['energie'], ENT_QUOTES, 'UTF-8') . "</td>

            <td>";

            if ($value['statut'] == '') {
                $trajet_futurPass .=
                    "<form action='index.php?cancelBook' method='post' onsubmit=\"return confirm('Voulez-vous vraiment annuler ce trajet ?');\">
                    <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='covoiturage_id' value='" . htmlspecialchars($value['covoiturage_id'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='role' value='passager'>
            <button type='submit' class='cancel_book'>Annuler</button>
        </form>
        </td>
            </tr>";
            } else {
                $trajet_futurPass .= htmlspecialchars($value['statut'], ENT_QUOTES, 'UTF-8') . " </td></tr>";
            }
        }


        $this->addCar($utilisateur_id);

        $voiture_user = "";
        $list_voiture = "";

        $voiture_info = $this->model->voiture_user($utilisateur_id);

        foreach ($voiture_info as $value) {
            $voiture_user .=

                "<tr>
            <td> <img src='" . $this->model->searchLogo($value['libelle']) . "' alt='" . $value['libelle'] . "' width='100'> </td>
            <td>" . htmlspecialchars($value['modele'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($value['immatriculation'], ENT_QUOTES, 'UTF-8') . "</td>
            <td> <img src='" . $this->model->searchCarbu($value['energie']) . "' alt='" . $value['energie'] . "' width='100'> </td>
            </tr>";

            $list_voiture .=

                "<option value='" . htmlspecialchars($value['voiture_id'], ENT_QUOTES, 'UTF-8') . "'>
                    " . htmlspecialchars($value['libelle'], ENT_QUOTES, 'UTF-8') . " - "
                . htmlspecialchars($value['modele'], ENT_QUOTES, 'UTF-8') . "
                </option>";
        }


        $list_marque = "";

        $marque_info = $this->model->select_marque();

        foreach ($marque_info as $value) {
            $list_marque .=

                "<option value='" . htmlspecialchars($value['marque_id'], ENT_QUOTES, 'UTF-8') . "'>"
                . htmlspecialchars($value['libelle'], ENT_QUOTES, 'UTF-8') .
                "</option>";
        }



        $this->genViews();
        switch ($_SESSION['user']['role']) {
            case 'admin':
                $this->requireRole('admin');
                $this->addMarque();
                $this->NewEmploye();


                $admin_marque = "";
                $marque_info = $this->model->select_marque();

                foreach ($marque_info as $value) {
                    $admin_marque .=

                        "<tr>
            <td> " . $value['libelle'] . " </td>
            <td> <img src='" . $this->model->searchLogo($value['libelle']) . "' alt='" . $value['libelle'] . "' width='100'> </td>
            </tr>";
                }

                $stats = $this->model->statsAdmin();

                $dates = [];
                $trajJour = [];
                $creditJour = [];

                foreach ($stats as $row) {
                    $dates[] = $row['date_depart'];
                    $trajJour[] = (int)$row['traj_jour'];
                    $creditJour[] = (int)$row['credit_jour'];
                }

                $datesJS = json_encode($dates);
                $trajJS = json_encode($trajJour);
                $creditJS = json_encode($creditJour);


                require_once 'code/views/profileAdmin.php';
                break;



            case 'employe':
                $this->requireRole('employe');
                $this->addMarque();
                $this->ValidationAvis();
                $admin_marque = "";
                $marque_info = $this->model->select_marque();

                foreach ($marque_info as $value) {
                    $admin_marque .=

                        "<tr>
            <td> " . $value['libelle'] . " </td>
            <td> <img src='" . $this->model->searchLogo($value['libelle']) . "' alt='" . $value['libelle'] . "' width='100'> </td>
            </tr>";
                }


                $statutAvis = "";
                $Avis_info = $this->model->liste_avisE();
                foreach ($Avis_info as $value) {
                    $photo_chauffeur = "data:image/jpeg;base64," . base64_encode($value['photo_chauffeur']);
                    $statutAvis .=

                        "<tr>
            <td>" . htmlspecialchars($value['Passager'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($value['commentaire'], ENT_QUOTES, 'UTF-8') . "</td>

            <td> " . $this->etoileEmploye((int)$value['a_note']) . " </td>
            
            <td><img src='" . $photo_chauffeur . "' alt='Photo utilisateur' style='width:40px; height:40px; object-fit:cover; border-radius:50%;'>
            
        <form action='index.php?avisUser' method='post' style='display:inline; margin:0; padding:0;'>
        <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='utilisateur_id' value='" . $value['chauffeur_id'] . "'>
            <button type='submit' style='background:none; border:none; padding:0; margin:0; font-size:14px; color:#007bff; cursor:pointer; text-decoration:underline;'>
                " . htmlspecialchars($value['Conducteur']) . "
            </button>
        </form></td>
            <td> 
            
            <form action='index.php?profile' method='post' onsubmit=\"return confirm('Voulez-vous valider cet avis ?');\">
            <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='avis_id' value='" . $value['avis_id'] . "'>
            <button type='submit' name='avis_valid' class='avis_pass'>Valider</button></form> 
                    </td><td>
            <form action='index.php?profile' method='post' onsubmit=\"return confirm('Voulez-vous refuser cet avis ?');\">
            <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='avis_id' value='" . $value['avis_id'] . "'>
            <button type='submit' name='avis_refus' class='cancel_book'>Refuser</button>
        </form> 
        
        </td>
            
            </tr>";
                }
                require_once 'code/views/profileEmploye.php';
                break;




            case 'chauffeur':
                $this->requireRole('chauffeur');
                require_once 'code/views/profile.php';
                break;
            case 'passager':
                $this->requireRole('passager');
                require_once 'code/views/profilePass.php';
                break;
            default:
                session_destroy();
                echo "<p>Rôle inconnu</p>";
                break;
        }


        require_once 'code/views/footer.php';
    }



    private function etoileEmploye($note)
    {
        $etoiles_html = "<div class='stars text-warning'>";
        $max = 5;

        for ($i = 1; $i <= $max; $i++) {
            $remplissage = min(max($note - ($i - 1), 0), 1);

            $etoiles_html .= '
        <span class="star">
            <span class="star-fill" style="width:' . ($remplissage * 100) . '%;">★</span>
            <span class="star-empty">★</span>
        </span>';
        }

        $etoiles_html .= "
    </div>
    <p class='text-white'> " . number_format($note, 1) . " / 5 
</p>";

        return $etoiles_html;
    }



    private function genererEtoiles($note, $nb_note)
    {
        $etoiles_html = "<div class='stars text-warning'>";
        $max = 5;

        for ($i = 1; $i <= $max; $i++) {
            $remplissage = min(max($note - ($i - 1), 0), 1);

            $etoiles_html .= '
        <span class="star">
            <span class="star-fill" style="width:' . ($remplissage * 100) . '%;">★</span>
            <span class="star-empty">★</span>
        </span>';
        }

        $etoiles_html .= "
    </div>
    <p class='text-white'> " . number_format($note, 1) . " / 5 
        <a href='index.php?avisUser' class='text-muted'>
    ( " . htmlspecialchars($nb_note) . " avis)
</a>
</p>";

        return $etoiles_html;
    }

    private function addCar($utilisateur_id)
    {
        if (isset($_POST['addCar'])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $this->checkCsrf();
                $modele = $_POST['modele'];
                $immat = $_POST['immat'];
                $marque = $_POST['marque'];
                $nrj = $_POST['nrj'] ?? '';
                $date_immat = $_POST['date_immat'] ?? '';
                if ($modele != "" && $immat != "" && $marque != "0") {
                    $new_car = $this->model->createCar($modele, $immat,  $nrj, $date_immat, $marque, $utilisateur_id);
                    if ($new_car) {
                        $this->model->change_role($utilisateur_id);
                        $role = $this->model->getRoleById($utilisateur_id);
                        $_SESSION['user']['role'] = $role['libelle'];
                    }
                }
            }
        }
    }

    private function addTrajet()
    {
        if (isset($_POST['addTrajet'])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $this->checkCsrf();
                $utilisateur_id = $_SESSION['user']['id'];
                $tav_Vdepart = $_POST['tav_Vdepart'];
                $tav_Varrivee = $_POST['tav_Varrivee'];
                $tav_dateD = $_POST['tav_dateD'];
                $tav_dateA = $_POST['tav_dateA'];
                $tav_Hdepart = $_POST['tav_Hdepart'];
                $tav_Harrivee = $_POST['tav_Harrivee'];
                $tav_prix = $_POST['tav_prix'];
                $tav_place = $_POST['tav_place'];
                $voiture_id = $_POST['voiture_id'];

                if ($tav_Vdepart != "" && $tav_Varrivee != "" && $tav_dateD != "" && $tav_dateA != "" && $tav_place != "" && $tav_Hdepart != "" && $tav_Harrivee != "" && $tav_prix != "") {
                    $this->model->createTrajet($tav_Vdepart, $tav_Varrivee, $tav_dateD, $tav_dateA, $tav_place, $tav_Hdepart, $tav_Harrivee, $tav_prix, $voiture_id, $utilisateur_id);
                }
            }
        }
    }

    private function dlImage($utilisateur_id)
    {
        if (isset($_POST['profilPic'])) {
            $this->checkCsrf();
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageData = file_get_contents($_FILES['image']['tmp_name']);
                $this->model->profilPic($imageData, $utilisateur_id);
            } else {
                echo "Aucun fichier envoyé.";
            }
        }
    }

    private function addMarque()
    {
        if (isset($_POST['addMarque'])) {
            $this->checkCsrf();
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $marque = $_POST['marque'];
                $fileName = $this->model->addPicMarque($_FILES['image'], $marque);

                if ($fileName != "") {
                    $this->model->addXmlMarque($marque, $fileName);
                    $this->model->addSqlMarque($marque);
                }
            } else {
                echo "Aucun fichier envoyé.";
            }
        }
    }


    private function NewEmploye()
    {
        if (isset($_POST['createEmp']) && $_SERVER["REQUEST_METHOD"] === "POST") {
            $this->checkCsrf();
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $date_naissance = $_POST['date_naissance'];
            $pseudo = $_POST['pseudo'];

            try {
                $Nuser = $this->model->createEmploye($nom, $prenom, $email, $pass, $date_naissance, $pseudo);
                if ($Nuser) {
                    $_SESSION['alert_success'] = "Félicitations " . $_POST['prenom'] . " a été ajouté aux employés ";
                }
            } catch (Exception $e) {
                $_SESSION['popup_error'] = $e->getMessage();
            }
        }
    }


    private function ValidationAvis()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->checkCsrf();

            if (isset($_POST['avis_valid'])) {
                $avis_id = $_POST['avis_id'];
                $this->model->valide_avis($avis_id);
            } elseif (isset($_POST['avis_refus'])) {
                $avis_id = $_POST['avis_id'];
                $this->model->refus_avis($avis_id);
            }
        }
    }
}
