<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

// Check if the form was submitted
if (isset($_POST["update"])) {

    // Initialize variables
    $invalid = [];

    // Retrieve form data
    $informationId = $_POST["information_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $oldImage = $_POST["old_image"];

    // Handle file upload
    $medicalInformation = null;
    if (!empty($_FILES["image"]["name"])) {
        // Handle file upload
        $file = $_FILES["image"];
        $fileName = $_FILES["image"]["name"];
        $fileTmpName = $_FILES["image"]["tmp_name"];
        $fileSize = $_FILES["image"]["size"];
        $fileError = $_FILES["image"]["error"];
        $fileType = $_FILES["image"]["type"];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'jpeg', 'png'); // Allowed file extensions

        if (in_array($fileActualExt, $allowed)) {
            if ($fileSize <= 5000000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = '.././public/img/informations/' . $fileNameNew;
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $image = $fileDestination;
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
        $image = $oldImage; // Fetch this from the database based on user or record ID
    }

    // Validate form data
    if (empty($title)) array_push($invalid, 'Title should not be empty!');
    if (empty($description)) array_push($invalid, 'Description should not be empty!');

    // If there are no validation errors, proceed with insertion
    if (empty($invalid)) {
        // Assuming $userFacade is already defined and connected to the database
        $updateInformation = $informationsFacade->updateInformation($title, $image, $description, $informationId);

        if ($updateInformation) {
            header("Location: informations.php?msg=Information has been updated successfully!");
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
