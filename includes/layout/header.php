<?php
require_once __DIR__ . '/../development.php';

$site_name = "Discord Emoji Management System";
$site_description = "Portal to recommend emoji's to be added to my emoji storage Discord servers.";
$site_image = SITE_ROOT . "/shit.png";

require_once __DIR__ . '/../ext/DiscordOAuthAPI.php';

use EmojiSBS\External\Discord\OAuth\DiscordOAuthAPI;

session_start();
$oauth = new DiscordOAuthAPI();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Meta noindex-->
    <meta name="robots" content="noindex">
    <!-- HTML Meta Tags -->
    <title><?php echo $site_name ?></title>
    <meta name="description" content="<?php echo $site_description ?>">
    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="<?php echo $site_name ?>">
    <meta itemprop="description" content="<?php echo $site_description ?>">
    <meta itemprop="image" content="<?php echo $site_image ?>">
    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="<?php echo SITE_ROOT ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $site_name ?>">
    <meta property="og:description" content="<?php echo $site_description ?>">
    <meta property="og:image" content="<?php echo $site_image ?>">
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $site_name ?>">
    <meta name="twitter:description" content="<?php echo $site_description ?>">
    <meta name="twitter:image" content="<?php echo $site_image ?>">
    <!--Bootstrap Imports-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!--Custom Stylesheets-->

    <!-- Global site tag (gtag.js) - Google Analytics -->

<!--test 3-->
</head>
<body class="p-3">
<?php

if($oauth->isAuthorized()) {
    $user = $oauth->getUser();
    $guilds = $oauth->getCommonGuilds();
    //Show Logged in Nav
    include "includes/layout/nav_login.php";
} else {
    //Show Site Name and Description
    echo '<h1>' .  $site_name . '</h1><p class="mb-3">' . $site_description . '</p>';
    //Show Logged Out Nav
    include "includes/layout/nav.php";
}

?>