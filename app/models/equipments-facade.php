<?php

class EquipmentsFacade extends DBConnection
{

    public function fetchEquipments()
    {
        $sql = $this->connect()->prepare("SELECT equipments.*, inventory.* FROM equipments
                                          LEFT JOIN inventory ON equipments.equipment = inventory.equipment_id
                                          ORDER BY user_id DESC, (date_issued IS NOT NULL) ASC");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);  // Fetch as an associative array
    }


    // public function fetchDisabilityEquipmentApplicable($pwdId)
    // {
    //     $sql = $this->connect()->prepare("
    //         SELECT 
    //             de.*, 
    //             u.*, 
    //             i.*, 
    //             e.equipment AS equipment_from_equipments, 
    //             de.equipment_id AS equipment_from_disability_equipments
    //         FROM disability_equipments de
    //         LEFT JOIN users u ON u.disability = de.disability
    //         LEFT JOIN inventory i ON i.equipment_id = de.equipment_id
    //         LEFT JOIN equipments e ON e.equipment = i.equipment_id
    //         WHERE u.id = ?
    //     ");
    //     $sql->execute([$pwdId]);
    //     return $sql->fetchAll(PDO::FETCH_ASSOC);
    // }
//     public function fetchDisabilityEquipmentApplicable($pwdId)
// {
//     // Get the disabilities of the user
//     $sql = $this->connect()->prepare("SELECT disability FROM users WHERE id = ?");
//     $sql->execute([$pwdId]);
//     $userDisability = $sql->fetchColumn(); // Get the comma-separated list of disabilities

//     if ($userDisability) {
//         // Split disabilities by comma and trim spaces
//         $disabilities = array_map('trim', explode(',', $userDisability));
//         print_r($disabilities);

//         // Prepare the SQL query to get equipment based on the user's disabilities
//         $placeholders = rtrim(str_repeat('?, ', count($disabilities)), ', '); // Create placeholders for IN
//         $sql = $this->connect()->prepare("
//             SELECT 
//                 de.*, 
//                 i.*, 
//                 e.equipment AS equipment_from_equipments, 
//                 de.equipment_id AS equipment_from_disability_equipments
//             FROM disability_equipments de
//             LEFT JOIN inventory i ON i.equipment_id = de.equipment_id
//             LEFT JOIN equipments e ON e.equipment = i.equipment_id
//             WHERE de.disability IN ($placeholders)
//         ");

//         // Execute the query with the disabilities array
//         $sql->execute($disabilities);

//         return $sql->fetchAll(PDO::FETCH_ASSOC);
//     }

//     return []; // Return an empty array if no disabilities are found for the user
// }
public function fetchDisabilityEquipmentApplicable($pwdId)
{
    // Step 1: Get the disabilities for the user
    $sql = $this->connect()->prepare("SELECT disability FROM users WHERE id = ?");
    $sql->execute([$pwdId]);
    $userDisability = $sql->fetchColumn(); // Get the comma-separated list of disabilities

    if ($userDisability) {
        // Step 2: Split the disabilities into an array
        $disabilities = array_map('trim', explode(',', $userDisability));  // Split disabilities and trim spaces

        // Step 3: Prepare SQL with dynamic IN clause based on the number of disabilities
        $placeholders = implode(',', array_fill(0, count($disabilities), '?')); // Create placeholders for IN

        // Step 4: Fetch the equipment applicable for these disabilities
        $sql = $this->connect()->prepare("
            SELECT 
                de.*, 
                i.*, 
                e.equipment AS equipment_from_equipments, 
                de.equipment_id AS equipment_from_disability_equipments
            FROM disability_equipments de
            LEFT JOIN inventory i ON i.equipment_id = de.equipment_id
            LEFT JOIN equipments e ON e.equipment = i.equipment_id
            WHERE de.disability IN ($placeholders)
        ");

        // Step 5: Execute the query with disabilities array
        $sql->execute($disabilities);

        // Step 6: Return the result
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    return []; // Return an empty array if no disabilities are found for the user
}



    public function fetchEquipmentById($id)
    {
        $sql = $this->connect()->prepare("SELECT * FROM equipments WHERE equipments.id = ?");
        $sql->execute([$id]); //[$id]
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchEquipmentOptId($id)
    {
        $sql = $this->connect()->prepare("
            SELECT 
                e.*, 
                de.*, 
                i.* 
            FROM equipments e
            LEFT JOIN disability_equipments de ON e.equipment = de.equipment_id
            LEFT JOIN inventory i ON de.equipment_id = i.equipment_id
            WHERE e.id = ?
        ");
        $sql->execute([$id]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }



    public function verifyEquipmentByUserId($userId)
    {
        $sql = $this->connect()->prepare("SELECT * FROM equipments WHERE user_id = ?");
        $sql->execute([$userId]);
        $count = $sql->rowCount();
        return $count;
    }

    public function assignEquipment($userId, $equipment)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            // Update the equipment in the equipments table
            $sql = $conn->prepare("
                UPDATE equipments 
                SET equipment = :equipment, claim_status = '1', date_issued = NOW() 
                WHERE user_id = :user_id
            ");
            $sql->bindParam(':user_id', $userId);
            $sql->bindParam(':equipment', $equipment);
            $sql->execute();

            // Update the inventory stocks and check if stocks reach zero
            $updateStock = $conn->prepare("
                UPDATE inventory 
                SET 
                    stocks = stocks - 1,
                    remarks = CASE WHEN stocks - 1 = 0 THEN 1 ELSE remarks END
                WHERE equipment_id = :equipment
            ");
            $updateStock->bindParam(':equipment', $equipment);
            $updateStock->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function assignDefaultEquipment($userId) {
        $sql = $this->connect()->prepare("INSERT INTO equipments (user_id, equipment, claim_status, date_issued, date_claimed)
        VALUES (:userId, NULL, NULL, NULL, NULL)");

        $sql->bindParam(':userId', $userId, PDO::PARAM_INT);

        $sql->execute();
        return $sql;
    }

    public function deleteEquipment($id, $equipment)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            $sql = $conn->prepare("DELETE FROM equipments WHERE id = :id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();

            $updateStock = $conn->prepare("UPDATE inventory SET stocks = stocks + 1 WHERE equipment_id = :equipment_id");
            $updateStock->bindParam(':equipment_id', $equipment, PDO::PARAM_INT);
            $updateStock->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $this->connect()->rollBack();
        throw $e;
        }
    }

    
    // public function updateEquipment($equipment, $pwdId)
    // {
    //     $sql = $this->connect()->prepare("
    //         UPDATE equipments 
    //         SET equipment = :equipment
    //         WHERE user_id = :pwdId
    //     ");

    //     $sql->bindParam(':equipment', $equipment);
    //     $sql->bindParam(':pwdId', $pwdId, PDO::PARAM_INT);

    //     $sql->execute();

    //     return $sql;
    // }
    public function updateEquipment($newEquipment, $userId)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            $getCurrentEquipment = $conn->prepare("
                SELECT equipment 
                FROM equipments 
                WHERE user_id = :userId
            ");
            $getCurrentEquipment->bindParam(':userId', $userId);
            $getCurrentEquipment->execute();
            $currentEquipment = $getCurrentEquipment->fetchColumn();

            if ($currentEquipment) {
                $incrementStock = $conn->prepare("
                    UPDATE inventory 
                    SET stocks = stocks + 1 
                    WHERE equipment_id = (
                        SELECT equipment FROM equipments WHERE equipment = :currentEquipment LIMIT 1
                    )
                ");
                $incrementStock->bindParam(':currentEquipment', $currentEquipment);
                $incrementStock->execute();
            }

            $updateEquipment = $conn->prepare("
                UPDATE equipments 
                SET equipment = :newEquipment 
                WHERE user_id = :userId
            ");
            $updateEquipment->bindParam(':newEquipment', $newEquipment);
            $updateEquipment->bindParam(':userId', $userId);
            $updateEquipment->execute();

            $decrementStock = $conn->prepare("
                UPDATE inventory 
                SET stocks = stocks - 1 
                WHERE equipment_id = (
                    SELECT equipment FROM equipments WHERE equipment = :newEquipment LIMIT 1
                )
            ");
            $decrementStock->bindParam(':newEquipment', $newEquipment);
            $decrementStock->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function release_claim_equipments($id, $userId) 
    {
        $sql = $this->connect()->prepare("UPDATE equipments SET claim_status = '2', date_claimed = Now(), released_by = :user_id WHERE id = :id");
        $sql->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    /*public function fetchInventoryEquipmentHistory($id)
    {
        $sql = $this->connect()->prepare("
            SELECT 
                equipments.*, 
                users.name AS user_name, 
                users.email AS user_email, 
                inventory.*, 
            FROM equipments
            INNER JOIN users ON equipments.user_id = users.id
            INNER JOIN inventory ON equipments.equipment = inventory.equipment_id
            WHERE equipments.equipment = :equipmentId 
              AND equipments.date_claimed IS NOT NULL
        ");
        $sql->bindParam(':equipmentId', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }*/

}
