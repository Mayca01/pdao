<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

// Check if the form was submitted
if (isset($_POST["update"])) {

    // Initialize variables
    $invalid = [];

    // Retrieve form data
    $pwdId = $_POST["pwd_id"];
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $age = $_POST["age"];
    $barangay = $_POST["barangay"];
    $address = $_POST["address"];
    $occupation = $_POST["occupation"];
    $contactPerson = $_POST["contact_person"];
    $contactNumber = $_POST["contact_number"];
    //$disability = isset($_POST["disability"]) ? $_POST["disability"] : 'None';
    //$oldMedicalInformation = $_POST["old_medical_information"];

    // Handle file upload
    //$medicalInformation = null;
    /*if (!empty($_FILES["medical_information"]["name"])) {
        // Handle file upload
        $file = $_FILES["medical_information"];
        $fileName = $_FILES["medical_information"]["name"];
        $fileTmpName = $_FILES["medical_information"]["tmp_name"];
        $fileSize = $_FILES["medical_information"]["size"];
        $fileError = $_FILES["medical_information"]["error"];
        $fileType = $_FILES["medical_information"]["type"];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'jpeg', 'png'); // Allowed file extensions

        if (in_array($fileActualExt, $allowed)) {
            if ($fileSize <= 5000000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = '.././public/img/medical-informations/' . $fileNameNew;
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $medicalInformation = $fileDestination;
                } else {
                    array_push($invalid, "Error uploading file!");
                }
            } else {
                array_push($invalid, "File size should not exceed 5MB!");
            }
        } else {
            array_push($invalid, "File type is not supported!");
        }
    } else {
        // No file uploaded, set $medicalInformation as null or default path
        $medicalInformation = $oldMedicalInformation; // Fetch this from the database based on user or record ID
    }*/

    // Validate form data
    if (empty($firstName)) array_push($invalid, 'First Name should not be empty!');
    if (empty($lastName)) array_push($invalid, 'Last Name should not be empty!');
    if (empty($age)) array_push($invalid, 'Age should not be empty!');
    if (empty($barangay)) array_push($invalid, 'Barangay should not be empty!');
    if (empty($address)) array_push($invalid, 'Address should not be empty!');
    if (empty($occupation)) array_push($invalid, 'Occupation should not be empty!');
    if (empty($contactPerson)) array_push($invalid, 'Contact Person should not be empty!');
    //if (empty($disability) || $disability === 'None') array_push($invalid, 'Type of Disability should not be empty!');

    // If there are no validation errors, proceed with insertion
    if (empty($invalid)) {
        // Assuming $userFacade is already defined and connected to the database
        $signUp = $userFacade->updatePwd($firstName, $lastName, $age, $barangay, $address, $occupation, $contactPerson, $contactNumber, $pwdId);

        if ($signUp) {
            header("Location: pwd.php?msg=PWD has been updated successfully!");
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
