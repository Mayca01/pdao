<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

// Check if the form was submitted
if (isset($_POST["update"])) {

    // Initialize variables
    $invalid = [];

    // Retrieve form data
    $pwdId = $_POST["pwd_id"];
    $equipment = $_POST["equipment"];

    // Validate form data
    if (empty($equipment)) array_push($invalid, 'Equipment should not be empty!');

    // If there are no validation errors, proceed with insertion
    if (empty($invalid)) {
        // Assuming $userFacade is already defined and connected to the database
        $updateEquipment = $equipmentsFacade->updateEquipment($equipment, $pwdId);

        if ($updateEquipment) {
            header("Location: equipments.php?msg=Equipment has been updated successfully!");
            exit();
        } else {
            array_push($invalid, "Database update failed!");
        }
    }

    // Display validation errors if any
    foreach ($invalid as $error) {
        echo "<p>Error: $error</p>";
    }
}
