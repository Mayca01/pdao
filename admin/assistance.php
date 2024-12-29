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

if (isset($_POST['approveBtn'])) {
    $assistanceId = htmlspecialchars($_POST["assistance_id"]);
    $reason = htmlspecialchars($_POST["reason_approved"]);
    $saveApproveReason = $assistanceFacade->approveAssistance($assistanceId, $reason);
    if ($saveApproveReason) {
        header("Location: assistance.php?msg=Assistance has been approved successfully!");
    }
}

if (isset($_POST['disapproveBtn'])) {
    $assistanceId = htmlspecialchars($_POST["assistance_id"]);
    $reason = htmlspecialchars($_POST["reason_disapproved"]);
    $saveDisapproveReason = $assistanceFacade->disapproveAssistance($assistanceId, $reason);
    if ($saveDisapproveReason) {
        header("Location: assistance.php?msg=Assistance has been disapproved successfully!");
    }
}

if (isset($_POST['pendingBtn'])) {
    $assistanceId = htmlspecialchars($_POST["assistance_id"]);
    $reason = htmlspecialchars($_POST["reason_pending"]);
    $savePendingReason = $assistanceFacade->pendingAssistance($assistanceId, $reason);
    if ($savePendingReason) {
        header("Location: assistance.php?msg=assistance has been pending successfully");
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
        <li class="nav-item active">
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
                                <h6 class="m-0 font-weight-bold">Assistance Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" style="width:200%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Age</th>
                                                <th class="text-center">Address</th>
                                                <th class="text-center">Disability</th>
                                                <th class="text-center">Assistance Type</th>
                                                <th class="text-center exclude">Requirements & Reason</th>
                                                <th class="text-center exclude">Action</th>
                                                <th class="text-center">Application Date</th>
                                                <th class="text-center exclude">Action</th>
                                                <th class="text-center">Date Claimed</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                            $fetchAssistanceById = $assistanceFacade->fetchAssistance();
                                            foreach ($fetchAssistanceById as $assistance) { ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        $pwdId = $assistance["user_id"];
                                                        $fetchPWDById = $userFacade->fetchPWDById($pwdId);
                                                        foreach ($fetchPWDById as $users) {
                                                            echo $users["first_name"] . ' ' . $users["last_name"];
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $pwdId = $assistance["user_id"];
                                                        $fetchPWDById = $userFacade->fetchPWDById($pwdId);
                                                        foreach ($fetchPWDById as $users) {
                                                            echo $users["age"];
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $pwdId = $assistance["user_id"];
                                                        $fetchPWDById = $userFacade->fetchPWDById($pwdId);
                                                        foreach ($fetchPWDById as $users) {
                                                            echo $users["address"];
                                                        } ?>
                                                    </td>
                                                        <td>
                                                            <?php
                                                            $pwdId = $assistance["user_id"];
                                                            $fetchPWDById = $userFacade->fetchPWDById($pwdId);
                                                            foreach ($fetchPWDById as $users) {
                                                                echo $users["disability"];
                                                            } ?>
                                                        </td>
                                                        <td><?= $assistance["assistance"] ?></td>
                                                        <td>
                                                            <a href="assistance-requirements.php?assistance_id=<?= $assistance["id"] ?>" class="btn btn-secondary btn-sm text-left">
                                                                <span class="fas fa-fw fa-eye"></span> View
                                                            </a>
                                                        </td><?php if ($assistance["status"] !== "Pending") { ?>
                                                        <td>
                                                            <?= $assistance["status"] === "Approved" ? "Approved" : "Disapproved"; ?>
                                                        </td>
                                                        <?php } else { ?>
                                                        <td>
                                                            <!-- Pending Button -->
                                                            <button 
                                                                class="btn btn-warning btn-sm" 
                                                                data-toggle="modal" 
                                                                data-target="#pendingModal-<?= $assistance["id"] ?>" 
                                                                data-id="<?= $assistance["id"] ?>">
                                                                <span class="fas fa-fw fa-clock"></span> Pending
                                                            </button>

                                                            <!-- Approve Button -->
                                                            <button 
                                                                class="btn btn-success btn-sm" 
                                                                data-toggle="modal" 
                                                                data-target="#approveModal-<?= $assistance["id"] ?>" 
                                                                data-id="<?= $assistance["id"] ?>">
                                                                <span class="fas fa-fw fa-thumbs-up"></span> Approve
                                                            </button>

                                                            <!-- Disapprove Button -->
                                                            <button 
                                                                class="btn btn-danger btn-sm" 
                                                                data-toggle="modal" 
                                                                data-target="#disapproveModal-<?= $assistance["id"] ?>" 
                                                                data-id="<?= $assistance["id"] ?>">
                                                                <span class="fas fa-fw fa-thumbs-down"></span> Disapprove
                                                            </button>
                                                        </td>

                                                        <!-- Pending Modal -->
                                                        <div 
                                                            class="modal fade" 
                                                            id="pendingModal-<?= $assistance["id"] ?>" 
                                                            tabindex="-1" 
                                                            aria-labelledby="pendingModalLabel-<?= $assistance["id"] ?>" 
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form action="assistance.php" method="POST">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Pending Assistance</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span>&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="assistance_id" value="<?= $assistance["id"] ?>">
                                                                            <div class="form-floating mb-2">
                                                                                <textarea 
                                                                                    name="reason_pending" 
                                                                                    id="reason_pending" 
                                                                                    class="form-control" 
                                                                                    placeholder="Enter reason..." required></textarea>
                                                                                <label for="reason_pending">Reason</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                               <span class="fas fa-fw fa-times"></span> Cancel</button>
                                                                            <button type="submit" class="btn btn-success" name="pendingBtn">
                                                                               <span class="fas fa-fw fa-clock"></span> Pending
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Approve Modal -->
                                                        <div 
                                                            class="modal fade" 
                                                            id="approveModal-<?= $assistance["id"] ?>" 
                                                            tabindex="-1" 
                                                            aria-labelledby="approveModalLabel-<?= $assistance["id"] ?>" 
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form action="assistance.php" method="POST">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Approve Assistance</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span>&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="assistance_id" value="<?= $assistance["id"] ?>">
                                                                            <div class="form-floating mb-2">
                                                                                <textarea 
                                                                                    name="reason_approved" 
                                                                                    id="reason_approved" 
                                                                                    class="form-control" 
                                                                                    placeholder="Enter reason..." required></textarea>
                                                                                <label for="reason_approved">Reason</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                               <span class="fas fa-fw fa-times"></span> Cancel</button>
                                                                            <button type="submit" class="btn btn-success" name="approveBtn">
                                                                               <span class="fas fa-fw fa-thumbs-up"></span> Approve
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Disapprove Modal -->
                                                        <div 
                                                            class="modal fade" 
                                                            id="disapproveModal-<?= $assistance["id"] ?>" 
                                                            tabindex="-1" 
                                                            aria-labelledby="disapproveModalLabel-<?= $assistance["id"] ?>" 
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form action="assistance.php" method="POST">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Disapprove Assistance</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span>&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="form-floating mb-2">
                                                                                <textarea 
                                                                                    name="reason_disapproved" 
                                                                                    id="reason_disapproved" 
                                                                                    class="form-control" 
                                                                                    placeholder="Enter reason..." required></textarea>
                                                                                    <label for="reason_disapproved"> Reason</label>
                                                                            </div>
                                                                            <input type="hidden" name="assistance_id" value="<?= $assistance["id"] ?>">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                                <span class="fas fa-fw fa-times"></span> Cancel</button>
                                                                            <button type="submit" class="btn btn-danger" name="disapproveBtn">  <span class="fas fa-fw fa-thumbs-down"></span> Disapprove
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    <td style="width: 25%;">
                                                        <?php 
                                                        echo !empty($assistance["applied_date"]) && $assistance["applied_date"] != "0000-00-00 00:00:00" 
                                                            ? date('F j, Y h:i:s a', strtotime($assistance["applied_date"])) 
                                                            : '-'; 
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if (empty($assistance["is_claim"]) || $assistance["is_claim"] === 0) { ?>
                                                            <a href="claim-assistance.php?assistance_id=<?= $assistance["id"] ?>" 
                                                               id="claimAssistanceBtn" 
                                                               data-id="<?= $assistance['id']; ?>" 
                                                               class="btn btn-sm btn-primary <?= ($assistance['status'] == 'Pending' || $assistance['status'] == 'Disapproved') ? 'disabled' : '' ?>">
                                                                <span class="fas fa-fw fa-check-circle"></span> Release
                                                            </a>
                                                        <?php } else { ?>
                                                            <span class="text-success">Released</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php echo !empty($assistance["claimed_date"]) ? date('F j, Y h:i:s a', strtotime($assistance['claimed_date'])) : '-' ?>
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
    $(document).on("click", "#claimAssistanceBtn", function(e) {
        e.preventDefault();

        var url = $(this).attr("href");

        var conf = confirm("Do you want to proceed to confirm the claim?");
        if (conf) {
            window.location.href = url;
        } else {
            return false;
        }
    });
</script>