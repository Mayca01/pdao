<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
}

if (isset($_SESSION["first_name"])) {
    $firstName = $_SESSION["first_name"];
}

if (isset($_SESSION["last_name"])) {
    $lastName = $_SESSION["last_name"];
}

if (isset($_GET["msg"])) {
    array_push($success, $_GET["msg"]);
}

$id = null;
if (isset($_GET['id']) && is_numeric($_GET["id"])) {
    $id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');
}

if (isset($_POST["assign_equipment"])) {
    $pwdId = $_POST["pwd_id"];
    $equipment = $_POST["equipment"];

    if (empty($equipment)) {
        array_push($invalid, 'Equipment should not be empty!');
    }

    $checkUserValidation = $userFacade->checkValidatedUser($pwdId);
    $userValidated = $checkUserValidation[0]['user_validated'] ?? null;
    if (empty($userValidated)) {
        $msg = "Unable to assign equipment: user not validated.";
        header("Location: equipments.php?msg=" . urlencode($msg));
        exit;
    }

    $assignEquipment = $equipmentsFacade->assignEquipment($pwdId, $equipment);
    if ($assignEquipment) {
        header("Location: equipments.php?msg=" . urlencode("Equipment has been assigned successfully!"));
        exit;
    } else {
        $msg = "Failed to assign equipment.";
        header("Location: equipments.php?msg=" . urlencode($msg));
        exit;
    }
    
}



?>

<div id="wrapper">
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #315a39;">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="sidebar-brand-text mx-3">PDAO</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="admin.php">
                <i class="fas fa-fw fa-user"></i>
                <span>Admin</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="pwd.php">
                <i class="fas fa-solid fa-user"></i>
                <span>PWD</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="assistance.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Assistance</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="equipments.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Assign Equipments</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="inventory-equipments.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Inventory Equipments</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="informations.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Announcements</span>
            </a>
        </li>
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $firstName . ' ' . $lastName ?></span>
                            <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name=<?= $firstName . '+' . $lastName ?>">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
             
                <?php include('.././errors.php') ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        $fetchEquipmentById = $equipmentsFacade->fetchEquipmentById($id); //fetchEquipmentById
                        foreach ( $fetchEquipmentById as $equipments) {
                            $pwdId = $equipments["user_id"];
                        ?>
                            <div class="card shadow mb-4">
                                <div class="card-header d-flex align-items-center">
                                    <a href="equipments.php" class="mr-2"> <!-- Adds spacing between link and heading -->
                                        <span class="fas fa-fw fa-arrow-left"></span> Back
                                    </a>
                                    <span class="mx-2"> | </span>
                                    <h6 class="m-0 font-weight-bold">Assign Equipments</h6>
                                </div>
                                <form action="assign-equipments.php" method="post">
                                    <div class="card-body">
                                        <div class="form-floating">
                                            <select class="form-control" id="pwd" name="pwd_id" disabled="disabled">
                                                <?php
                                                $fetchUser = $userFacade->fetchPWDById($pwdId);
                                                foreach ($fetchUser as $user) {
                                                ?>
                                                    <option value="<?= $user["id"] ?>"><?= $user["first_name"] . ' ' . $user["last_name"] ?></option>
                                                <?php } ?>
                                            </select>
                                            <label for="pwd">PWD</label>
                                        </div>
                                        <div class="form-floating">
                                        <!-- SElect input here -->
                                        <select class="form-control" id="equipment" name="equipment">
                                            <option value="">--- Choose Equipment ---</option>
                                            <?php
                                                // Fetch the equipment details
                                                $res = $equipmentsFacade->fetchEquipmentById($id);
                                                $fetchEquipment = null;

                                                foreach ($res as $row) {
                                                    $fetchEquipment = $row['equipment']; // Fetch the currently selected equipment
                                                }

                                                // Fetch applicable disability equipment
                                                $res1 = $equipmentsFacade->fetchDisabilityEquipmentApplicable($pwdId);

                                                // Prevent duplicates by tracking displayed equipment IDs
                                                $displayedEquipment = [];

                                                foreach ($res1 as $row):
                                                    if (isset($row['equipment_name']) && !in_array($row['equipment_id'], $displayedEquipment)) {
                                                        $displayedEquipment[] = $row['equipment_id'];
                                                        $isDisabled = ($row['remarks'] === 1) ? 'disabled' : ''; // Disable if remarks is 0
                                                        $remarks = ($row['remarks'] === 1) ? 'Not Available' : ''; // Disable if remarks is 0
                                            ?>
                                                        <option value="<?= htmlspecialchars($row['equipment_id']) ?>" <?= $isDisabled ?>>
                                                            <?= htmlspecialchars($row['equipment_name']) ?> <?= !empty($remarks) ? "(".$remarks.")" : '' ?>
                                                        </option>
                                            <?php
                                                    } 
                                                endforeach; 
                                            ?>
                                        </select>
                                        <label for="equipment">Equipment</label>
                                        </div>
                                        <input type="hidden" name="pwd_id" value="<?= $equipments["user_id"] ?>">
                                        <button class="w-100 btn btn-lg btn-primary my-3" type="submit" name="assign_equipment">Assign Equipment</button>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; PDAO 2024</span>
                </div>
            </div>
        </footer>
    </div>
</div>
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="../sign-out.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<?php include realpath(__DIR__ . '/../app/layout/admin-footer.php') ?>