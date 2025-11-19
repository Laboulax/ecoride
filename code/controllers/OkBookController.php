<?php
require_once 'code/model/TrajetModel.php';

class OkBookController extends MainController

{


    public function __construct()
    {
        parent::__construct();

        $this->model = new TrajetModel();
    }


    public function handle()
    {


        if (!isset($_SESSION['user'])) {
            $_SESSION['flash_message'] = "Veuillez vous connecter pour réserver un trajet.";
            header("Location: index.php?login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: index.php?search");
            exit;
        }
        $this->checkCsrf();

        if (!isset($_POST['id']) || !isset($_POST['nbPersonnes'])) {
            header("Location: index.php?search");
            exit;
        }


        $userId   = $_SESSION['user']['id'];
        $trajetId = (int)$_POST['id'];
        $nbPers   = max(1, (int)$_POST['nbPersonnes']);
        $credit =  $_SESSION['user']['credits'];
        $trajet = $this->model->getTrajetById($trajetId);
        $prix = ((float)$trajet['prix_personne']) * $nbPers;


        if ($prix <= $credit) {
            try {
                $this->model->reserveTrajet($userId, $trajetId, $nbPers);
            } catch (Exception $e) {
                $class_book = "book_refus";
                $titre_book = "Réservation impossible !";
                $text_book =  $e->getMessage();;
                $this->genViews();
                require_once 'code/views/okBook.php';
                require_once 'code/views/footer.php';
                exit;
            }
        } else {
            echo "<script>alert('Crédits insuffisants !');</script>";
            $this->genViews();
            require_once 'code/views/credits.php';
            require_once 'code/views/footer.php';
            exit;
        }

        $titre_book = "Réservation Confirmée !";
        $text_book = "Merci d'avoir réservé avec Ecoride. Vous recevrez un e-mail de confirmation !";
        $class_book = '';

        $this->genViews();
        require_once 'code/views/okBook.php';
        require_once 'code/views/footer.php';
    }
}
