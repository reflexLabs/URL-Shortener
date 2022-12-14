<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__. '/../includes/navbar.php'; ?>

<div class="container-fluid min-vh-100">
    <div class="row w-50 mx-auto main_container">
        <div class="col text-center">
            <h1 class="title py-2">URL Shortener</h1>
            <p class="text-muted">
                <strong>URL Shotener is a tool that allows you to make any url link significantly shorter,</strong>
                <br>This website uses PHP, Symfony and bootstrap.
            </p>
            <div class="input-group mb-3">
                <input type="text" id="actionInput" class="form-control fancy_input" placeholder="Type your link here..." aria-label="Type your link here..." aria-describedby="basic-addon2">
            </div>
            <button class="btn btn-outline-secondary fancy_button" id="actionButton" value="generate" type="button">Generate</button>

            <div class="py-3"><a href="/counter">Click Through Rate Tool</a></div>
        </div>
    </div>
</div>

<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="actionModalLabel">Your link is generated successfully!</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control fancy_input" id="copyInput" placeholder="URL" aria-label="URL" aria-describedby="actionCopy">
                <button class="btn btn-outline-secondary fancy_button" type="button" data-value="copy" id="actionCopy">Copy</button>
            </div>
            <div class="py-2">
                <span class="text-white">Original Link: </span>
                <a href="#" target="_blank" class="text-muted" id="text_placeholder"></span>
            </div>
        </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>