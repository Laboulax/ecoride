<?php

require_once 'code/model/AuthModel.php';

class AuthController extends MainController
{


    public function __construct($action)
    {
        parent::__construct(); 

        $this->action = $action;
        $this->model = new AuthModel();
    }

    public function handle()
    {

        if (isset($_SESSION['user']['id'])) {
            header('Location:index.php?profile');
        } else {
            if ($this->action == 'login') {
                $message = $this->connectUser();
                $this->genViews();
                require_once 'code/views/login.php';
                require_once 'code/views/footer.php';
            } elseif ($this->action == 'register') {
                if ($this->newUser()) {
                    header('Location:index.php?login');
                    exit();
                }
                $this->genViews();
                require_once 'code/views/register.php';
                require_once 'code/views/footer.php';
            }
        }
    }

    private function newUser()
    {
        if (isset($_POST['ok']) && $_SERVER["REQUEST_METHOD"] === "POST") {
            $this->checkCsrf();
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $adress = $_POST['adress'];
            $date_naissance = $_POST['date_naissance'];
            $pseudo = $_POST['pseudo'];
            $role = $_POST['role'];
            try {
                $Nuser = $this->model->createUser($nom, $prenom, $email, $pass, $adress, $date_naissance, $pseudo, $role);
                if ($Nuser) {
                    $user = $this->model->logUser($email, $pass);
                    if ($user) {
                        session_regenerate_id(true);
                        $_SESSION['user'] = [
                            'id'     => $user['utilisateur_id'],
                            'nom'    => $user['Nom'],
                            'prenom' => $user['Prenom'],
                            'pseudo' => $user['pseudo'],
                            'email'  => $user['email'],
                            'role'   => $user['role'],
                            'credits' => $user['credits']
                        ];
                        $_SESSION['alert_success'] = "Félicitations {$user['Prenom']} ! Vous venez de recevoir {$user['credits']} crédits.";
                        header("Location: index.php?profile");
                        exit;
                    }
                }
            } catch (Exception $e) {
                $_SESSION['popup_error'] = $e->getMessage();
                header("Location: index.php?register");
                exit;
            }
        }
    }

    private function connectUser()
    {
        if (isset($_POST['ok']) && $_SERVER["REQUEST_METHOD"] === "POST") {
            $this->checkCsrf();
            $email = trim($_POST['email']);
            $pass  = $_POST['pass'];

            if (!empty($email) && !empty($pass)) {
                $user = $this->model->logUser($email, $pass);

                if ($user) {
                    $_SESSION['user'] = [
                        'id'     => $user['utilisateur_id'],
                        'nom'    => $user['Nom'],
                        'prenom' => $user['Prenom'],
                        'pseudo' => $user['pseudo'],
                        'email'  => $user['email'],
                        'role'   => $user['role'],
                        'credits' => $user['credits']
                    ];

                    header("Location: index.php?profile");
                } else {
                    return "Email ou mot de passe incorrect, veuillez réessayer.";
                }
            } else {
                return "Merci de remplir tous les champs.";
            }
        }
        return '';
    }
}
