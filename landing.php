<?php
require 'includes/layout/header.php';
if(!$oauth->isAuthorized()) {
    echo '<h1>Not Authorized</h1>';
    require 'includes/layout/footer.php';
    die();
}
?>
<div class="row">
    <div class="p-3 col-12 col-sm-6 col-lg-3">
        <a href="request.php" class="card text-decoration-none text-body">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">Request Emoji</h5>
                <img src="https://cdn.discordapp.com/avatars/428762361537495050/40d95dc2458caa6c07de13ad7007c717" class="rounded-circle profile-pic" alt="chronically crazy" title="" style="">
            </div>
            <div class="card-body">
                <p class="card-text">Request an Emoji to be added to a server.</p>
            </div>
        </a>
    </div>
    <div class="p-3 col-12 col-sm-6 col-lg-3">
        <a href="board.php" class="card text-decoration-none text-body">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">Emoji Board</h5>
                <img src="https://cdn.discordapp.com/avatars/428762361537495050/40d95dc2458caa6c07de13ad7007c717" class="rounded-circle profile-pic" alt="chronically crazy" title="" style="">
            </div>
            <div class="card-body">
                <p class="card-text">View list of already requested emojis.</p>
            </div>
        </a>
    </div>
    <div class="p-3 col-12 col-sm-6 col-lg-3">
        <a href="links.php" class="card text-decoration-none text-body">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">Server Links</h5>
                <img src="https://cdn.discordapp.com/avatars/428762361537495050/40d95dc2458caa6c07de13ad7007c717" class="rounded-circle profile-pic" alt="chronically crazy" title="" style="">
            </div>
            <div class="card-body">
                <p class="card-text">View list of links for Discord servers.</p>
            </div>
        </a>
    </div>
    <div class="p-3 col-12 col-sm-6 col-lg-3">
        <a href="index.php" class="card text-decoration-none text-body">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">About the Project</h5>
                <img src="https://cdn.discordapp.com/avatars/428762361537495050/40d95dc2458caa6c07de13ad7007c717" class="rounded-circle profile-pic" alt="chronically crazy" title="" style="">
            </div>
            <div class="card-body">
                <p class="card-text">General information about this project.</p>
            </div>
        </a>
    </div>
</div>
<?php include "includes/layout/footer.php"; ?>