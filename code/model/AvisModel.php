<?php


require_once 'MainModel.php';

class AvisModel extends MainModel
{


    public function ajouterAvis($commentaire, $note, $userId, $covoitId)
    {
        $commentaire = trim($commentaire);

        if ($commentaire === '' || !isset($userId) || !isset($covoitId) || !isset($note)) {
            throw new Exception("Paramètres invalides pour l'ajout d'un avis.");
        }

        try {
            $this->db->beginTransaction();

            $queryCovoit = $this->db->prepare("SELECT chauffeur FROM covoiturages WHERE covoiturage_id = ?");
            $queryCovoit->execute([$covoitId]);
            $chauffeur = $queryCovoit->fetch(PDO::FETCH_ASSOC);

            if (!$chauffeur) {
                throw new Exception("Covoiturage introuvable.");
            }

            $chauffeurId = $chauffeur['chauffeur'];


            $query = "INSERT INTO `avis` (`commentaire`, `statut`, `a_note`, `covoit_id`)
            VALUES (?, 'enAttente', ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$commentaire, $note, $covoitId]);

            $avisId = $this->db->lastInsertId();
            if (!$avisId) {
                throw new Exception("Erreur récup avis_id.");
            }

            $queryLiaison = "INSERT INTO `avis_users` (`au_utilisateur_id`, `au_avis_id`)
            VALUES (?, ?)";
            $stmtLiaison = $this->db->prepare($queryLiaison);
            $stmtLiaison->execute([$userId, $avisId]);


            $this->db->commit();

            return $avisId;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new Exception("Erreur PDO : " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}
