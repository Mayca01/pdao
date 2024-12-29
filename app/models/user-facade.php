<?php

class UserFacade extends DBConnection
{

    public function fetchUsers()
    {
        $sql = $this->connect()->prepare("SELECT * FROM users WHERE user_type = 0 ORDER BY id DESC, user_validated = 0 DESC ");
        $sql->execute();
        return $sql;
    }

    public function fetchUsersById($userId)
    {
        $sql = $this->connect()->prepare("SELECT * FROM users WHERE id = :id");
        $sql->bindParam(':id', $userId);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchAdmin()
    {
        $sql = $this->connect()->prepare("SELECT * FROM users WHERE user_type = 1");
        $sql->execute();
        return $sql;
    }

    public function fetchAdminById($user_id)
    {
        $sql = $this->connect()->prepare("SELECT * FROM users WHERE user_type = 1 && id = $user_id");
        $sql->execute();
        return $sql;
    }

    public function fetchPWDById($pwdId)
    {
        $sql = $this->connect()->prepare("SELECT * FROM users WHERE id = ?");
        $sql->execute([$pwdId]);
        return $sql;
    }

    public function fetchPWDByBarangay($barangay)
    {
        $sql = $this->connect()->prepare("SELECT * FROM users WHERE barangay = ?");
        $sql->execute([$barangay]);
        return $sql;
    }

    public function verifyUsernameAndPassword($username, $password)
    {
        $sql = $this->connect()->prepare("SELECT username, password FROM users WHERE username = ? AND password = ?");
        $sql->execute([$username, $password]);
        $count = $sql->rowCount();
        return $count;
    }

    public function fetchBarangay($barangay)
    {
        $sql = $this->connect()->prepare("SELECT barangay FROM users WHERE barangay = ?");
        $sql->execute([$barangay]);
        $count = $sql->rowCount();
        return $count;
    }

    /*public function signUp($firstName, $lastName, $age, $barangay, $address, $occupation, $contactPerson, $contactNumber, $email, $disability, $medicalInformation, $username, $password, $status)
    {
        // Prepare the SQL statement
        $sql = $this->connect()->prepare("INSERT INTO users(first_name, last_name, age, barangay, address, occupation, contact_person, contact_number, email, disability, medical_information, username, password, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$firstName, $lastName, $age, $barangay, $address, $occupation, $contactPerson, $contactNumber, $email, $disability, $medicalInformation, $username, $password, $status]);
        return $sql;
    }*/

    public function signUp($firstName, $lastName, $age, $barangay, $address, $occupation, $contactPerson, $contactNumber, $email, $finalDisability, $medicalInformation, $username, $password, $status) 
    {
        try {
            // Ensure $finalDisability is an array (in case it's a string like "Blindness, Stroke")
            if (is_string($finalDisability)) {
                $finalDisability = explode(', ', $finalDisability); // Convert string to array
            }

            // Log to verify $finalDisability
            printf("Final Disability Array: " . print_r($finalDisability, true));

            // Database connection
            $db = $this->connect();
            if (!$db) {
                throw new Exception("Database connection failed.");
            }

            // Begin transaction
            //$db->beginTransaction();

            // Step 1: Insert user data into the `users` table
            $sql = $db->prepare("
                INSERT INTO users (
                    first_name, last_name, age, barangay, address, occupation, 
                    contact_person, contact_number, email, disability, 
                    medical_information, username, password, status
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $sql->execute([
                $firstName, $lastName, $age, $barangay, $address, $occupation,
                $contactPerson, $contactNumber, $email, implode(', ', $finalDisability),
                $medicalInformation, $username, $password, $status
            ]);
            
            // Get the last inserted user ID
            /*$userId = $db->lastInsertId();
            printf("User ID: " . $userId);

            // Step 2: Check disabilities in `disability_equipments` and map to `equipment_id`
            $equipmentIds = [];
            foreach ($finalDisability as $disability) {
                $query = $db->prepare("SELECT equipment_id FROM disability_equipments WHERE disability = ?");
                $query->execute([$disability]);

                if ($query->rowCount() > 0) {
                    $result = $query->fetch();
                    $equipmentIds[] = $result['equipment_id'];
                } else {
                    $equipmentIds[] = null; // Disability not found, no equipment
                }
            }

            // Log equipment IDs
            printf("Equipment IDs: " . print_r($equipmentIds, true));

            // Step 3: Insert equipment_id to equipment mapping from disability_equipment
            $equipmentSql = $db->prepare("INSERT INTO equipments (user_id, equipment) VALUES (?, ?)");
            foreach ($equipmentIds as $equipmentId) {
                $equipmentSql->execute([
                    $userId,
                    $equipmentId // Use NULL if no equipment_id found
                ]);
            }

            // Commit the transaction
            $db->commit();*/

            return true;

        } catch (Exception $e) {
            // Rollback the transaction only if it was started
            /*if ($db->inTransaction()) {
                $db->rollBack();
            }*/
            printf("Error during sign-up: " . $e->getMessage());
            return false;
        }
    }


    public function addAdmin($userType, $firstName, $lastName, $email, $username, $password)
    {
        // Prepare the SQL statement
        $sql = $this->connect()->prepare("INSERT INTO users(user_type, first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->execute([$userType, $firstName, $lastName, $email, $username, $password]);
        return $sql;
    }

    public function signIn($username, $password)
    {
        $sql = $this->connect()->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $sql->execute([$username, $password]);
        return $sql;
    }

    public function deletePwd($pwdId)
    {
        try {
            // Start a transaction
            $conn = $this->connect();
            $conn->beginTransaction();

            // Retrieve user information to get file paths
            $sqlUser = $conn->prepare("SELECT medical_information FROM users WHERE id = :pwdId");
            $sqlUser->bindParam(':pwdId', $pwdId, PDO::PARAM_INT);
            $sqlUser->execute();
            $user = $sqlUser->fetch(PDO::FETCH_ASSOC);

            // Delete the user's uploaded medical information files
            if ($user && !empty($user['medical_information'])) {
                $medicalFiles = explode(',', $user['medical_information']);
                foreach ($medicalFiles as $file) {
                    $filePath = './public/img/medical-informations/' . trim($file); // Ensure file path is trimmed
                    if (file_exists($filePath)) {
                        unlink($filePath); // Delete the file
                    }
                }
            }

            // Retrieve and delete uploaded requirement files from the assistance table
            $sqlAssistance = $conn->prepare("SELECT uploaded_requirements FROM assistance WHERE user_id = :pwdId");
            $sqlAssistance->bindParam(':pwdId', $pwdId, PDO::PARAM_INT);
            $sqlAssistance->execute();
            $assistance = $sqlAssistance->fetch(PDO::FETCH_ASSOC);

            if ($assistance && !empty($assistance['uploaded_requirements'])) {
                $requirementFiles = explode(',', $assistance['uploaded_requirements']);
                foreach ($requirementFiles as $file) {
                    $filePath = './public/img/requirements/uploads/' . trim($file); // Ensure file path is trimmed
                    if (file_exists($filePath)) {
                        unlink($filePath); // Delete the file
                    }
                }
            }

            // Delete related data from the `equipment` table
            $sqlEquipment = $conn->prepare("DELETE FROM equipments WHERE user_id = :pwdId");
            $sqlEquipment->bindParam(':pwdId', $pwdId, PDO::PARAM_INT);
            $sqlEquipment->execute();

            // Delete related data from the `assistance` table
            $sqlAssistanceDelete = $conn->prepare("DELETE FROM assistance WHERE user_id = :pwdId");
            $sqlAssistanceDelete->bindParam(':pwdId', $pwdId, PDO::PARAM_INT);
            $sqlAssistanceDelete->execute();

            // Delete the user from the `users` table
            $sqlUserDelete = $conn->prepare("DELETE FROM users WHERE id = :pwdId");
            $sqlUserDelete->bindParam(':pwdId', $pwdId, PDO::PARAM_INT);
            $sqlUserDelete->execute();

            // Commit the transaction
            $conn->commit();

            return true;
        } catch (Exception $e) {
            // Rollback the transaction on error and rethrow the exception
            $conn->rollBack();
            throw new Exception("Failed to delete user and related data: " . $e->getMessage());
        }
    }


    public function updateAdmin($firstName, $lastName, $username, $password, $userId)
    {
        // Prepare the SQL query with placeholders
        $sql = $this->connect()->prepare("
        UPDATE users 
        SET first_name = :firstName, 
            last_name = :lastName, 
            username = :username, 
            password = :password 
        WHERE id = :userId
    ");

        // Bind parameters to the placeholders
        $sql->bindParam(':firstName', $firstName);
        $sql->bindParam(':lastName', $lastName);
        $sql->bindParam(':username', $username);
        $sql->bindParam(':password', $password);
        $sql->bindParam(':userId', $userId, PDO::PARAM_INT);

        // Execute the query
        $sql->execute();

        return $sql;
    }

    public function updatePwd($firstName, $lastName, $age, $barangay, $address, $occupation, $contactPerson, $contactNumber, $pwdId)
    {
        // Prepare the SQL query with placeholders
        $sql = $this->connect()->prepare("
        UPDATE users 
        SET first_name = :firstName, 
            last_name = :lastName, 
            age = :age, 
            barangay = :barangay, 
            address = :address, 
            occupation = :occupation, 
            contact_person = :contactPerson,
            contact_number = :contactNumber
        WHERE id = :pwdId
    ");

        // Bind parameters to the placeholders
        $sql->bindParam(':firstName', $firstName);
        $sql->bindParam(':lastName', $lastName);
        $sql->bindParam(':age', $age);
        $sql->bindParam(':barangay', $barangay);
        $sql->bindParam(':address', $address);
        $sql->bindParam(':occupation', $occupation);
        $sql->bindParam(':contactPerson', $contactPerson);
        $sql->bindParam(':contactNumber', $contactNumber);
        $sql->bindParam(':pwdId', $pwdId, PDO::PARAM_INT);

        // Execute the query
        $sql->execute();

        return $sql;
    }

    public function fetchUserInfoByInput($term)
    {
        $sql = $this->connect()->prepare("SELECT id, first_name, last_name, user_id FROM users WHERE first_name LIKE ? OR last_name LIKE ? OR user_id LIKE ?");
        $sql->execute(['%' . $term . '%', '%' . $term . '%']);
        $results = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    }

    public function getUserByEmail($email) 
    {
        $sql = $this->connect()->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $sql->execute([$email]);
        $results = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function saveResetToken($email, $resetToken) 
    {
        $sql = $this->connect()->prepare("UPDATE users SET reset_token = :token, reset_token_expires = NOW() + INTERVAL 1 HOUR WHERE email = :email");
        $sql->bindParam(':token', $resetToken);
        $sql->bindParam(':email', $email);
        $sql->execute();
        return $sql;
    }

    public function getEmailByToken($token)
    {
        $sql = $this->connect()->prepare("SELECT email FROM users WHERE reset_token = ?");
        $sql->execute([$token]);
        $results = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function updatePassword($email, $pw)
    {
        $sql = $this->connect()->prepare("UPDATE users SET password = :password WHERE email = :email");
        $sql->bindParam(':password', $pw, PDO::PARAM_STR);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->execute();
        return $sql;
    }

    public function invalidateToken($token)
    {
        $sql = $this->connect()->prepare("UPDATE users SET reset_token = null, reset_token_expires = null 
                WHERE reset_token = :token");
        $sql->bindParam(':token', $token);
        $sql->execute();
        return $sql;
    }

    public function checkValidatedUser($userId) {
        $sql = $this->connect()->prepare("SELECT user_validated FROM users WHERE id = :userId");
        $sql->bindParam(':userId', $userId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validateUser($userId) {
        try {
            // Start the transaction
            $conn = $this->connect();
            $conn->beginTransaction();  // Begin the transaction
            
            // Update the user's validation status
            $sqlUpdate = $conn->prepare("UPDATE users SET user_validated = '1' WHERE id = :userId");
            $sqlUpdate->bindParam(':userId', $userId, PDO::PARAM_INT);
            $sqlUpdate->execute();
            
            // Insert the equipment data
            $sqlInsert = $conn->prepare("INSERT INTO equipments (user_id, equipment, claim_status, date_issued, date_claimed)
                                         VALUES (:userId, NULL, NULL, NULL, NULL)");
            $sqlInsert->bindParam(':userId', $userId, PDO::PARAM_INT);
            $sqlInsert->execute();
            
            // Commit the transaction
            $conn->commit();
            
            return true; // Return true if both operations were successful
        } catch (PDOException $e) {
            // Catch any PDO exceptions and roll back the transaction if an error occurs
            $conn->rollBack();  // Rollback the transaction if something goes wrong
            error_log('Error in validateUser: ' . $e->getMessage());  // Log the error message for debugging purposes
            return false; // Return false if there was an error
        } catch (Exception $e) {
            // Catch any other exceptions
            $conn->rollBack();  // Rollback the transaction
            error_log('Error in validateUser: ' . $e->getMessage());  // Log the error message
            return false; // Return false if there was an error
        }
    }



    public function cancelValidateUser($userId) {
        $sql = $this->connect()->prepare("UPDATE users set user_validated = null WHERE id = :userId");
        $sql->bindParam(':userId', $userId, PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }
}