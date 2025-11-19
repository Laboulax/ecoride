<?php

require_once 'code/database/db.php';

abstract class MainModel
{

    protected $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getUserById($utilisateur_id)
    {
        $query = "SELECT * FROM `utilisateurs` WHERE utilisateur_id = :utilisateur_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getNoteById($utilisateur_id)
    {
        $query = "SELECT COUNT(*) AS nb_note, AVG(`a_note`) AS note FROM `covoiturages` 
        INNER JOIN `avis` ON covoiturage_id = covoit_id 
        WHERE chauffeur = :utilisateur_id AND avis.statut = 'valide'
        GROUP BY chauffeur";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
