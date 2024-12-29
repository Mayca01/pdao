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
                                <h6 class="m-0 font-weight-bold">Announcements</h6>
                                <a href="add-information.php" class="btn btn-primary m-0">Add Announcement</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Title</th>
                                                <th class="text-center exclude">Image</th>
                                                <th class="text-center exclude">Action</th>
                                            </tr>
                                        </thead>
                                       <tbody class="text-center">
                                            <?php
                                            $fetchInformations = $informationsFacade->fetchInformations();
                                            foreach ($fetchInformations as $informations) { 
                                                $images = explode(', ', $informations["image"]); // Split the image filenames
                                                $modalId = 'modal' . $informations['id']; // Unique modal ID
                                            ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($informations["title"], ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td>
                                                        <a href="read-more.php?information_id=<?= $informations['id'] ?>" target="_blank">View</a>
                                                    </td>
                                                    <td>
                                                        <a href="update-information.php?information_id=<?= $informations["id"] ?>" class="btn btn-primary btn-sm">
                                                            <span class="fas fa-fw fa-edit"></span> Update
                                                        </a>
                                                        <a href="delete-information.php?information_id=<?= $informations["id"] ?>&image=<?= urlencode($informations["image"]) ?>" class="btn btn-danger btn-sm">
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