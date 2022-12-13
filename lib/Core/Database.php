<?php

namespace Library\Core;

use PDO, PDOStatement;

class Database {

    private static ?Database $_instance = null;

    //private string $_prefix;
    private ?string $_force_charset;
    protected PDO $_pdo;
    private PDOStatement $_statement;
    private bool $_error = false;
    private array $_results;
    private int $_count = 0;

    function __construct() {
        $host = Config::get('databases/mysql/host');
        $database = Config::get('databases/mysql/name');
        $username = Config::get('databases/mysql/username');
        $password = Config::get('databases/mysql/password');
        $port = Config::get('databases/mysql/port');
        $prefix = Config::get('databases/mysql/host');

        $connection_string = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database;

        $this->_pdo = new PDO(
            $connection_string,
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );
    }

    public static function getCustomInstance(
        string $host,
        string $database,
        string $username,
        string $password,
        int $port = 3306,
        //string $prefix = 'r_'
    ): Database {
        return new Database();
    }

    public static function getInstance(): Database {
        if (self::$_instance) {
            return self::$_instance;
        }

        return self::$_instance = self::getCustomInstance(
            Config::get('databases/mysql/host'),
            Config::get('databases/mysql/name'),
            Config::get('databases/mysql/username'),
            Config::get('databases/mysql/password'),
            Config::get('databases/mysql/port'),
            Config::get('databases/mysql/host')
        );
    }

    /**
     * Get the underlying PDO instance.
     *
     * @return PDO The PDO instance.
     */
    public function getPDO(): PDO {
        return $this->_pdo;
    }

    /**
     * Get the first row of the result.
     *
     * @return object|null The result object, or null if no result was returned.
     */
    public function first(): ?object {
        return $this->results()[0] ?? null;
    }

    /**
     * Get all the results of the query.
     *
     * @return array The results of the query.
     */
    public function results(): array {
        return $this->_results;
    }

    /**
     * Get the number of rows affected by the last query.
     *
     * @return int The number of rows.
     */
    public function count(): int {
        return $this->_count;
    }

    /**
     * Get the last inserted ID
     *
     * @return string|false ID of the last inserted row or false on failure
     */
    public function lastId() {
        return $this->_pdo->lastInsertId();
    }

    /**
     * Whether there was an error during the last query.
     *
     * @return bool Whether there was an error.
     */
    public function error(): bool {
        return $this->_error;
    }

    /**
     * Perform a SELECT query on the database.
     *
     * @param string $table The table to select from.
     * @param array $where The where clause.
     * @return static|false This instance if successful, false otherwise.
     */
    public function get(string $table, array $where = []) {
        return $this->action('SELECT *', $table, $where);
    }

    /**
     * Perform a DELTE query on the database.
     *
     * @param string $table The table to delete from.
     * @param array $where The where clause.
     * @return static|false This instance if successful, false otherwise.
     */
    public function delete(string $table, array $where) {
        return $this->action('DELETE', $table, $where);
    }

    /**
     * Perform a raw SQL query on the database.
     *
     * @param string $sql The SQL query string to execute.
     * @param array $params The parameters to bind to the query.
     * @param bool $isSelect Whether the statement is a select, defaults to null
     * @return static This Database instance.
     */
    public function query(string $sql, array $params = [], bool $isSelect = null) {
        $this->_error = false;
        if ($this->_statement = $this->_pdo->prepare($sql)) {
            $x = 1;
            foreach ($params as $param) {
                // Convert "true" and "false" to 1 and 0 so that query params can be more fluent
                if (is_bool($param)) {
                    $param = $param ? 1 : 0;
                }
                $this->_statement->bindValue($x, $param, is_int($param)
                    ? PDO::PARAM_INT
                    : PDO::PARAM_STR
                );
                $x++;
            }

            if ($this->_statement->execute()) {
                // Only fetch the results if this is a SELECT query.
                if ($isSelect || str_starts_with(strtoupper($sql), 'SELECT')) {
                    $this->_results = $this->_statement->fetchAll(PDO::FETCH_OBJ);
                }
                $this->_count = $this->_statement->rowCount();
            } else {
                print_r($this->_pdo->errorInfo());
                $this->_error = true;
            }
        }

        return $this;
    }

    /**
     * Execute some SQL action (which uses a where clause) on the database.
     *
     * @param string $action The action to perform (SELECT, DELETE).
     * @param string $table The table to perform the action on.
     * @param array $where The where clause.
     * @return static|false This instance if successful, false otherwise.
     */
    private function action(string $action, string $table, array $where = []) {
        [$where, $where_params] = $this->makeWhere($where);

        //$table = $this->_prefix . $table;
        $sql = "{$action} FROM {$table} {$where}";

        if (!$this->query($sql, $where_params)->error()) {
            return $this;
        }

        return false;
    }

    /**
     * Insert a new row into a table within the database.
     *
     * @param string $table The table to insert into.
     * @param array $fields Array of data in "column => value" format to insert.
     * @return bool Whether an error occurred or not.
     */
    public function insert(string $table, array $fields = []): bool {
        $keys = array_keys($fields);
        $fieldCount = count($fields);
        $values = '';
        $x = 1;

        for ($i = 0; $i < $fieldCount; $i++) {
            $values .= '?';
            if ($x < $fieldCount) {
                $values .= ', ';
            }
            $x++;
        }

        //$table = $this->_prefix . $table;
        $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

        return !$this->query($sql, $fields)->error();
    }

