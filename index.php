<?php 

include realpath(__DIR__ . '/app/layout/header.php');

$userId = 0;

if ($userId == 0) {
    header("Location: sign-in.php");
}

?>

<style>
    body {
        opacity: 1;
        background-image: radial-gradient(#cdd9e7 1.05px, #e5e5f7 1.05px);
        background-size: 21px 21px;
    }

    .col-12 {
        display: flex;
        align-items: center;
        height: 100vh;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div>
                <h1 class="display-4">Congratulations!</h1>
                <p class="lead">Your Dalira Framework project has been created successfully. <br class="d-none d-sm-block"> If you're new to Dalira Framework, it's advisable to read the <a href="https://appworksco.github.io/dalira/" class="text-decoration-none">documentation</a>.</p>
            </div>
        </div>
    </div>
</div>

<?php include realpath(__DIR__ . '/app/layout/footer.php') ?>