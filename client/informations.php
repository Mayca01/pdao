<?php
include realpath(__DIR__ . '/../app/layout/admin-header.php');
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if (isset($_SESSION['first_name'])) {
    $firstName = $_SESSION['first_name'];
}

if (isset($_SESSION['last_name'])) {
    $lastName = $_SESSION['last_name'];
}

if (isset($_GET['msg'])) {
    array_push($success, $_GET['msg']);
}
?>

<div id="wrapper">
    <!-- Sidebar -->
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
            <a class="nav-link" href="assistance.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Assistance</span>
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="informations.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Announcements</span>
            </a>
        </li>
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

    <!-- Content Wrapper -->
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

            <!-- Main Content -->
            <div class="container-fluid">
               
                <?php include('.././errors.php') ?>
                <div class="row">
                    <?php
                    $fetchInformations = $informationsFacade->fetchInformations();
                    foreach ($fetchInformations as $informations) { ?>
                        <div class="col-sm-12 col-md-6 col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <h3 class="m-0"><?= $informations["title"] ?></h3>
                                    <h6>Posted On: <?= date('F j, Y h:i:s a', strtotime($informations["date"])) ?></h6>
                                </div>
                                <div class="card-body">
                                    <?php 
                                    $images = explode(', ', $informations["image"]);
                                    if (count($images) > 1) {
                                        // Initialize the carousel
                                        echo '<div id="carousel'.$informations["id"].'" class="carousel slide" data-ride="carousel">';
                                        echo '<div class="carousel-inner">';

                                        foreach ($images as $index => $image): 
                                            $activeClass = ($index === 0) ? 'active' : ''; 
                                            ?>
                                            <div class="carousel-item <?= $activeClass ?>">
                                                <img src=".././public/img/informations/<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" class="d-block w-100" alt="Image <?= $index + 1 ?>" style="height: 300px; object-fit: cover;">
                                            </div>
                                            <?php 
                                        endforeach; 

                                        echo '</div>';
                                        // Carousel controls
                                        echo '<a class="carousel-control-prev" href="#carousel'.$informations["id"].'" role="button" data-slide="prev">';
                                        echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                                        echo '<span class="sr-only">Previous</span>';
                                        echo '</a>';
                                        echo '<a class="carousel-control-next" href="#carousel'.$informations["id"].'" role="button" data-slide="next">';
                                        echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                                        echo '<span class="sr-only">Next</span>';
                                        echo '</a>';
                                        echo '</div>'; // carousel
                                    } else {
                                        // Display single image if only one is available
                                        ?>
                                        <img src=".././public/img/informations/<?= htmlspecialchars($images[0], ENT_QUOTES, 'UTF-8') ?>" class="d-inline-block mr-2" alt="Image 1" style="max-width: 100%; height: auto;">
                                        <?php 
                                    }
                                    ?>
                                </div>
                                <div class="card-footer">
                                    <a href="read-more.php?information_id=<?= $informations["id"] ?>" target="_blank">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; PDAO 2024</span>
                </div>
            </div>
        </footer>
    </div>
</div>
<?php include realpath(__DIR__ . '/../app/layout/admin-footer.php') ?>



