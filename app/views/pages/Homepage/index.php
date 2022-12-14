<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__. '/../includes/navbar.php'; ?>

<div class="container-fluid min-vh-100">
    <div class="row w-50 mx-auto main_container">
        <div class="col text-center">
            <h1 class="title py-3">URL Shortener</h1>
            <p class="text-muted">
                URL Shotener is a tool that allows you to make any url link shorter significantly,

            </p>
            <div class="input-group mb-3">
                <input type="text" class="form-control fancy_input" placeholder="Type your link here..." aria-label="Type your link here..." aria-describedby="basic-addon2">
            </div>
            <button class="btn btn-outline-secondary fancy_button" type="button">Generate</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>