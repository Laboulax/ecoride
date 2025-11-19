<?php
require_once 'code/model/TrajetModel.php';

class CancelBookController extends MainController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new TrajetModel();
    }

    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash_message'] = "Veuillez vous connecter.";
            header("Location: index.php?login");
            exit;
        }
        $userId = $_SESSION['user']['id'];
        $trajetId = $_POST['covoiturage_id'];
        $role = $_POST['role'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?profile");
            exit;
        }
        $this->checkCsrf();


        switch ($role) {
            case 'passager':
                $methode = 'cancelBookPass';
                $params = [$userId, $trajetId];
                break;

            case 'chauffeur':
                $methode = 'cancelBook';
                $params = [$trajetId];
                break;

            case 'goTraj':
                $methode = 'goTrajet';
                $params = [$trajetId];
                break;

            case 'endTraj':
                $methode = 'endTrajet';
                $params = [$trajetId];
                break;

            default:
                $_SESSION['flash_message'] = "Rôle invalide.";
                header("Location: index.php?profile");
                exit;
        }
        try {
            $this->model->$methode(...$params);
            $_SESSION['flash_message'] = "Votre réservation a bien été annulée.";
        } catch (Exception $e) {
            $_SESSION['flash_message'] = "Erreur : " . $e->getMessage();
        }
        header("Location: index.php?profile");
        exit;
    }
}
