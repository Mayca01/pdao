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
foreach ($fetchInformations as $informations) { 
    $images = explode(', ', $informations["image"]); 
?>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($images as $index => $image): ?>
            <button type="button" data-target="#carouselExampleIndicators" data-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($images as $index => $image): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src=".././public/img/informations/<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" class="d-block w-100 img-thumbnail" alt="Image <?= $index + 1 ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<h1 class="text-center"><?= $informations["title"] ?></h1>
<h6 class="text-center"><?= date('m/d/Y h:i:s a', strtotime($informations["date"])) ?></h6>
<?php } ?>

</div>

<?php include realpath(__DIR__ . '/../app/layout/admin-footer.php') ?>