    /**
     * Perform an UPDATE query on a table.
     *
     * @param string $table The table to update.
     * @param mixed $where The where clause. If not an array, it will be used for "id" column lookup.
     * @param array $fields Array of data in "column => value" format to update.
     * @return bool Whether an error occurred or not.
     */
    public function update(string $table, $where, array $fields): bool {
        $set = '';
        $x = 1;

        foreach (array_keys($fields) as $column) {
            $set .= "`{$column}` = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        if (!is_array($where)) {
            $where = ['id', '=', $where];
        }

        [$where, $where_params] = $this->makeWhere($where);
        //$table = $this->_prefix . $table;

        $sql = "UPDATE {$table} SET {$set} $where";

        return !$this->query($sql, array_merge($fields, $where_params))->error();
    }

    /**
     * Increment a numeric column value by 1.
     *
     * @param string $table The table to use.
     * @param int $id The id of the row to increment a column in.
     * @param string $field The field to increment.
     * @return bool Whether an error occurred or not.
     */
    public function increment(string $table, int $id, string $field): bool {
        //$table = $this->_prefix . $table;

        return !$this->query("UPDATE {$table} SET {$field} = {$field} + 1 WHERE id = ?", [$id])->error();
    }

    /**
     * Decrement a numeric column value by 1.
     *
     * @param string $table The table to use.
     * @param int $id The id of the row to decrement a column in.
     * @param string $field The field to increment.
     * @return bool Whether an error occurred or not.
     */
    public function decrement(string $table, int $id, string $field): bool {
        //$table = $this->_prefix . $table;

        return !$this->query("UPDATE {$table} SET {$field} = {$field} - 1 WHERE id = ?", [$id])->error();
    }

    /**
     * Select rows from the database, ordering by a specific column and sort type.
     *
     * @param string $table The table to use.
     * @param string $order The column to order by.
     * @param string $sort ASC or DESC
     * @return static|false This instance if successful, false otherwise.
     */
    public function orderAll(string $table, string $order, string $sort) {
        //$table = $this->_prefix . $table;
        $sql = "SELECT * FROM {$table} ORDER BY {$order} {$sort}";

        if (!$this->query($sql)->error()) {
            return $this;
        }

        return false;
    }

    /**
     * Select rows from the database with a where clause, ordering by a specific column and sort type.
     *
     * @param string $table The table to use.
     * @param string $order The column to order by.
     * @param string $sort ASC or DESC
     * @return static|false This instance if successful, false otherwise.
     */
    public function orderWhere(string $table, string $where, string $order, string $sort) {
        //$table = $this->_prefix . $table;
        $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$order} {$sort}";

        if (!$this->query($sql)->error()) {
            return $this;
        }

        return false;
    }

    /**
     * Create a new table in the database.
     *
     * @param string $name The name of the table.
     * @param string $table_schema The table SQL schema.
     * @return bool Whether an error occurred or not.
     */
    public function createTable(string $name, string $table_schema): bool {
        //$name = $this->_prefix . $name;
        $sql = "CREATE TABLE `{$name}` ({$table_schema}) ENGINE=InnoDB";
        if ($this->_force_charset) {
            $sql .= ' DEFAULT CHARSET=' . $this->_force_charset;
        }

        return !$this->query($sql)->error();
    }

    /**
     * Perform a SHOW TABLES LIKE query.
     *
     * @param string $table Name of table to try and lookup.
     * @return int|false The number of rows affected, or false on failure.
     */
    public function showTables(string $table) {
        //$table = $this->_prefix . $table;
        $sql = "SHOW TABLES LIKE '{$table}'";

        if (!$this->query($sql)->error()) {
            return $this->_statement->rowCount();
        }

        return false;
    }

    /**
     * Add a new column to a table.
     *
     * @param string $table Name of table to alter.
     * @param string $column The column to add.
     * @param string $attributes The attributes of the column.
     * @return bool Whether an error occurred or not.
     */
    public function addColumn(string $table, string $column, string $attributes): bool {
        //$table = $this->_prefix . $table;
        $sql = "ALTER TABLE {$table} ADD {$column} {$attributes}";

        return !$this->query($sql)->error();
    }

    /**
     * Convert an array of where clause data into a MySQL WHERE clause and params.
     *
     * @param array $clauses An array, or nested array, of
     * column, operator (default =), value, and glue (default AND).
     * @return array The where clause string, and parameters to bind.
     */
    private function makeWhere(array $clauses): array {
        if (count($clauses) === count($clauses, COUNT_RECURSIVE)) {
            return $this->makeWhere([$clauses]);
        }

        $where_clauses = [];
        foreach ($clauses as $clause) {
            if (!is_array($clause)) {
                continue;
            }

            if (count($clause) !== count($clause, COUNT_RECURSIVE)) {
                $this->makeWhere(...$clause);
                continue;
            }

            $column = null;
            $operator = '=';
            $value = null;
            $glue = 'AND';

            switch (count($clause)) {
                case 4:
                    [$column, $operator, $value, $glue] = $clause;
                    break;
                case 3:
                    [$column, $operator, $value] = $clause;
                    break;
                case 2:
                    [$column, $value] = $clause;
                    break;
                default:
                    throw new InvalidArgumentException('Invalid where clause');
            }

            if (!in_array($operator, ['=', '<>', '<', '>', '<=', '>=', 'LIKE', 'NOT LIKE'])) {
                throw new InvalidArgumentException("Invalid operator: {$operator}");
            }

            $where_clauses[] = [
                'column' => $column,
                'operator' => $operator,
                'value' => $value,
                'glue' => $glue,
            ];
        }

        $first = true;
        $where = '';
        $params = [];
        foreach ($where_clauses as $clause) {
            if ($first) {
                $where .= 'WHERE ';
                $first = false;
            } else {
                $where .= " {$clause['glue']} ";
            }

            $where .= "`{$clause['column']}` {$clause['operator']} ?";
            $params[] = $clause['value'];
        }

        return [$where, $params];
    }
}