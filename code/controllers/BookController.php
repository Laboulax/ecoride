<?php


require_once 'code/model/SearchModel.php';

class BookController extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new SearchModel();
    }


    public function handle()
    {


        if (!isset($_SESSION['user'])) {

            $_SESSION['flash_message'] = "Veuillez vous connecter pour réserver un trajet !";
            header("Location: index.php?login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?search");
            exit;
        }

        $this->checkCsrf();

        if (!isset($_POST['id'])) {
            header("Location: index.php?search");
            exit;
        }


        if ($_SESSION['user']['id'] == $_POST['utilisateur_id']) {
            $this->checkCsrf();

            $class_book = "book_refus";
            $titre_book = "Réservation impossible !";
            $text_book = "Vous ne pouvez pas réserver vos propres trajets !";
            $this->genViews();
            require_once 'code/views/okBook.php';
            require_once 'code/views/footer.php';
            exit;
        }

        $trajet = [
            'id'           => $_POST['id'],
            'pseudo'       => $_POST['pseudo'],
            'depart'       => $_POST['depart'] ?? '',
            'arrivee'      => $_POST['arrivee'] ?? '',
            'date_depart'  => $_POST['date_depart'] ?? '',
            'heure_depart' => $_POST['heure_depart'] ?? '',
            'date_arrivee'  => $_POST['date_arrivee'] ?? '',
            'heure_arrivee' => $_POST['heure_arrivee'] ?? '',
            'prix'         => $_POST['prix'] ?? 0,
            'places'       => $_POST['places'] ?? 0,
        ];


        $this->genViews();
        require_once 'code/views/book.php';
        require_once 'code/views/footer.php';
    }
}
