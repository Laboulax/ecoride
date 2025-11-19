<?php


require_once 'MainModel.php';

class SearchModel extends MainModel
{

    public function searchTrajet($depart, $arrivee, $nbplace, $filtre_duree = null, $prix = null, $energie = null, $note_min = null)
    {
        $query = "SELECT c.*, v.*, u.*, 
        TIMEDIFF(c.heure_arrivee, c.heure_depart) AS hdif
        FROM covoiturages AS c
        INNER JOIN voitures AS v ON c.car_covoit = v.voiture_id
        INNER JOIN utilisateurs AS u ON v.appartient_voiture = u.utilisateur_id
        WHERE c.lieu_depart = :depart
        AND c.lieu_arrivee = :arrivee
        AND c.nb_place >= :nbplace
        AND c.date_arrivee >= CURDATE()
    ";


        $params = [    // Définition des paramètres "de base"
            ':depart'  => $depart,
            ':arrivee' => $arrivee,
            ':nbplace' => $nbplace
        ];


        if (!empty($filtre_duree)) {
            $query .= " AND TIME_TO_SEC(TIMEDIFF(c.heure_arrivee, c.heure_depart)) <= :filtre_duree";   // Ajout du SQL
            $params[':filtre_duree'] = (int)$filtre_duree;                                              // et des paramètres associès si filtre utilisé
        }

        if (!empty($prix)) {
            $query .= " AND c.prix_personne <= :prix";
            $params[':prix'] = (float)str_replace(',', '.', $prix);
        }

        if (!empty($energie)) {
            $query .= " AND v.energie = :energie";
            $params[':energie'] = $energie;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);


        $listNote = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if (!empty($note_min)) {
            $listNote = array_filter($listNote, function ($trajet) use ($note_min) {

                $info_note = $this->getNoteById($trajet['utilisateur_id']);

                if ($info_note === null || empty($info_note['note'])) {
                    return false;
                }
                return floatval($info_note['note']) >= floatval($note_min);
            });
        }


        return $listNote;
    }
}
