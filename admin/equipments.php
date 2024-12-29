<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

$userId = '';
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

if (isset($_GET['id']) && isset($_GET['release_equipment'])) {
    $id = $_GET['id'];
    $claimEquipment = $equipmentsFacade->release_claim_equipments($id, $userId);
    if ($claimEquipment) {
        $msg = "Issued equipment successfully claimed.";
        header("Location: equipments.php?msg=".$msg);
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
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold">Equipments Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" class="display" style="width:160%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">PWD</th>
                                                <th class="text-center">Equipment Applicable</th>
                                                <th class="text-center">Equipments Remarks</th>
                                                <th class="text-center">Equipment Issued</th>
                                                <th class="text-center">Date Issued</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Date Claimed</th>
                                                <th class="text-center exclude" colspan="2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                            $fetchEquipments = $equipmentsFacade->fetchEquipments();
                                            foreach ($fetchEquipments as $equipments) { ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        $pwdId = $equipments["user_id"];
                                                        $fetchPWDById = $userFacade->fetchPWDById($pwdId);
                                                        foreach ($fetchPWDById as $users) {
                                                            echo $users["first_name"] . ' ' . $users["last_name"];
                                                        } ?>
                                                    </td>
                                                    <td class="scrollable-list">
                                                        <ul>
                                                            <?php 
                                                                $pwdId = $equipments["user_id"];

                                                                $fetchEquipment = isset($equipments["equipment_name"]) ? $equipments["equipment_name"] : null;

                                                                $applicableEqp = $equipmentsFacade->fetchDisabilityEquipmentApplicable($pwdId);

                                                                $rowDisplayedEquipmentNames = [];

                                                                if ($fetchEquipment && !in_array($fetchEquipment, $rowDisplayedEquipmentNames)) {
                                                                    echo '<li>' . htmlspecialchars($fetchEquipment) . '</li>';
                                                                    $rowDisplayedEquipmentNames[] = $fetchEquipment;
                                                                }

                                                                foreach ($applicableEqp as $optEqp) {
                                                                    if (isset($optEqp["equipment_name"]) && !in_array($optEqp["equipment_name"], $rowDisplayedEquipmentNames)) {
                                                                        echo '<li>' . htmlspecialchars($optEqp["equipment_name"]) . '</li>';
                                                                        $rowDisplayedEquipmentNames[] = $optEqp["equipment_name"];
                                                                    }
                                                                }

                                                                if (empty($rowDisplayedEquipmentNames)) {
                                                                    echo '<li>-</li>';
                                                                }
                                                            ?>
                                                        </ul>
                                                    </td>
                                                    <td class="scrollable-list">
                                                        <ul>
                                                            <?php 
                                                                $pwdId = $equipments["user_id"];
                                                                $applicableEqp = $equipmentsFacade->fetchDisabilityEquipmentApplicable($pwdId);

                                                                $displayedEquipmentStatuses = [];

                                                                foreach ($applicableEqp as $optEqp) {
                                                                    if (isset($optEqp["equipment_name"])) {
                                                                        $equipmentName = $optEqp["equipment_name"];
                                                                        $status = "";

                                                                        if (isset($optEqp["stocks"]) && $optEqp["stocks"] > 0) {
                                                                            $status = '<span class="text-success">Available</span>';
                                                                        } else {
                                                                            $status = '<span class="text-danger">Unavailable</span>';
                                                                        }
                                                                        
                                                                        $uniqueKey = $equipmentName . $status;

                                                                        if (!in_array($uniqueKey, $displayedEquipmentStatuses)) {
                                                                            echo '<li>' . $status . '</li>';
                                                                            $displayedEquipmentStatuses[] = $uniqueKey;
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <?= !empty($equipments['equipment_name']) ? htmlspecialchars($equipments['equipment_name']) : '-' ?>
                                                    </td>
                                                    <td><?= !empty($equipments['date_issued']) ? date('F j, Y h:i:s a', strtotime($equipments['date_issued'])) : '-' ?>
                                                    </td>
                                                        
                                                    <td>
                                                        <?= $equipments['claim_status'] === 1 ? 'Pending Release' : ($equipments['claim_status'] === 2 ? 'Released' : '-') ?>
                                                    </td>
                                                    <td>
                                                    <?= !empty($equipments['date_claimed']) ? date('F j, Y h:i:s a', strtotime($equipments['date_claimed'])) : '-' ?>
                                                    </td>
                                                    <td>
                                                        <a href="assign-equipments.php?id=<?= $equipments['id'] ?>" class="btn btn-primary btn-sm <?= !empty($equipments['equipment_id']) ? 'disabled' : '' ?>">
                                                            <span class="fas fa-fw fa-tag"></span> <?= !empty($equipments['equipment_id']) ? 'Issued' : 'Issue'; ?> 
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="equipments.php?id=<?= $equipments['id'] ?>&release_equipment=1" id="claimBtn" class="btn btn-success btn-sm <?= $equipments['claim_status'] === 2 || empty($equipments['equipment']) ? 'disabled' : '' ?>" data-id="<?= $equipments['id'] ?>">
                                                            <span class="fas fa-fw fa-check-circle"></span> <?= $equipments['claim_status'] === '2' ? 'Claimed' : 'Claim' ?>
                                                        </a>
                                                        <a href="update-equipments.php?id=<?= $equipments['id'] ?>" class="btn btn-info btn-sm <?= !empty($equipments['equipment_id']) ? 'disabled' : '' ?>">
                                                            <span class="fas fa-fw fa-edit"></span> Update
                                                        </a>
                                                        <a href="delete-equipments.php?id=<?= $equipments['id'] ?>&equipment=<?= $equipments['equipment'] ?>" data-id="<?= $equipments['id'] ?>" class="delete-equipments btn btn-danger btn-sm">
                                                            <span class="fas fa-fw fa-trash"></span> Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
<script type="text/javascript">
    $(document).ready(function() {
        $(".delete-equipments").click(function(e) {
            e.preventDefault();

            var url = $(this).attr("href");

            var conf = confirm("Are you sure want to delete this data?");
            if (conf) {
                window.location.href = url;
            } else {
                return false;
            }
        });

        $(document).on("click", "#claimBtn", function(e) {
            e.preventDefault();

            var url = $(this).attr("href");

            var conf = confirm("Are you sure want to release into claim?");
            if (conf) {
                window.location.href = url;
            } else {
                return false;
            }
        });
    });
</script>