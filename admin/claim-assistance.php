<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_GET["assistance_id"])) {
    $assistanceId = $_GET["assistance_id"];
    $claimAssistance = $assistanceFacade->claimAssistance($assistanceId);
    if ($claimAssistance) {
        header("Location: assistance.php?msg=Assistance has been claimed successfully!");
    }
}
