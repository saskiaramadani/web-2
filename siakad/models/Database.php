<?php

class Database
{
    private static ?self $instance = null;
    private ?PDO $conn = null;
    private ?PDOStatement $stmt = null;

    public function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new Exception('DB Connection Failed: ' . $e->getMessage());
        }
    }

    private function __clone()
    {
    }
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton.");
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    private function prepareAndBind(string $sql, array $params = []): void
    {
        $this->stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $paramType = match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default => PDO::PARAM_STR,
            };
            $this->stmt->bindValue(is_int($key) ? $key + 1 : $key, $value, $paramType);
        }
    }

    public function query(string $sql, array $params = []): static
    {
        $this->prepareAndBind($sql, $params);
        return $this;
    }

    public function execute(): bool
    {
        return $this->stmt?->execute() ?? false;
    }

    public function fetchAll(): array
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function fetchOne(): array|false
    {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function fetchAllAs(string $className): array
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_CLASS, $className);
    }

    public function fetchOneAs(string $className): object|false
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_CLASS, $className);
    }

    public function insert(string $table, array $data): string|false
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, array_values($data));
        return $this->execute() ? $this->conn->lastInsertId() : false;
    }


    public function update(string $table, array $data, string $where, array $whereParams = []): bool
    {
        $set = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE $table SET $set WHERE $where";
        $this->query($sql, array_merge(array_values($data), $whereParams));
        return $this->execute();
    }

    public function delete(string $table, string $where, array $params = []): bool
    {
        $sql = "DELETE FROM $table WHERE $where";
        $this->query($sql, $params);
        return $this->execute();
    }

    public function count(string $table, string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM $table" . ($where ? " WHERE $where" : "");
        $this->query($sql, $params);
        return (int) ($this->fetchOne()['total'] ?? 0);
    }

    public function affectedRows(): int
    {
        return $this->stmt?->rowCount() ?? 0;
    }

    public function lastInsertId(): string
    {
        return $this->conn?->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->conn?->beginTransaction() ?? false;
    }

    public function commit(): bool
    {
        return $this->conn?->commit() ?? false;
    }

    public function rollBack(): bool
    {
        return $this->conn?->rollBack() ?? false;
    }
}
