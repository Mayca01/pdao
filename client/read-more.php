<?php

include realpath(__DIR__ . '/../app/layout/admin-header.php');

if (isset($_SESSION["first_name"])) {
    $firstName = $_SESSION["first_name"];
}

if (isset($_SESSION["last_name"])) {
    $lastName = $_SESSION["last_name"];
}

if (isset($_GET["information_id"])) {
    $informationId = $_GET["information_id"];
}

?>

<div class="container py-5">
    <?php
    $fetchInformations = $informationsFacade->fetchInformationById($informationId);
    foreach ($fetchInformations as $informations) { ?>
        <?php 
            $images = explode(', ', $informations["image"]); 
            if (count($images) > 1) {
                // Initialize the carousel
                echo '<div id="carousel'.$informations["id"].'" class="carousel slide" data-ride="carousel">';
                echo '<div class="carousel-inner">';

                foreach($images as $index => $image): 
                    $activeClass = ($index === 0) ? 'active' : ''; // Set the first image as active
                    ?>
                    <div class="carousel-item <?= $activeClass ?>">
                        <img src=".././public/img/informations/<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" class="d-block w-100" alt="Image <?= $index + 1 ?>" style="height: 500px; object-fit: cover;">
                    </div>
                    <?php 
                endforeach; 

                echo '</div>'; // carousel-inner
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
                // Single image display if only one image is available
                ?>
                <img src=".././public/img/informations/<?= htmlspecialchars($images[0], ENT_QUOTES, 'UTF-8') ?>" class="w-100 mb-2 img-thumbnail" alt="Image 1">
                <?php 
            }
        ?>
        <h1 class="text-center"><?= $informations["title"] ?></h1>
        <h6 class="text-center"><?= date('m/d/Y h:i:s a', strtotime($informations["date"])) ?></h6>
        <p class="mt-5" style="text-align: justify;"><?= $informations["description"] ?></p>
    <?php } ?>
</div>

<?php include realpath(__DIR__ . '/../app/layout/admin-footer.php') ?>