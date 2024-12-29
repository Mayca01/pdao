<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_GET["assistance_id"])) {
    $assistanceId = $_GET["assistance_id"];
    $approveAssistance = $assistanceFacade->disapproveAssistance($assistanceId);
    if ($approveAssistance) {
        header("Location: assistance.php?msg=Assistance has been disapproved successfully!");
    }
}
