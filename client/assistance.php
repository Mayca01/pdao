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
        <li class="nav-item active">
            <a class="nav-link" href="assistance.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Assistance</span>
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
                                <a href="apply-assistance.php" class="btn btn-primary m-0">Apply Assistance</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-custom display" style="width:100%">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Assistance Type</th>
                                                <th>Status</th>
                                                <th>Application Date</th>
                                                <th>Remarks</th>
                                                <th>Reason</th>
                                                <th>Date Claimed</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $fetchAssistanceById = $assistanceFacade->fetchAssistanceById($userId);
                                            foreach ($fetchAssistanceById as $assistance) { 
                                                $response = '';
                                                if (trim($assistance["status"] == "Approved")) {
                                                    $response = "alert alert-success";
                                                } elseif (trim($assistance["status"] == "Disapproved")) {
                                                    $response = "alert alert-danger";
                                                }
                                            ?>
                                                <tr class="<?= $response ?>">
                                                    <td><?= $assistance["assistance"] ?></td>
                                                    <td><?= $assistance["status"] ?></td>
                                                    <td>
                                                        <?= !empty($assistance["applied_date"]) && $assistance["applied_date"] != "0000-00-00 00:00:00" 
                                                            ? date('F j, Y h:i:s a', strtotime($assistance["applied_date"])) 
                                                            : '-'; 
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <!-- View Button -->
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#viewModal<?= $assistance["id"] ?>"> <span class="fas fa-fw fa-eye"></span> View</button>
                                                    </td>
                                                    <td><?= ($assistance["reason"] !== null) ? $assistance["reason"] : '-' ?></td>
                                                    <td>
                                                        <?= !empty($assistance["claimed_date"]) 
                                                            ? date('F j, Y h:i:s a', strtotime($assistance["claimed_date"])) 
                                                            : '-' 
                                                        ?>
                                                    </td>
                                                    <td>
                                                    <?php if ($assistance["status"] === "Pending"): ?>
                                                        <a href="update-apply-assistance.php?id=<?= $assistance['id'] ?>" class="btn btn-primary btn-sm"><span class="fas fa-fw fa-edit"></span> Edit </a> 
                                                        
                                                    <?php else: ?>
                                                        <span>-</span>
                                                    <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <!-- Modal for Viewing Row Details -->
                                                <div class="modal fade" id="viewModal<?= $assistance["id"] ?>" tabindex="-1" aria-labelledby="viewModalLabel<?= $assistance["id"] ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="viewModalLabel<?= $assistance["id"] ?>">Remarks</h5>
                                                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form>
                                                                    <?php if ($assistance["status"] == "Pending" || $assistance["status"] == "Disapproved") { ?>      
                                                                        <div class="form-floating">
                                                                            <textarea class="form-control text-left" style="height: 150px; resize: none;" readonly><?= trim($assistance["approver_reason"]) ?></textarea>
                                                                            <label for="approver_reason">Reason</label>
                                                                        </div>
                                                                    <?php } elseif ($assistance["status"] == "Approved") { ?>
                                                                        <div class="form-floating">
                                                                            <textarea class="form-control text-left" style="height: 150px; resize: none;" readonly><?= trim($assistance["approver_reason"]) ?></textarea>
                                                                            <label for="remarks">Reason</label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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