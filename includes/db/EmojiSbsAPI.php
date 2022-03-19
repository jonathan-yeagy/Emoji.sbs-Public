<?php
namespace EmojiSbs\Database;
require_once 'EmojiSbsDAO.php';

class EmojiSbsAPI
{
    const STATUS_REJECTED = 0;
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_INFO_REQUESTED = 3;
    const STATUS_COMPLETED = 4;

    const PRIORITY_NORMAL = 1;
    const PRIORITY_HIGH = 2;
    const PRIORITY_VERY_HIGH = 3;

    private $dao;

    public function __construct($dao = null)
    {
        $this->dao = is_null($dao) ? new EmojiSbsDAO() : $dao;
    }

    public function getServers(): array
    {
        $results = $this->dao->selectAllServers();
        $servers = array();
        if ($results != FALSE) while ($row = $results->fetch_assoc()) {
            array_push($servers, $row);
        }
        return $servers;
    }

    public function addRequest($name, $animated, $source, $description, $server, $requestedBy, $priority): bool
    {
        function cleanName($string)
        {
            $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
            return preg_replace('/[^A-Za-z0-9\-]/', '_', $string); // Removes special chars.
        }

        return $this->dao->insertRequest(
            cleanName($name),
            filter_var($animated, FILTER_SANITIZE_STRING) === 'on' ? 1 : 0,
            filter_var($source, FILTER_SANITIZE_URL),
            filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($server, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($requestedBy, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($priority, FILTER_SANITIZE_NUMBER_INT)
        );
    }

    public function modifyRequest($name, $animated, $source, $description, $server, $priority, $status, $id): bool
    {
        function cleanName($string)
        {
            $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
            return preg_replace('/[^A-Za-z0-9\-]/', '_', $string); // Removes special chars.
        }

        return $this->dao->updateRequest(
            cleanName($name),
            filter_var($animated, FILTER_SANITIZE_STRING) === 'on' ? 1 : 0,
            filter_var($source, FILTER_SANITIZE_URL),
            filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($server, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($priority, FILTER_SANITIZE_NUMBER_INT),
            $status,
            $id
        );
    }

    public function getRequest($id): array
    {
        $results = $this->dao->selectRequest($id);
        $requests = array();
        if ($results != FALSE) while ($row = $results->fetch_assoc()) {
            array_push($requests, $row);
        }
        return $requests;

    }

    public function getInProgressEmojis(): array
    {
        $results = $this->dao->selectEmojisWithoutStatus(self::STATUS_REJECTED, self::STATUS_COMPLETED);
        $emojis = array();
        if ($results != FALSE) while ($row = $results->fetch_assoc()) {
            array_push($emojis, $row);
        }
        return $emojis;
    }
}