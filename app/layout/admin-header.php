<?php

// Start session management and output buffering
session_start();
ob_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_name']) || !isset($_SESSION['last_name'])) {
    header("location: ../index.php");
    exit;
}

// Array to store messages
$invalid = array();
$success = array();
$warning = array();
$info = array();

// Include necessary files for database connectivity and facade classes
include(__DIR__ . '/../../config/db/connector.php');
include(__DIR__ . '/../../app/models/user-facade.php');
include(__DIR__ . '/../../app/models/assistance-facade.php');
include(__DIR__ . '/../../app/models/equipments-facade.php');
include(__DIR__ . '/../../app/models/informations-facade.php');
include(__DIR__ . '/../../app/models/inventory-facade.php');
    
$userFacade = new UserFacade;
$assistanceFacade = new AssistanceFacade;
$equipmentsFacade = new EquipmentsFacade;
$informationsFacade = new InformationsFacade;
$inventoryFacade = new InventoryFacade;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Appworks Co.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href=".././vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href=".././public/css/sb-admin-2.min.css">
    <link rel="stylesheet" href=".././public/css/style.css">
    <title>PDAO | Information System</title>
</head>

<style>
    .dataTables_wrapper .dt-buttons {
        margin-bottom: 10px;
    }
</style>

<body>