<?php

class InformationsFacade extends DBConnection
{

    public function fetchInformations()
    {
        $sql = $this->connect()->prepare("SELECT * FROM informations");
        $sql->execute();
        return $sql;
    }

    public function fetchInformationById($informationId)
    {
        $sql = $this->connect()->prepare("SELECT * FROM informations WHERE id = ?");
        $sql->execute([$informationId]);
        return $sql;
    }

    public function addInformation($title, $information, $description)
    {
        $sql = $this->connect()->prepare("INSERT INTO informations(title, image, description) VALUES (?, ?, ?)");
        $sql->execute([$title, $information, $description]);
        return $sql;
    }

    public function deleteInformation($informationId)
    {
        $sql = $this->connect()->prepare("DELETE FROM informations WHERE id = $informationId");
        $sql->execute();
        return $sql;
    }

    
    public function updateInformation($title, $image, $description, $informationId)
    {
        // Prepare the SQL query with placeholders
        $sql = $this->connect()->prepare("
            UPDATE informations 
            SET title = :title, 
                image = :image, 
                description = :description
            WHERE id = :informationId
        ");

        // Bind parameters to the placeholders
        $sql->bindParam(':title', $title);
        $sql->bindParam(':image', $image);
        $sql->bindParam(':description', $description);
        $sql->bindParam(':informationId', $informationId, PDO::PARAM_INT);

        // Execute the query
        $sql->execute();

        return $sql;
    }

}
