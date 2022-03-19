<?php
require 'includes/layout/header.php';
if (!$oauth->isAuthorized()) {
    echo '<h1>Not Authorized</h1>';
    require 'includes/layout/footer.php';
    die();
}
?>
    <h1>Server Links</h1>
    <ul>
        <?php
        foreach ($oauth->getBotGuilds() as $guild) {
?>
            <li><a href="https://discord.com/channels/<?=$guild->id?>"><?=$guild->name?></a></li>
<?php
        }
        ?>
    </ul>
<?php include "includes/layout/footer.php"; ?>