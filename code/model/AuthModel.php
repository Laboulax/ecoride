<?php

require_once 'MainModel.php';

class AuthModel extends MainModel
{


    public function createUser($nom, $prenom, $email, $pass, $adress, $date_naissance, $pseudo, $role)
    {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $check = $this->db->prepare("SELECT email FROM utilisateurs WHERE email = :email");
        $check->bindParam(':email', $email);
        $check->execute();

        if ($check->fetch()) {
            echo "<script>alert('❌ Cet email est déjà utilisé, veuillez saisir une nouvelle adresse mail');</script>";
            return false;
        }
        try {

            $this->db->beginTransaction();
            $query = "INSERT INTO `utilisateurs` (`Nom`, `Prenom`, `email`, `password`, `adress`, `date_naissance`, `photo`, `pseudo`, `credits`) VALUES 
        (:nom, :prenom, :email, :pass, :adress, :date_naissance, NULL, :pseudo, 20)";
            $insertUser = $this->db->prepare($query);
            $insertUser->bindParam(':nom', $nom);
            $insertUser->bindParam(':prenom', $prenom);
            $insertUser->bindParam(':email', $email);
            $insertUser->bindParam(':pass', $hash);
            $insertUser->bindParam(':adress', $adress);
            $insertUser->bindParam(':date_naissance', $date_naissance);
            $insertUser->bindParam(':pseudo', $pseudo);
            $insertUser->execute();


            $stmtUUID = $this->db->prepare("SELECT utilisateur_id FROM utilisateurs WHERE email = :email LIMIT 1");
            $stmtUUID->bindParam(':email', $email);
            $stmtUUID->execute();
            $user_id = $stmtUUID->fetchColumn();

            if (!$user_id) {
                throw new Exception("Impossible de récupérer l'Id de l'utilisateur");
            }


            $stmtRole = $this->db->prepare("SELECT role_id FROM roles WHERE libelle = :role");
            $stmtRole->bindParam(':role', $role);
            $stmtRole->execute();
            $role_id = $stmtRole->fetchColumn();

            if (!$role_id) {
                throw new Exception("Veuillez choisir un rôle");
            }


            $stmtRoleUser = $this->db->prepare("INSERT INTO role_users (ru_utilisateur_id, ru_role_id)
            VALUES (:user_id, :role_id)
        ");
            $stmtRoleUser->bindParam(':user_id', $user_id);
            $stmtRoleUser->bindParam(':role_id', $role_id);
            $stmtRoleUser->execute();


            $this->db->commit();

            return true;
        } catch (Exception $e) {

            $this->db->rollBack();
            throw $e;
        }
    }



    public function logUser($email, $pass)
    {
        $query = "SELECT u.*, r.libelle AS role
        FROM utilisateurs AS u
        LEFT JOIN role_users ru ON u.utilisateur_id = ru.ru_utilisateur_id
        LEFT JOIN roles r ON ru.ru_role_id = r.role_id
        WHERE u.email = :email
        LIMIT 1
    ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password'])) {


            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($pass, PASSWORD_DEFAULT);
                $update = $this->db->prepare("UPDATE utilisateurs SET password = :pass WHERE utilisateur_id = :id");
                $update->bindParam(':pass', $newHash);
                $update->bindParam(':id', $user['utilisateur_id']);
                $update->execute();
            }

            return $user;
        }

        return false;
    }
}
