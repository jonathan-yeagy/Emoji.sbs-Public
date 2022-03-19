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

$emojiRequests = $emojiSbs->getRequest($_GET['requestNumber']);


foreach ($emojiRequests as $emojiRequest){

    ?>
    <form action="" method="post">
        <div class="form-group mb-3">
            <label for="emojiName">Emoji Name</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">:</span>
                </div>
                <input required type="text" class="form-control" id="emojiName" name="emojiName"
                       aria-describedby="emojiNameHelp" placeholder="rickroll" minlength="2" maxlength="32" value="<?=$emojiRequest['name']?>">
                <div class="input-group-append">
                    <span class="input-group-text">:</span>
                </div>
            </div>
            <small id="emojiNameHelp" class="form-text text-muted">How should the emoji be named in Discord?</small>
        </div>
        <div class="row px-3">
            <div class="form-check form-switch mb-3 px-0 col-auto d-flex flex-column">
                <label class="form-check-label" for="emojiAnimated">Animated</label>
                <input class="form-check-input mx-0" type="checkbox" <?php if($emojiRequest['animated'] == 1){echo 'checked';} ?>  id="emojiAnimated" name="emojiAnimated"/>
            </div>
            <div class="form-check form-switch mb-3 col">
                <label for="emojiPriority" class="form-label">Emoji Priority</label>
                <input type="range" class="form-range" value="<?=$emojiRequest['priority']?>" min="1" max="3" step="1" id="emojiPriority"
                       name="emojiPriority" aria-describedby="emojiPriorityHelp">
                <small id="emojiPriorityHelp" class="form-text text-muted">How high of a priority is this emoji?</small>
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="emojiSource">Source Material</label>
            <input type="url" class="form-control" id="emojiSource" name="emojiSource"
                   aria-describedby="emojiSourceHelp" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                   minlength="11" maxlength="2000" value="<?=$emojiRequest['source']?>">
            <small id="emojiSourceHelp" class="form-text text-muted">What is the URL of the source material for the
                emoji?</small>
        </div>
        <div class="form-group mb-3">
            <label for="emojiDescription">Description</label>
            <textarea class="form-control" id="emojiDescription" name="emojiDescription" rows="3"
                      aria-describedby="emojiDescriptionHelp"
                      placeholder="Never gonna give you up. Never gonna Let you down." minlength="2"
                      maxlength="500"><?=$emojiRequest['description']?></textarea>
            <small id="emojiDescriptionHelp" class="form-text text-muted">Describe how the emoji should look in
                Discord.</small>
        </div>
        <div class="form-group mb-3">
            <label for="emojiServer">Discord Server</label>
            <select class="form-control" id="emojiServer" name="emojiServer" aria-describedby="emojiServerHelp">
                <?php
                foreach ($guilds as $guild)
                    if($guild->id === $emojiRequest['server']){
                        echo '<option value="' . $guild->id . '" selected >' . $guild->name . '</option>';
                    }else{
                        echo '<option value="' . $guild->id . '">' . $guild->name . '</option>';
                    }
                ?>
            </select>
            <small id="emojiServerHelp" class="form-text text-muted">What Discord server should the emoji be uploaded
                to?</small>
        </div>
        <div class="form-group mb-3">
            <label for="emojiServer">Request Status</label>
            <select class="form-control" id="emojiStatus" name="emojiStatus" aria-describedby="emojiStatusHelp">
                <?php
                //set html based on emoji status
                switch ($emojiRequest['status']) {
                    case EmojiSbsAPI::STATUS_REJECTED:
                        echo '<option value="0" selected>Rejected</option><option value="1">Pending</option><option value="2">Approved</option><option value="3">Info Requested</option><option value="4">Completed</option>';
                        break;
                    case EmojiSbsAPI::STATUS_PENDING:
                        echo '<option value="0">Rejected</option><option value="1" selected>Pending</option><option value="2">Approved</option><option value="3">Info Requested</option><option value="4">Completed</option>';
                        break;
                    case EmojiSbsAPI::STATUS_APPROVED:
                        echo '<option value="0">Rejected</option><option value="1">Pending</option><option value="2" selected>Approved</option><option value="3">Info Requested</option><option value="4">Completed</option>';
                        break;
                    case EmojiSbsAPI::STATUS_INFO_REQUESTED:
                        echo '<option value="0">Rejected</option><option value="1">Pending</option><option value="2">Approved</option><option value="3" selected>Info Requested</option><option value="4">Completed</option>';
                        break;
                    case EmojiSbsAPI::STATUS_COMPLETED:
                        echo '<option value="0">Rejected</option><option value="1">Pending</option><option value="2">Approved</option><option value="3">Info Requested</option><option value="4" selected>Completed</option>';
                        break;
                    default:
                        echo '<option value="0">Rejected</option><option value="1">Pending</option><option value="2">Approved</option><option value="3">Info Requested</option><option value="4">Completed</option>';
                }
                ?>
            </select>
            <small id="emojiStatusHelp" class="form-text text-muted">What is the current status of this request?</small>
        </div>
        <button type="submit" class="btn btn-primary" name="emojiSubmit">Save Emoji Request</button>
    </form>

<?php
}
    // Replace $_GET['requestNumber'] with id
    if (isset($_POST['emojiSubmit'])) {
        if ($emojiSbs->modifyRequest($_POST['emojiName'], $_POST['emojiAnimated'], $_POST['emojiSource'],
            $_POST['emojiDescription'], $_POST['emojiServer'], $_POST['emojiPriority'],
            $_POST['emojiStatus'], $_GET['requestNumber'])) {
            echo "<script type='text/javascript'> document.location = 'board.php'; </script>";
            echo "Emoji Created Successfully";
        } else {
            die('error');
        }
    }



?>
<?php include "includes/layout/footer.php"; ?>