<?php


require_once 'MainModel.php';

class TrajetModel extends MainModel
{



    public function bookTrajet($tav_Vdepart, $tav_Varrivee, $tav_dateD, $tav_dateA, $tav_place, $tav_Hdepart, $tav_Harrivee, $tav_prix, $voiture_id)
    {
        $query = "INSERT INTO `covoiturages`(`date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `nb_place`, `prix_personne`, `car_covoit`)
        VALUES (:tav_dateD  , :tav_Hdepart , :tav_Vdepart , :tav_dateA ,  :tav_Harrivee , :tav_Varrivee ,  :tav_place ,  :tav_prix, :voiture_id)";

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

        return $stmt->execute();
    }



    public function getTrajetById($trajetId)
    {
        $stmt = $this->db->prepare("SELECT * FROM covoiturages WHERE covoiturage_id = ?");
        $stmt->execute([$trajetId]);
        $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
        return $trajet ?: null;
    }



    public function updatePlaces($trajetId, $nbPlaces)
    {
        $stmt = $this->db->prepare("UPDATE covoiturages SET nb_place = nb_place - ? WHERE covoiturage_id = ?");
        return $stmt->execute([$nbPlaces, $trajetId]);
    }

    public function updateCredits($prix, $userId)
    {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET credits = credits - ? WHERE utilisateur_id = ?");
        return $stmt->execute([$prix, $userId]);
    }


    public function addReservation($userId, $trajetId, $nbPlaces)
    {

        $stmt = $this->db->prepare(" INSERT INTO covoiturage_users (ac_utilisateur_id, ac_covoiturage_id, place_res) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$userId, $trajetId, $nbPlaces]);
    }


    public function reserveTrajet($userId,  $trajetId,  $nbPlaces)
    {
        try {
            $this->db->beginTransaction();
            $trajet = $this->getTrajetById($trajetId);

            if (!$trajet) {
                throw new Exception("Trajet introuvable.");
            }

            if ($trajet['nb_place'] < $nbPlaces) {
                throw new Exception("Nombre de places insuffisant.");
            }

            $prixTot = (float)$trajet['prix_personne'] * $nbPlaces;


            $this->updatePlaces($trajetId, $nbPlaces);
            if (!$this->addReservation($userId, $trajetId,  $nbPlaces)) {
                throw new Exception("Trajet déja réservé");
            }
            $this->updateCredits($prixTot, $userId);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }





    public function cancelBookPass($userId, $trajetId)
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("SELECT * FROM covoiturage_users 
            WHERE ac_utilisateur_id = ? AND ac_covoiturage_id = ?           
        ");
            $stmt->execute([$userId, $trajetId]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$reservation) {
                throw new Exception("Aucune réservation trouvée pour ce trajet.");
            }

            $trajet = $this->getTrajetById($trajetId);
            $nbPlaces = $reservation['place_res'];
            $prixTot = (float)$trajet['prix_personne'] * $nbPlaces;
            $stmt = $this->db->prepare("DELETE FROM covoiturage_users 
            WHERE ac_utilisateur_id = ? AND ac_covoiturage_id = ?       
        ");
            $stmt->execute([$userId, $trajetId]);
            $stmt = $this->db->prepare("UPDATE covoiturages 
            SET nb_place = nb_place + ? 
            WHERE covoiturage_id = ?
        ");
            $stmt->execute([$nbPlaces, $trajetId]);

            $this->updateCredits(-$prixTot, $userId);

            $this->db->commit();

            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }



    public function getPassagerByTrajet($trajetId)
    {
        $stmt = $this->db->prepare("SELECT * FROM covoiturage_users WHERE ac_covoiturage_id = ?");
        $stmt->execute([$trajetId]);
        return $stmt;
    }


    public function cancelBook($trajetId)
    {
        try {
            $trajet = $this->getTrajetById($trajetId);
            $reservation = $this->getPassagerByTrajet($trajetId);
            if (!$trajet) {
                throw new Exception("Aucune trajet trouvé.");
            }

            foreach ($reservation as $Pass) {
                $nbPlaces = $Pass['place_res'];
                $prixTot = (float)$trajet['prix_personne'] * $nbPlaces;
                $userId = $Pass['ac_utilisateur_id'];
                $this->updateCredits(-$prixTot, $userId);
            }

            $stmt = $this->db->prepare("DELETE FROM covoiturage_users 
            WHERE  ac_covoiturage_id = ?       
        ");
            $stmt->execute([$trajetId]);

            $stmt = $this->db->prepare("DELETE FROM covoiturages 
            WHERE covoiturage_id = ? 
        ");
            $stmt->execute([$trajetId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }


    public function goTrajet($trajetId)
    {
        $stmt = $this->db->prepare("UPDATE covoiturages SET statut = 'EnCours' WHERE covoiturage_id = ?");
        return $stmt->execute([$trajetId]);
    }

    public function endTrajet($trajetId)
    {
        try {
            $this->db->beginTransaction();
            $trajet = $this->getTrajetById($trajetId);
            if (!$trajet) {
                throw new Exception("Aucune trajet trouvé.");
            }

            $reservation  = $this->getPassagerByTrajet($trajetId);
            $nb_places = 0;

            foreach ($reservation as $Pass) {
                $nb_places += (int)$Pass['place_res'];
            }

            $ChauffeurId = $trajet['chauffeur'];
            $prixChauffeur = ((float)$trajet['prix_personne'] - 2) * $nb_places;

            $this->updateCredits(-$prixChauffeur, $ChauffeurId);

            $stmt = $this->db->prepare("UPDATE covoiturages SET statut = 'Termine' WHERE covoiturage_id = ?");
            $stmt->execute([$trajetId]);
            $this->db->commit();
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}
