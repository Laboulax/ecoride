<?php
require_once 'code/controllers/MainController.php';
require_once 'code/model/AvisModel.php';

class AvisController extends MainController
{


    public function __construct()
    {
        $this->model = new AvisModel();
    }

    public function handle()
    {


        if (!isset($_SESSION['user'])) {
            $_SESSION['flash_message'] = "Veuillez vous connecter.";
            header("Location: index.php?login");
            exit;
        }


        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && !empty($_POST['commentaire'])
            && !empty($_POST['covoiturage_id'])
            && !empty($_POST['note'])
        ) {
            $this->checkCsrf();
            $commentaire = trim($_POST['commentaire']);
            $covoitId    = (int) $_POST['covoiturage_id'];
            $userId = $_SESSION['user']['id'];
            $note = (int) $_POST['note'];

            if ($commentaire === '' || strlen($commentaire) > 100) {
                $class = "book_refus";
                $titre_book = "Commentaire trop long !";
                $text_book  = "Votre commentaire doit faire moins de 100 caractères.";

                $this->genViews();
                require "code/views/okBook.php";
                require "code/views/footer.php";
                exit;
            }

            try {
                $this->model->ajouterAvis($commentaire, $note, $userId, $covoitId);
                $_SESSION['flash_message'] = "Commentaire envoyé.";
            } catch (Exception $e) {
                $_SESSION['flash_message'] = "Erreur : " . $e->getMessage();
            }

            header("Location: index.php?profile");
            exit;
        }
    }
}
