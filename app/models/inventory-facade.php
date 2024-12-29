<?php

 class InventoryFacade extends DBConnection
 {
    public function fetchInventoryEquipment() 
    {
        $sql = $this->connect()->prepare("SELECT * FROM inventory");
        $sql->execute();
        return $sql;
    }

    public function fetchInventoryDataByEquipId($uri_query_segment) {
        $sql = $this->connect()->prepare("SELECT * FROM inventory WHERE equipment_id = $uri_query_segment");
        $sql->execute();
        return $sql;
    }

    public function addInventoryEquipment($equipmentName)
    {
            $sql = $this->connect()->prepare("INSERT INTO inventory (equipment_id, equipment_name, stocks, remarks) VALUES (null, :name, '0', '1')");
            $sql->bindParam(':name', $equipmentName);
            $sql->execute();

            return $sql;
    }

    public function fetchInvEquipmentById($equipmentId) 
    {
        $sql = $this->connect()->prepare("SELECT * FROM inventory WHERE equipment_id = :equipmentId");
        $sql->bindParam(':equipmentId', $equipmentId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateInvEquipment($updEquipmentId, $updEquipmentName, $updStocks)
    {
        if ($updStocks == 0) {
            $updRemarks = 1; 
        }

        $sql = $this->connect()->prepare("UPDATE inventory 
            SET equipment_name = :equipment_name,
                remarks = :remarks,
                stocks = :stocks 
            WHERE equipment_id = :equipment_id");

        $sql->bindParam(':equipment_name', $updEquipmentName, PDO::PARAM_STR);
        $sql->bindParam(':remarks', $updRemarks, PDO::PARAM_INT);
        $sql->bindParam(':stocks', $updStocks, PDO::PARAM_INT);
        $sql->bindParam(':equipment_id', $updEquipmentId, PDO::PARAM_INT);

        if ($sql->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateEquipmentById($updEquipmentId, $updEquipmentName) 
    {
        $sql = $this->connect()->prepare("UPDATE inventory SET equipment_name = :equipment_name WHERE equipment_id = :equipment_id");
        $sql->bindParam(':equipment_name', $updEquipmentName, PDO::PARAM_STR);
        $sql->bindParam(':equipment_id', $updEquipmentId, PDO::PARAM_INT);
        if ($sql->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function addStockInvEquipment($updEquipmentId, $userId, $updStocks)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            // Update inventory stock
            $updateSql = $conn->prepare("UPDATE inventory SET stocks = stocks + :stocks WHERE equipment_id = :equipmentId");
            $updateSql->bindParam(':stocks', $updStocks, PDO::PARAM_INT);
            $updateSql->bindParam(':equipmentId', $updEquipmentId, PDO::PARAM_INT);
            $updateSql->execute();

            // Log the transaction with static transaction type
            $logSql = $conn->prepare("
                INSERT INTO inv_stock_logs (equipment_id, qty, incharge_user, transaction_type, transaction_date) 
                VALUES (:equipmentId, :stocks, :userId, 'Add Stock', NOW())
            ");
            $logSql->bindParam(':equipmentId', $updEquipmentId, PDO::PARAM_INT);
            $logSql->bindParam(':stocks', $updStocks, PDO::PARAM_INT);
            $logSql->bindParam(':userId', $userId, PDO::PARAM_INT);
            $logSql->execute();

            $conn->commit();

            return true;
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error in addStockInvEquipment: " . $e->getMessage());
            return false;
        }
    }

    public function fetchStockLogsData() 
    {
        $sql = $this->connect()->prepare("SELECT * FROM inv_stock_logs 
                                            INNER JOIN inventory ON inventory.equipment_id = inv_stock_logs.equipment_id
                                            INNER JOIN users ON users.id = inv_stock_logs.incharge_user 
                                            ORDER BY transaction_date DESC");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function deleteInvEquipment($equipmentId)
    // {
    //     $sql = $this->connect()->prepare("DELETE FROM inventory WHERE equipment_id = $equipmentId");
    //     $sql->execute();
    //     return $sql;
    // }
    public function deleteInvEquipment($equipmentId)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            $sql = $conn->prepare("DELETE FROM disability_equipments WHERE equipment_id = :equipment_id");
            $sql->bindParam(':equipment_id', $equipmentId, PDO::PARAM_INT);
            $sql->execute();

            $sql = $conn->prepare("DELETE FROM inventory WHERE equipment_id = :equipment_id");
            $sql->bindParam(':equipment_id', $equipmentId, PDO::PARAM_INT);
            $sql->execute();

            $conn->commit();

            return true;

        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Error in deleteInvEquipment: " . $e->getMessage());
            return false;
        }
    }

    public function getDisabilityTypesEquimentById($uri_query_segment)
    {
        $sql = $this->connect()->prepare("SELECT * FROM disability_equipments de 
                                        JOIN inventory i ON de.equipment_id = i.equipment_id 
                                        WHERE de.equipment_id = :equipment_id");
        $sql->bindParam(':equipment_id', $uri_query_segment, PDO::PARAM_INT);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateDisabilityTypes($eqpDisabilityId, $editDisabilityType)
    {
        $sql = $this->connect()->prepare("UPDATE disability_equipments SET disability = :editDisabilityType WHERE id = :eqpDisabilityId");
        $sql->bindParam(':editDisabilityType', $editDisabilityType, PDO::PARAM_STR);
        $sql->bindParam(':eqpDisabilityId', $eqpDisabilityId, PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    public function getEqpDisabilityTypeById($editType)
    {
        $sql = $this->connect()->prepare("SELECT * FROM disability_equipments WHERE id = :editType");
        $sql->bindParam(':editType', $editType, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addDisabilityTypes($equipmentId, $disabilityType)
    {
        $sql = $this->connect()->prepare("INSERT INTO disability_equipments (id, equipment_id, disability) VALUES (null, :equipment_id, :disability)");
        $sql->bindParam(':equipment_id', $equipmentId, PDO::PARAM_INT);
        $sql->bindParam(':disability', $disabilityType, PDO::PARAM_STR);
        $sql->execute();
        return $sql;
    }

    public function deleteDisabilityTypesEquipmentById($id) 
    {
        $sql = $this->connect()->prepare("DELETE FROM disability_equipments WHERE id = $id");
        $sql->execute();
        return $sql;
    }

    public function newEquipmentOrderSave($equipment, $order_qty, $arrive_date)
    {
        $sql = $this->connect()->prepare("INSERT INTO new_equipment_order (id, equipment_id, qty, order_date, expected_arrived_date, status, rcvd_date) VALUES(null, :equipmentId, :orderQty, Now(), :arriveDate, null, null)");
        $sql->bindParam(':equipmentId', $equipment, PDO::PARAM_INT);
        $sql->bindParam(':orderQty', $order_qty, PDO::PARAM_INT);
        $sql->bindParam(':arriveDate', $arrive_date, PDO::PARAM_STR);
        $sql->execute();
        return $sql;
    }

    public function updateEquipmentOrderSave($id, $equipment, $order_qty, $arrive_date)
    {
        try {
            $sql = $this->connect()->prepare("UPDATE new_equipment_order SET equipment_id = :equipmentId, qty = :orderQty, expected_arrived_date = :arriveDate WHERE id = :id");
            
            $sql->bindParam(':equipmentId', $equipment, PDO::PARAM_INT);
            $sql->bindParam(':orderQty', $order_qty, PDO::PARAM_INT);
            $sql->bindParam(':arriveDate', $arrive_date, PDO::PARAM_STR);
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            
            // Execute the query
            $result = $sql->execute();
            
            // Return result (boolean) indicating if the update was successful
            return $result;
        } catch (PDOException $e) {
            // Handle any errors (log the error message or display as needed)
            echo "Error updating equipment order: " . $e->getMessage();
            return false;
        }
    }

    public function fetchNewOrderEquipmentData()
    {
        $sql = $this->connect()->prepare("SELECT * FROM new_equipment_order
                                            INNER JOIN inventory ON inventory.equipment_id = new_equipment_order.equipment_id
                                            ORDER BY order_date DESC");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchNewOrderEquipmentHistoryData() {
        $sql = $this->connect()->prepare("
            SELECT 
                new_equipment_order.*, 
                inventory.*, 
                users.*
            FROM 
                new_equipment_order
            INNER JOIN 
                inventory 
            ON 
                inventory.equipment_id = new_equipment_order.equipment_id
            INNER JOIN 
                users 
            ON 
                users.id = new_equipment_order.rcvd_by
            WHERE 
                new_equipment_order.rcvd_date IS NOT NULL
            ORDER BY 
                new_equipment_order.rcvd_date DESC
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as an associative array
    }    

    public function recivedEquipmentOrder($id, $user_id) {
        var_dump($id);
        try {
            // Start transaction
            $conn = $this->connect();
            $conn->beginTransaction();

            // Fetch the order details
            $sql = $conn->prepare("SELECT qty, equipment_id FROM new_equipment_order WHERE id = :id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            $order = $sql->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                throw new Exception("Order not found.");
            }

            $orderQty = $order['qty'];
            $equipmentId = $order['equipment_id'];

            // Update inventory stocks
            $sql = $conn->prepare("UPDATE inventory SET stocks = stocks + :orderQty, remarks = 0 WHERE equipment_id = :equipmentId");
            $sql->bindParam(':orderQty', $orderQty, PDO::PARAM_INT);
            $sql->bindParam(':equipmentId', $equipmentId, PDO::PARAM_INT);
            $sql->execute();

            // Update new_equipment_order table
            $sql = $conn->prepare("UPDATE new_equipment_order SET status = 1, rcvd_date = NOW(), rcvd_by = :user_id WHERE id = :id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $sql->execute();

            // Commit transaction
            $conn->commit();

            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($conn) {
                $conn->rollBack();
            }
            error_log("Error in recivedEquipmentOrder: " . $e->getMessage());
            return false;
        }
    }

    public function getNewEquipmentOrder($id) {
        $sql = $this->connect()->prepare("SELECT * FROM new_equipment_order WHERE id = :id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchInventoryEquipmentHistory($id) {
        $sql = $this->connect()->prepare("
            SELECT 
                eq.*, 
                inv.*, 
                u1.first_name AS claimed_by_first_name, 
                u1.last_name AS claimed_by_last_name, 
                u2.first_name AS user_first_name, 
                u2.last_name AS user_last_name
            FROM equipments AS eq
            INNER JOIN inventory AS inv ON eq.equipment = inv.equipment_id
            INNER JOIN users AS u1 ON u1.id = eq.released_by
            INNER JOIN users AS u2 ON u2.id = eq.user_id 
            WHERE eq.equipment = :id AND eq.date_claimed IS NOT NULL
        ");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }    
    
 }
?>