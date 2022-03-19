<div class="dropdown d-flex justify-content-end">
    <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown">
        <img src="https://cdn.discordapp.com/avatars/<?= $user->id ?>/<?= $user->avatar ?>"
             class="rounded-circle profile-pic" alt="<?= $user->username ?>"/>
        <span><?= $user->username ?></span>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="/request.php">Request Emoji</a>
        <a class="dropdown-item" href="/board.php">Emoji Board</a>
        <a class="dropdown-item" href="/links.php">Server Links</a>
        <a class="dropdown-item" href="/index.php">About the Project</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="https://discordapp.com/users/<?= $user->id ?>" target="_blank">View My
            Account</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/session.php?action=logout">Log Out</a>
    </div>
</div>
<style>
    .profile-pic {
        height: 2em;
        margin-right: .5em;
    }
</style>