<?php

declare(strict_types=1);

namespace system;
use \mysqli;
use \Exception;

class Database
{
    // <-- property variabel class -->
    private $mysqli          = '';
    private $selects         = '*';
    private $insert_value    = '';
    private $sets            = '';
    private $froms           = '';
    private $where_clause    = '';
    private $or_where_clause = '';

    public function dbconnect()
    {
        try {
            $this->mysqli = @new mysqli(DB['db_server'], DB['db_username'], DB['db_password'], DB['db_name']);
            if ($this->mysqli->connect_errno) {
                // gagal konek ke database
                throw new Exception("<h1>Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error . "</h1>");
            }
            return $this;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function db()
    {
        $this->selects         = '*';
        $this->insert_value    = '';
        $this->sets            = '';
        $this->froms           = '';
        $this->where_clause    = '';
        $this->or_where_clause = '';
        return $this;
    }

    public function insert(string $value = '')
    {
        $this->selects = $value;
        return $this;
    }

    public function addValue(array $value = [])
    {
        $arr_key = [];
        $arr_value = [];
        foreach ($value as $keys => $values) {
            $arr_key[] = $keys;
            $arr_value[] = $values;
        }
        $this->insert_value = "(" . join(", ", $arr_key) . ") VALUES ('" . join("', '", $arr_value) . "')";
        return $this;
    }

    public function select($value = '')
    {
        $this->selects = is_array($value) ? join(', ', $value) : $value;
        return $this;
    }

    public function from(string $value = '')
    {
        $this->froms = $value;
        return $this;
    }

    public function set(array $value = [])
    {
        $data = [];
        foreach ($value as $keys => $values) {
            $data[] = sprintf("%s='%s'", $keys, $values);
        }
        $this->sets = 'SET ' . join(', ', $data);
        return $this;
    }

    public function where(array $value = [])
    {
        $data = [];
        foreach ($value as $keys => $values) {
            $data[] = sprintf("%s='%s'", $keys, $values);
        }
        $this->where_clause = 'WHERE (' . join(' AND ', $data) . ')';
        return $this;
    }

    public function orWhere(array $value = [])
    {
        $data = [];
        foreach ($value as $keys => $values) {
            $data[] = sprintf("%s='%s'", $keys, $values);
        }
        $this->or_where_clause = 'OR (' . join(' AND ', $data) . ')';
        return $this;
    }

    public function get()
    {
        $results = [];
        $sql = sprintf("SELECT %s FROM %s %s %s", $this->selects, $this->froms, $this->where_clause, $this->or_where_clause);
        $result = mysqli_query($this->mysqli, $sql);
        if ($result) {
            while ($data = mysqli_fetch_assoc($result)) {
                $results[] = $data;
            }
        }
        return $results;
    }

    public function addData()
    {
        $sql = sprintf("INSERT INTO %s %s", $this->selects, $this->insert_value);
        return mysqli_query($this->mysqli, $sql);
    }

    public function update()
    {
        $sql = sprintf("UPDATE %s %s %s", $this->froms, $this->sets, $this->where_clause);
        return mysqli_query($this->mysqli, $sql);
    }

    public function query(string $query = '')
    {
        return mysqli_query($this->mysqli, $query);
    }
}
