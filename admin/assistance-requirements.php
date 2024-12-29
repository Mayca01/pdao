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

$uploadDir = ".././public/img/requirements/uploads/";

$assistance_id = null;
if (isset($_GET["assistance_id"]))
{
    $assistance_id = $_GET["assistance_id"];
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
                            <div class="card-header d-flex align-items-center">
                                <a href="assistance.php" class="mr-2"> <!-- Adds spacing between link and heading -->
                                        <span class="fas fa-fw fa-arrow-left"></span> Back
                                </a>
                                <!-- <span class="mx-2"> | </span>
                                <h6 class="m-0 font-weight-bold">Assistance Information</h6> -->
                            </div>
                            <div class="card-body">
                                <?php 
                                    $fetchAssistance = $assistanceFacade->fetchAssistanceUpldRequirements($assistance_id);
                                    foreach($fetchAssistance as $row):

                                ?>
                                <div class="col-sm-12">
                                    <h5>PWD: &nbsp; <?= ucwords($row["first_name"] . ' ' . $row["last_name"]); ?></h5>
                                    <h5>Uploaded Requirements:</h5>
                                        <div class="row">
                                            <?php 
                                                // Ensure $row['uploaded_requirements'] is not empty
                                                if (!empty($row['uploaded_requirements'])) {
                                                    $uploadedFiles = explode(",", $row["uploaded_requirements"]);
                                                    $fName = htmlspecialchars($row["first_name"]); // Escape first name
                                                    $lName = htmlspecialchars($row["last_name"]);   // Escape last name

                                                    foreach ($uploadedFiles as $fileName):
                                                        $fileName = trim($fileName);
                                                        $filePath = $uploadDir . $fName . "_" . $lName . "/" . $fileName;

                                                        // Check if the file exists
                                                        if (file_exists($filePath)) {
                                                            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                                                            echo "<div class='col-sm-4 mb-3'>";
                                                            // Check for image files
                                                            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                                echo "<div class='card text-center shadow-sm'>";
                                                                echo "<div class='card-body'>";
                                                                echo "<i class='fas fa-file-image fa-2x text-primary mb-2'></i>"; 
                                                                echo "<br/> <a href='" . htmlspecialchars($filePath) . "' target='_blank' class='btn btn-outline-primary btn-sm'> View Image</a>";
                                                                echo "</div>";
                                                                echo "<div class='card-footer text-truncate'>" . htmlspecialchars($fileName) . "</div>";
                                                                echo "</div>";
                                                            }
                                                            // Check for PDF files
                                                            elseif ($fileExtension === 'pdf') {
                                                                echo "<div class='card text-center shadow-sm'>";
                                                                echo "<div class='card-body'>";
                                                                echo "<i class='fas fa-file-pdf fa-2x text-danger mb-2'></i>"; // Font Awesome icon for PDFs
                                                                echo "<br/> <a href='" . htmlspecialchars($filePath) . "' target='_blank' class='btn btn-outline-primary btn-sm'>View PDF</a>";
                                                                echo "</div>";
                                                                echo "<div class='card-footer text-truncate'>" . htmlspecialchars($fileName) . "</div>";
                                                                echo "</div>";
                                                            }
                                                            echo "</div>";
                                                        } else {
                                                            // Handle missing files
                                                            echo "<div class='col-sm-4 mb-3 text-danger'>";
                                                            echo "<div class='card text-center shadow-sm'>";
                                                            echo "<div class='card-body'>";
                                                            echo "<i class='fas fa-exclamation-triangle fa-2x text-warning mb-2'></i>"; // Warning icon for missing files
                                                            echo "File not found: " . htmlspecialchars($fileName);
                                                            echo "</div>";
                                                            echo "</div>";
                                                            echo "</div>";
                                                        }
                                                    endforeach;
                                                } else {
                                                    echo "<div class='col-12'>";
                                                    echo "<div class='alert alert-danger' role='alert'>";
                                                    echo "No requirements provided.";
                                                    echo "</div>";
                                                    echo "</div>";
                                                }
                                            ?>
                                        </div>
                                        <h5>Reason: <?= $row['reason'] ?></h5>
                                    </div>
                                </div>
                                <?php endforeach; ?>
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