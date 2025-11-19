<?php

require_once 'code/model/UserModel.php';

abstract class MainController
{



    protected $action;
    protected $model;
    protected $formatter;



    protected function genViews()
    {

        $profil = "<a href='index.php?login' ><img class='logoUser' src='img/iconsuser.png' alt='user'></a>";

        if (isset($_SESSION['user']['id'])) {

            $utilisateur_id = $_SESSION['user']['id'];


            $photo = "src='img/iconsuser.png'";

            $info_user = $this->model->getUserById($utilisateur_id);
            if ($info_user != NULL && $info_user['utilisateur_id'] != false && !empty($info_user['photo'])) {
                $photo = "src='data:image/jpeg;base64," . base64_encode($info_user['photo']);
                $_SESSION['user']['credits'] = $info_user['credits'];
            }

            $credits = isset($_SESSION['user']['credits']) ? $_SESSION['user']['credits'] : 0;

            if ($_SESSION['user']['role'] == 'chauffeur' || $_SESSION['user']['role'] == 'passager') {

                $profil = "<a href='index.php?credits' class='credits'>💳 " . $credits . " crédits</a> ";
            } else {
                $profil = '';
            }
            $profil .= "<a href='index.php?login'><img class='logoUser'" . $photo . "'alt='IconUser' /></a>
                    <form method='post' action='index.php?home'>
                        <button type='submit' name='logout' id='btn_deco' class='btn_deco'>Déconnexion</button>
                    </form>";
        }

        require_once 'code/views/header.php';
    }


    protected function logOut()
    {


        if (isset($_POST['logout'])) {

            $_SESSION = [];


            session_destroy();
            header("Location: index.php?login");
            exit;
        }
    }


    protected function requireLogin()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?login");
            exit;
        }
    }


    protected function requireRole($role)
    {
        $this->requireLogin();

        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != $role) {

            $_SESSION['flash_message'] = "Accès refusé : vous ne détenez pas les droits nécessaires.";
            header("Location: index.php?register");
            exit;
        }
    }

    protected function checkCsrf()
    {
        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            throw new Exception("Erreur : token CSRF invalide.");
        }
    }

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}
