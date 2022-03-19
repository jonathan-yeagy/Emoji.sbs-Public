<?php

namespace EmojiSBS\External\Discord\OAuth;

use UnexpectedValueException;

require_once __DIR__ . '/../development.php';
require_once __DIR__ . '/DiscordOAuthDAO.php';

class DiscordOAuthAPI
{
    private const AUTH_REDIRECT_URI = SITE_ROOT . '/session.php?action=authorize';

    private $dao;

    // Session-tracked variables
    private $accessToken;
    private $refreshToken;
    private $expiry;

    // Cached per load
    private $user;
    private $users = array();
    private $guilds;

    function __construct($dao = null)
    {
        $this->dao = is_null($dao) ? new DiscordOAuthDAO() : $dao;
        if (isset($_SESSION['access_token'])) {
            $this->accessToken = $_SESSION['access_token'];
            $this->refreshToken = $_SESSION['refresh_token'];
            $this->expiry = $_SESSION['expiry'];
            // If the token is expired (or expiring), revoke session
            if ($this->expiry + 5 < time())
                $this->revokeSession();
            // TODO refresh token if time is about to expire
        }
    }

    function getLoginUrl(): string
    {
        return $this->dao->buildLoginUrl(self::AUTH_REDIRECT_URI);
    }

    function authorize($authorizationCode): bool
    {
        $response = $this->dao->createAccessToken($authorizationCode, self::AUTH_REDIRECT_URI);
        if ($response->access_token) {
            $this->accessToken = $_SESSION['access_token'] = $response->access_token;
            $this->refreshToken = $_SESSION['refresh_token'] = $response->refresh_token;
            $this->expiry = $_SESSION['expiry'] = $response->expires_in + time();
            return true;
        }
        return false;
    }

    function isAuthorized(): bool
    {
        if(!$this->accessToken)
            return false;

        // Actually test that the API is working.
        try {
            // If this succeeds, worst case scenario we cache the value for when we need it later.
            $this->getUser();
            return true;
        } catch (\UnexpectedValueException $e) {
            // The session will automatically be revoked
            return false;
        }
    }

    function revoke(): void
    {
        // Should always succeed, but if it doesn't the token will expire on its own
        $this->dao->revokeAccessToken($this->accessToken);
        $this->revokeSession();
    }

    function getUser(): object
    {
        // Cache value in case of multiple calls
        if (!isset($this->user)) {
            $user = $this->dao->getUser($this->accessToken);
            if (!$this->checkAuthorized($user)) {
                $this->revokeSession();
                throw new UnexpectedValueException("User not authorized.  This is likely a race condition.");
            }
            $this->user = $user;
        }
        return $this->user;
    }

    function getUserById($userId): object
    {
        // Cache value in case of multiple calls
        if(!in_array($userId, array_map(function($user){return $user->id;}, $this->users))) {
            $user = $this->dao->getUserById($userId);
            $this->users[$userId] = $user;
        }
        return $this->users[$userId];
    }

    function getBotGuilds(): array
    {
        // Cache value in case of multiple calls
        if (!isset($this->guilds) || is_null($this->guilds) || sizeof($this->guilds) === 0) {
            $this->guilds = $this->dao->getBotGuilds();
            usort($this->guilds, function($a, $b){return strnatcmp($a->name, $b->name);});
        }
        return $this->guilds;
    }

    function getBotGuild($guildId): ?object
    {
        foreach ($this->getBotGuilds() as $guild)
            if ($guild->id === $guildId)
                return $guild;
        return null;
    }

    function getCommonGuilds(): array
    {
        $userGuilds = array_map(function($guild){return $guild->id;}, $this->dao->getUserGuilds($this->accessToken));
        $common = array();
        foreach ($this->getBotGuilds() as $botGuild) {
            if (in_array($botGuild->id, $userGuilds)) {
                $simplified = new \stdClass();
                $simplified->id = $botGuild->id;
                $simplified->name = $botGuild->name;
                array_push($common, $simplified);
            }
        }
        return $common;
    }

    private function checkAuthorized($response): bool
    {
        // If there's a message that starts with '401' then it's not authorized
        return !(property_exists($response, 'message') &&
            substr_compare($response->message, '401', 0, 3) === 0);
    }

    /**
     * Unset internals and session variables.  Should only be called when the current token is invalid.
     */
    private function revokeSession(): void
    {
        unset($this->accessToken);
        unset($_SESSION['access_token']);
        unset($this->refreshToken);
        unset($_SESSION['refresh_token']);
        unset($this->expiry);
        unset($_SESSION['expiry']);
    }
}