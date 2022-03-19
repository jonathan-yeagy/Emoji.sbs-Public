<?php

namespace EmojiSbs\Secrets;

class Secrets
{
    private static $database;
    private static $discordOAuth;

    /**
     * The contents of the Discord OAuth secrets file.
     *
     * @return array Database secrets
     */
    public static function discordOAuth(): array
    {
        if (empty(self::$discordOAuth)) {
            self::$discordOAuth = self::loadFile("discord_oauth.json");
        }
        return self::$discordOAuth;
    }

    /**
     * The contents of the database secrets file.
     *
     * @return array Database secrets
     */
    public static function database(): array
    {
        if (empty(self::$database)) {
            self::$database = self::loadFile("database.json");
        }
        return self::$database;
    }

    /**
     * Reads the contents of a file and returns the json_decode of that data.
     *
     * @param $filename string  the file to read
     * @return array            the json_decode'd contents
     */
    private static function loadFile(string $filename): array
    {
        return json_decode(file_get_contents(__DIR__ . '/' . $filename), true);
    }
}