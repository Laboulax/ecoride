<?php


require_once 'MainModel.php';

class UserModel extends MainModel
{



    public function futurTrajets($utilisateur_id)
    {
        $query = "SELECT c.*, v.*, cu.place_res, TIMEDIFF(c.heure_arrivee, c.heure_depart) AS hdif
    FROM covoiturages AS c
    LEFT JOIN covoiturage_users AS cu ON cu.ac_covoiturage_id = c.covoiturage_id
    INNER JOIN voitures AS v ON c.car_covoit = v.voiture_id
    WHERE v.appartient_voiture = :utilisateur_id
    AND (c.date_arrivee >= CURDATE() OR c.statut != 'Termine')";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        $stmt->execute();
        return $stmt;
    }

    public function futurTrajetsPass($utilisateur_id)
    {
        $query = "SELECT c.*,v.energie, cu.place_res, TIMEDIFF(heure_arrivee, heure_depart) AS 'hdif' FROM covoiturages AS c INNER JOIN covoiturage_users AS cu 
                    ON cu.ac_covoiturage_id = c.covoiturage_id INNER JOIN voitures AS v
                    ON v.voiture_id = c.car_covoit WHERE cu.ac_utilisateur_id = :utilisateur_id AND (c.date_arrivee >= CURDATE() OR c.statut != 'Termine')";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        $stmt->execute();
        return $stmt;
    }



