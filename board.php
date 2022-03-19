<?php
require 'includes/layout/header.php';
if(!$oauth->isAuthorized()) {
    echo '<h1>Not Authorized</h1>';
    require 'includes/layout/footer.php';
    die();
}

require_once 'includes/db/EmojiSbsAPI.php';

use EmojiSbs\Database\EmojiSbsAPI;

$emojiSbs = new EmojiSbsAPI();
?>

<style>
    .accordion-button::after {
        display: none;
    }
</style>
<div class="accordion accordion-flush" id="accordionFlushExample">
    <?php
    $emojisInProgress = $emojiSbs->getInProgressEmojis();

    foreach ($emojisInProgress as $emoji) {
        $requestUser = $oauth->getUserById($emoji['requestedBy']);
        $requestServer = $oauth->getBotGuild($emoji['server']);
?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-heading<?=$emoji["id"]?>">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?=$emoji["id"]?>" aria-expanded="false" aria-controls="flush-collapse<?=$emoji["id"]?>">
            <div class="d-flex flex-column w-100">
                <div class="row mb-1">
                    <div class="col-auto">
                        <strong class="font-weight-bold"><?=$emoji["name"]?></strong>
                    </div>
                    <div class="col"></div>
                    <div class="col-auto">
                        <?php
                        //Set Priority Status
                        switch ($emoji["priority"]) {
                        case EmojiSbsAPI::PRIORITY_NORMAL:
                            ?><span class="badge bg-success w-100 text-center">Normal</span><?php
                            break;
                        case EmojiSbsAPI::PRIORITY_HIGH:
                            ?><span class="badge bg-warning w-100 text-center text-dark">High</span><?php
                            break;
                        case EmojiSbsAPI::PRIORITY_VERY_HIGH:
                            ?><span class="badge bg-danger w-100 text-center">Very High</span><?php
                            break;
                        default:
                            ?><span class="badge bg-dark w-100 text-center">Undefined</span><?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-auto">
                        <?php
                            // Check if emoji is Animated
                            if ($emoji["animated"] == 1) {
                                ?><span class="badge bg-secondary text-white">Animated</span><?php
                            }
                        ?>
                    </div>
                    <div class="col"></div>
                    <div class="col-auto">
                        <?php
                        //set html based on emoji status
                        switch ($emoji["status"]) {
                            case EmojiSbsAPI::STATUS_REJECTED:
                                ?><span class="badge w-100 bg-danger text-center">Rejected</span><?php
                                break;
                            case EmojiSbsAPI::STATUS_PENDING:
                                ?><span class="badge w-100 bg-warning text-center text-dark">Pending</span><?php
                                break;
                            case EmojiSbsAPI::STATUS_APPROVED:
                               ?><span class="badge w-100 bg-success text-center">Approved</span><?php
                                break;
                            case EmojiSbsAPI::STATUS_INFO_REQUESTED:
                                ?><span class="badge w-100 bg-info text-center text-dark">Info Requested</span><?php
                                break;
                            case EmojiSbsAPI::STATUS_COMPLETED:
                                ?><span class="badge w-100 bg-secondary text-center">Completed</span><?php
                                break;
                            default:
                                ?><span class="badge w-100 bg-dark text-center">Unknown</span><?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </button>
        </h2>
        <div id="flush-collapse<?=$emoji["id"]?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?=$emoji["id"]?>" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
                <div class="w-100 d-flex justify-content-between">
                    <?php
                        if($user->id === $requestUser->id){
                    ?>
                        <span>Me</span>
                    <?php
                        }else{
                    ?>
                        <a href="https://discordapp.com/users/<?= $requestUser->id ?>" target="_blank">
                            <img src="https://cdn.discordapp.com/avatars/<?=$requestUser->id?>/<?=$requestUser->avatar?>" class="rounded-circle profile-pic" alt="<?=$requestUser->username?>"/>
                            <span><?=$requestUser->username?>#<?=$requestUser->discriminator?></span>
                        </a>
                    <?php
                        }
                        // Check if Source exists
                        if (strlen($emoji["source"]) > 0) {
                            ?><a href="<?=$emoji["source"]?>" class="badge bg-primary" target="_blank">Source</a><?php
                        }
                    ?>
                </div>
                <a href="https://discord.com/channels/<?=$requestServer->id?>" target="_blank">
                    <img src="https://cdn.discordapp.com/icons/<?=$requestServer->id?>/<?=$requestServer->icon?>.png" class="rounded-circle profile-pic" alt="<?=$requestServer->name?>" />
                    <span><?=$requestServer->name?></span>
                </a>
                <hr>
                <p><?=$emoji["description"]?></p>
                <div class="text-end">
                    <a href="/edit_request.php?requestNumber=<?=$emoji["id"]?>">Edit</a>
                </div>
            </div>
        </div>
    </div>
<?php
    }
    if (empty($emojisInProgress)) {
        echo "0 results";
    }
    ?>
</div>
<?php include "includes/layout/footer.php"; ?>