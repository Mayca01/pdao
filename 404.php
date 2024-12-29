<?php include realpath(__DIR__ . '/includes/layout/header.php') ?>

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
                <h1 class="display-4">Error 404</h1>
                <p class="lead">The page your looking for may have been moved, deleted or possibly never existed.</p>
            </div>
        </div>
    </div>
</div>

<?php include realpath(__DIR__ . '/includes/layout/footer.php') ?>