    public function faitTrajets($utilisateur_id)
    {
        $query = "SELECT CASE WHEN appartient_voiture = :utilisateur_id
        THEN 'chauffeur' ELSE 'passager' END AS 'role',
        c.*, TIMEDIFF(heure_arrivee, heure_depart) AS 'hdif'
        FROM covoiturages AS c
        INNER JOIN `voitures` ON car_covoit = voiture_id 
        INNER JOIN covoiturage_users AS cu
        ON cu.ac_covoiturage_id = c.covoiturage_id
        WHERE (cu.ac_utilisateur_id = :utilisateur_id OR appartient_voiture = :utilisateur_id)
        AND (c.date_arrivee < CURDATE() OR c.statut = 'Termine')
        GROUP BY c.covoiturage_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':utilisateur_id' => $utilisateur_id]);
        return $stmt;
    }


    public function voiture_user($utilisateur_id)
    {
        $query = "SELECT * FROM `voitures` INNER JOIN `marques` ON marque_voiture = marque_id  WHERE appartient_voiture = :utilisateur_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        $stmt->execute();
        return $stmt;
    }

    public function select_marque()
    {
        $query = "SELECT * FROM `marques`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function createCar($modele, $immat,  $nrj, $date_immat, $marque, $utilisateur_id)
    {
        $query = "INSERT INTO `voitures`( `modele`, `immatriculation`,  `energie`, `date_premiere_immat`, `appartient_voiture`, `marque_voiture`) VALUES 
        (:modele, :immat, :nrj, :date_immat,  :utilisateur_id, :marque)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':modele', $modele);
        $stmt->bindParam(':immat', $immat);
        $stmt->bindParam(':nrj', $nrj);
        $stmt->bindParam(':date_immat', $date_immat);
        $stmt->bindParam(':marque', $marque);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        return $stmt->execute();
    }


    public function change_role($utilisateur_id)
    {
        $query = "UPDATE `role_users` SET `ru_role_id`=2 WHERE ru_utilisateur_id = :utilisateur_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        return $stmt->execute();
    }


    public function getRoleById($utilisateur_id)
    {
        $query = "SELECT `libelle` FROM `role_users` INNER JOIN `roles` ON `role_id` = `ru_role_id`  WHERE ru_utilisateur_id = :utilisateur_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function createTrajet($tav_Vdepart, $tav_Varrivee, $tav_dateD, $tav_dateA, $tav_place, $tav_Hdepart, $tav_Harrivee, $tav_prix, $voiture_id, $utilisateur_id)
    {
        $query = "INSERT INTO `covoiturages`(`date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `nb_place`, `prix_personne`, `car_covoit`, `chauffeur`)
        VALUES (:tav_dateD  , :tav_Hdepart , :tav_Vdepart , :tav_dateA ,  :tav_Harrivee , :tav_Varrivee ,  :tav_place ,  :tav_prix, :voiture_id, :utilisateur_id)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tav_dateD', $tav_dateD);
        $stmt->bindParam(':tav_Hdepart', $tav_Hdepart);
        $stmt->bindParam(':tav_Vdepart', $tav_Vdepart);
        $stmt->bindParam(':tav_dateA', $tav_dateA);
        $stmt->bindParam(':tav_Harrivee', $tav_Harrivee);
        $stmt->bindParam(':tav_Varrivee', $tav_Varrivee);
        $stmt->bindParam(':tav_place', $tav_place);
        $stmt->bindParam(':tav_prix', $tav_prix);
        $stmt->bindParam(':voiture_id', $voiture_id);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        return $stmt->execute();
    }

    public function profilPic($photo, $utilisateur_id)
    {
        $query = "UPDATE `utilisateurs` SET `photo` = :photo WHERE utilisateur_id = :utilisateur_id ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        if ($stmt->execute()) {
            echo "<script>alert('Image enregistrée avec succès !');</script>";
        } else {
            echo "<script>alert('Echec du chargement de l'image !');</script>";
        }
    }



    public function searchLogo($marque)
    {
        $xml = (new Database())->getXML(__DIR__ . "/../../noSQL/logoCar.xml");
        $result = $xml->xpath("//Car[Name='$marque']/Logo");
        $logo = (string)$result[0];
        return $logo;
    }


    public function searchCarbu($nrj)
    {
        $xml = (new Database())->getXML(__DIR__ . "/../../noSQL/logonrj.xml");
        $result = $xml->xpath("//Nrj[Name='$nrj']/Logo");
        $logo = (string)$result[0];
        return $logo;
    }

    public function CheckAvis($userId, $covoitId)
    {
        $query = "SELECT COUNT(*) FROM avis AS a
            INNER JOIN avis_users AS au ON a.avis_id = au.au_avis_id
            WHERE au.au_utilisateur_id = :userId AND a.covoit_id = :covoitId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':userId' => $userId,
            ':covoitId' => $covoitId
        ]);
        return $stmt->fetchColumn() > 0;
    }




    public function getAvisUtilisateur($utilisateur_id)
    {
        $query = "SELECT 
                a.a_note,
                a.commentaire,
                a.statut,
                u.Prenom AS passager_prenom,
                u.Nom AS passager_nom,
                u.photo AS passager_photo
            FROM avis AS a
            INNER JOIN avis_users AS au ON a.avis_id = au.au_avis_id
            INNER JOIN covoiturages AS c ON c.covoiturage_id = a.covoit_id
            INNER JOIN utilisateurs AS u ON u.utilisateur_id = au.au_utilisateur_id
            WHERE c.chauffeur = :utilisateur_id AND a.statut ='valide'";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':utilisateur_id' => $utilisateur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function addPicMarque($photo, $marque)
    {

        $targetDir = "img/logoCar/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $marque = strtolower(preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", $marque));

        $fileType = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "<script>alert('Format de fichier non autorisé.');</script>";
            return "";
        }

        foreach ($allowedTypes as $ext) {
            if (file_exists($targetDir . $marque . '.' . $ext)) {
                echo "<script>alert('Le logo de cette marque est déjà référencée sous une autre extension');</script>";
                return "";
            }
        }

        $targetFile = $targetDir . $marque . '.' . $fileType;


        $check = getimagesize($photo["tmp_name"]);
        if ($check === false) {
            return "Erreur : le fichier n’est pas une image valide.";
        }

        if (move_uploaded_file($photo["tmp_name"], $targetFile)) {
            echo "<script>alert('Image enregistrée avec succès !');</script>";
        } else {
            echo "<script>alert('Echec du chargement de l'image !');</script>";
            $marque = "";
        }
        return $marque . '.' . $fileType;
    }




    public function addXmlMarque($marque, $fileName)
    {
        $xmlFile = 'noSQL/logoCar.xml';

        if (!file_exists($xmlFile)) {
            return "Erreur : le fichier XML n'existe pas.";
        }

        $xml = simplexml_load_file($xmlFile);

        if ($xml === false) {
            return "Erreur : impossible de charger le fichier XML.";
        }

        $newCar = $xml->addChild('Car');
        $newCar->addChild('Name', htmlspecialchars($marque));
        $newCar->addChild('Logo', 'img/logoCar/' . $fileName);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($xmlFile);

        return "Marque ajoutée dans le fichier XML.";
    }

    public function addSqlMarque($marque)
    {
        $query = "INSERT INTO `marques`( `libelle`) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$marque]);
    }


    public function createEmploye($nom, $prenom, $email, $pass,  $date_naissance, $pseudo)
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
            $query = "INSERT INTO `utilisateurs` (`Nom`, `Prenom`, `email`, `password`, `date_naissance`, `photo`, `pseudo`) VALUES 
        (:nom, :prenom, :email, :pass, :date_naissance, NULL, :pseudo)";
            $insertUser = $this->db->prepare($query);
            $insertUser->bindParam(':nom', $nom);
            $insertUser->bindParam(':prenom', $prenom);
            $insertUser->bindParam(':email', $email);
            $insertUser->bindParam(':pass', $hash);
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


            $stmtRoleUser = $this->db->prepare("INSERT INTO role_users (ru_utilisateur_id, ru_role_id)
            VALUES (:user_id, 4)
        ");
            $stmtRoleUser->bindParam(':user_id', $user_id);

            $stmtRoleUser->execute();

            $this->db->commit();

            return true;
        } catch (Exception $e) {

            $this->db->rollBack();
            throw $e;
        }
    }

    public function liste_avisE()
    {
        $query = "SELECT CONCAT(p.Nom ,' ' , p.Prenom , ' (', p.email ,')') AS Passager, 
        CONCAT(c.Nom ,' ' , c.Prenom , ' (', c.email ,')') AS Conducteur,
        a.*, c.photo AS photo_chauffeur, c.utilisateur_id AS chauffeur_id FROM `utilisateurs` AS p INNER JOIN `avis_users` ON p.utilisateur_id = au_utilisateur_id
        INNER JOIN `avis` AS a ON au_avis_id = a.avis_id 
        INNER JOIN `covoiturages` ON covoit_id = covoiturage_id 
        INNER JOIN `utilisateurs` AS c ON chauffeur = c.utilisateur_id 
        WHERE a.statut = 'enAttente'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    public function valide_avis($avis_id)
    {
        $query = "UPDATE `avis` SET `statut` = 'valide' WHERE avis_id = ? ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$avis_id]);
        return $stmt;
    }


    public function refus_avis($avis_id)
    {
        try {
            $this->db->beginTransaction();
            $query = "DELETE FROM `avis_users` WHERE au_avis_id = ? ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$avis_id]);


            $query = "DELETE FROM `avis` WHERE avis_id = ? ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$avis_id]);

            $this->db->commit();

            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function statsAdmin()
    {
        $query = "SELECT `date_depart`, COUNT(`covoiturage_id`) AS traj_jour, 
        2*SUM(CASE WHEN `statut`='Termine' THEN `place_res` ELSE 0 END) AS credit_jour 
        FROM `covoiturages` LEFT JOIN `covoiturage_users` 
        ON `covoiturage_id` = `ac_covoiturage_id` 
        GROUP BY `date_depart` ORDER BY `date_depart`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
