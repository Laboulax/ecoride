<?php
require_once 'code/model/UserModel.php';

class AvisUserController extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    public function handle()
    {



        if (isset($_POST['utilisateur_id']) && !empty($_POST['utilisateur_id'])) {
            $utilisateur_id = $_POST['utilisateur_id'];
            $phrase = "Voici ce que pensent les ecoriders de ";
        } else {

            $this->requireLogin();
            $utilisateur_id = $_SESSION['user']['id'];
            $phrase = "voici ce que les ecoriders ont dit de vous ";
        }

        $info_user = $this->model->getUserById($utilisateur_id);
        if (!$info_user) {
            echo "Utilisateur introuvable.";
            exit;
        }

        $prenom = $info_user['Prenom'];

        $avisRecu = $this->model->getAvisUtilisateur($utilisateur_id);


        foreach ($avisRecu as &$avis) {

            $avis['etoiles_html'] = $this->genererEtoiles(floatval($avis['a_note']));
            $avis['passager_prenom'] = htmlspecialchars($avis['passager_prenom']);
            $avis['passager_nom'] = htmlspecialchars($avis['passager_nom']);
            $avis['commentaire'] = htmlspecialchars($avis['commentaire']);
        }
        unset($avis);

        $titre = $phrase . htmlspecialchars($prenom);

        $this->genViews();
        require_once 'code/views/avisUser.php';
        require_once 'code/views/footer.php';
    }

    private function genererEtoiles($note)
    {
        $etoiles_html = '';
        $max = 5;

        for ($i = 1; $i <= $max; $i++) {
            $remplissage = min(max($note - ($i - 1), 0), 1);

            $etoiles_html .= '
        <span class="star">
            <span class="star-fill" style="width:' . ($remplissage * 100) . '%;">★</span>
            <span class="star-empty">★</span>
        </span>';
        }

        return $etoiles_html;
    }
}
