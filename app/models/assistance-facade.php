<?php

class AssistanceFacade extends DBConnection
{

    public function fetchAssistance() {
        $sql = $this->connect()->prepare("
            SELECT * 
            FROM assistance 
            ORDER BY 
                CASE 
                    WHEN status = 'Pending' THEN 1
                    ELSE 2
                END,
                applied_date ASC
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC); // Fetch results as an associative array
    }
    

    public function fetchAssistanceById($userId)
    {
        $sql = $this->connect()->prepare("SELECT * FROM assistance WHERE user_id = ? ORDER BY status DESC, applied_date ASC");
        $sql->execute([$userId]);
        return $sql;
    }

    public function verifyUsernameAndPassword($username, $password)
    {
        $sql = $this->connect()->prepare("SELECT username, password FROM users WHERE username = ? AND password = ?");
        $sql->execute([$username, $password]);
        $count = $sql->rowCount();
        return $count;
    }

    public function addAssistance($pwdId, $assistance, $status, $reason, $uploadedFileNames)
    {
        $sql = $this->connect()->prepare("INSERT INTO assistance (user_id, assistance, applied_date, status, remarks, reason, uploaded_requirements, is_claim) VALUES (?, ?, Now(), ?, null, ?, ?, 0)");
        $sql->execute([$pwdId, $assistance, $status, $reason, $uploadedFileNames]);
        return $sql;
    }

    public function updateAssistance($assistance_id, $uploadedFileNames)
    {
        $sql = $this->connect()->prepare("UPDATE assistance SET uploaded_requirements = :uploadFiles WHERE id = :assistance_id");
        $sql->bindParam(':uploadFiles', $uploadedFileNames);
        $sql->bindParam(':assistance_id', $assistance_id);
        $sql->execute();
        return $sql;
    }

    public function fetchAssistanceUpldRequirements($assistance_id) 
    {
        $sql = $this->connect()->prepare(
            "SELECT u.*, a.uploaded_requirements, a.reason 
             FROM assistance a
             INNER JOIN users u ON a.user_id = u.id
             WHERE a.id = ?"
        );
        
        $sql->execute([$assistance_id]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function pendingAssistance($assistanceId, $reason) {
        $remarks = "Application is pending, Please comply your other requirements.";
        $sql = $this->connect()->prepare("UPDATE assistance SET status = 'Pending', approver_reason = :reason, remarks = :remarks WHERE id = :assistanceId");
        $sql->bindParam(':remarks', $remarks);
        $sql->bindParam(':reason', $reason);
        $sql->bindParam(':assistanceId', $assistanceId, PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    public function approveAssistance($assistanceId, $reason)
    {
        $remarks = "Application is approved, Please bring your requirements.";
        $sql = $this->connect()->prepare("UPDATE assistance SET status = 'Approved', approver_reason = :reason, remarks = :remarks WHERE id = :assistanceId");
        $sql->bindParam(':remarks', $remarks);
        $sql->bindParam(':reason', $reason);
        $sql->bindParam(':assistanceId', $assistanceId, PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    public function disapproveAssistance($assistanceId, $reason)
    {
      $remarks = "Application is disapproved, Please try again.";
      $sql = $this->connect()->prepare("UPDATE assistance SET status = 'Disapproved', approver_reason = :reason, remarks = :remarks WHERE id = :assistanceId");
      $sql->bindParam(':remarks', $remarks);
      $sql->bindParam(':reason', $reason);
      $sql->bindParam(':assistanceId', $assistanceId, PDO::PARAM_INT);
      $sql->execute();
      return $sql;
    }

    public function claimAssistance($assistanceId)
    {
        $sql = $this->connect()->prepare("UPDATE assistance SET is_claim = '1', claimed_date = Now() WHERE id = :assistanceId");
        $sql->bindParam(':assistanceId', $assistanceId, PDO::PARAM_INT);
        $sql->execute();
        return $sql;
    }

    public function fetchAssistanceReason($assistance_id)
    {
        $sql = $this->connect()->prepare("SELECT reason FROM assistance WHERE id = ?");
        $sql->execute([$assistance_id]);
        return $sql;
    }

    public function fetchAssistanceDataById($id) {
        $sql = $this->connect()->prepare("SELECT * FROM assistance WHERE id = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

}
