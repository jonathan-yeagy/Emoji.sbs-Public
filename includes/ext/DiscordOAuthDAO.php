<?php

namespace EmojiSBS\External\Discord\OAuth;

require_once __DIR__ . '/../secrets/Secrets.php';

use EmojiSbs\Secrets\Secrets;

class DiscordOAuthDAO
{
    private const AUTHORIZE_URL = 'https://discordapp.com/api/oauth2/authorize';
    private const TOKEN_URL = 'https://discordapp.com/api/oauth2/token';
    private const TOKEN_REVOKE_URL = 'https://discordapp.com/api/oauth2/token/revoke';
    private const API_BASE_URL = 'https://discordapp.com/api';

    private $clientID;
    private $clientSecret;
    private $emojiArchiverToken;

    function __construct($secrets = null)
    {
        if (!$secrets) {
            $secrets = Secrets::discordOAuth();
        }
        $this->clientID = $secrets['client_id'];
        $this->clientSecret = $secrets['client_secret'];
        $this->emojiArchiverToken = $secrets['emoji_archiver_token'];
    }

    function buildLoginUrl($redirectUri): string
    {
        $params = array(
            'client_id' => $this->clientID,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'identify guilds'
        );
        return self::AUTHORIZE_URL . '?' . http_build_query($params);
    }

    function createAccessToken($authorizationCode, $redirectUri): object
    {
        $params = array(
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
            'code' => $authorizationCode
        );
        return self::request(self::TOKEN_URL, $params);
    }

    function revokeAccessToken($accessToken): object
    {
        // https://github.com/discord/discord-api-docs/issues/2259#issuecomment-927180184
        $params = array(
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'token' => $accessToken
        );
        return self::request(self::TOKEN_REVOKE_URL, $params);
    }

    function getUser($accessToken): object
    {
        return self::request(self::API_BASE_URL . '/users/@me', FALSE, $accessToken);
    }

    function getUserById($userId): object
    {
        return self::request(self::API_BASE_URL . '/users/' . $userId, FALSE, $this->emojiArchiverToken, TRUE);
    }

    function getUserGuilds($accessToken): array
    {
        return self::request(self::API_BASE_URL . '/users/@me/guilds', FALSE, $accessToken);
    }

    function getBotGuilds(): array
    {
        return self::request(self::API_BASE_URL . '/users/@me/guilds', FALSE, $this->emojiArchiverToken, TRUE);
    }

    static function request($url, $post = FALSE, $accessToken = null, $isBotUser = FALSE){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

        $headers[] = 'Accept: application/json';

        if(!is_null($accessToken))
            $headers[] = 'Authorization: ' . ($isBotUser ? 'Bot' : 'Bearer') . ' ' . $accessToken;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        return json_decode($response);
    }

}