<?php

namespace EmojiSbs\Database;
require_once __DIR__ . '/../secrets/Secrets.php';

use EmojiSbs\Secrets\Secrets;
use mysqli;

class EmojiSbsDAO
{
    private $db;

    public function __construct()
    {
        $secrets = Secrets::database();
        $this->db = new mysqli($secrets['server_name'], $secrets['user_name'], $secrets['password'], $secrets['database_name']);
        if ($this->db->connect_error) {
            throw new \mysqli_sql_exception($this->db->connect_error);
        }
    }

    public function __destruct()
    {
        $this->db->close();
    }

    public function selectAllServers()
    {
        return $this->db->query("SELECT * FROM server ORDER BY serverName ASC");
    }

    public function insertRequest($name, $animated, $source, $description, $server, $requestedBy, $priority): bool
    {
        $query = $this->db->prepare("INSERT INTO request (name, animated, source, description, server, requestedBy, priority, requestDate, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $query->bind_param('sissssis', $name, $animated, $source, $description, $server, $requestedBy, $priority, date('Y-m-d H:i:s'));
        return $query->execute();
    }

    public function updateRequest($name, $animated, $source, $description, $server, $priority, $status, $id): bool
    {
        $query = $this->db->prepare("UPDATE request SET name = ?, animated = ?, source = ?, description = ?, server = ?, priority = ?, status = ? WHERE id = ?");
        $query->bind_param('sisssiii', $name, $animated, $source, $description, $server, $priority, $status, $id);
        return $query->execute();
    }

    public function selectRequest($id){
        return $this->db->query("SELECT * FROM request WHERE id = " . $id);

    }


    public function selectEmojisWithoutStatus(...$statuses)
    {
        // https://stackoverflow.com/a/58355651/2133216
        $sql = sprintf("SELECT * FROM request WHERE status NOT IN (%s) ORDER BY priority DESC, requestDate ASC",
                       str_repeat('?,', count($statuses) - 1) . '?');
        $query =  $this->db->prepare($sql);
        $query->bind_param(str_repeat('i', count($statuses)), ...$statuses);
        return $query->execute() ? $query->get_result() : false;
    }
}