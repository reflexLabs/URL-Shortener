<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__. '/../includes/navbar.php'; ?>

<div class="container-fluid min-vh-100">
    <div class="row w-50 mx-auto main_container">
        <div class="col text-center">
            <h1 class="title py-2">URL Shortener Counter</h1>
            <p class="text-muted">
                <strong>URL Shotener Counter allows you to check click through rate of any shortened url link,</strong>
                <br>Simply just put the short code (the code after / in the link) to check
            </p>
            <div class="input-group mb-3">
                <input type="text" id="actionInput" class="form-control fancy_input" placeholder="Type your link here..." aria-label="Type your link here..." aria-describedby="basic-addon2">
            </div>
            <button class="btn btn-outline-secondary fancy_button" id="actionButton" value="count" type="button">Check</button>

            <div class="py-3"><a href="/">Go back</a></div>
        </div>
    </div>
</div>

<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="actionModalLabel">Click Through Rate</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control fancy_input" id="copyInput" placeholder="URL" aria-label="URL" aria-describedby="actionCopy">
                <button class="btn btn-outline-secondary fancy_button" type="button" data-value="copy" id="actionCopy">Copy</button>
            </div>
            <div class="py-2">
                <span class="text-white">Shortened Link: </span>
                <a href="#" target="_blank" class="text-muted" id="text_placeholder"></span>
            </div>
        </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>