<?php

class User
{
    private Database $db;
    private string $table = 'users';
    private const HASH_COST = 10;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(int $limit = 10, int $offset = 0): array
    {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit OFFSET :offset", [
            ':limit' => $limit,
            ':offset' => $offset,
        ]);
        return $this->db->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id", [':id' => $id]);
        return $this->db->fetchOne();
    }

    public function getByUsername(string $username): array|false
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE username = :username", [':username' => $username]);
        return $this->db->fetchOne();
    }

    public function getByEmail(string $email): array|false
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE email = :email", [':email' => $email]);
        return $this->db->fetchOne();
    }

    public function usernameExists(string $username): bool
    {
        return $this->getByUsername($username) !== false;
    }

    public function emailExists(string $email): bool
    {
        return $this->getByEmail($email) !== false;
    }

    public function authenticate(string $username, string $password): array|false
    {
        $user = $this->getByUsername($username);
        if ($user && $this->verifyPassword($password, $user['password'])) {

            return $user;
        }
        return false;
    }

    public function create(array $data): string|false
    {
        $data = array_map([$this, 'sanitizeInput'], $data);
        $this->db->query("INSERT INTO {$this->table} (username, email, password, name, role, created_at) 
                          VALUES (:username, :email, :password, :name, :role, NOW())", [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => self::HASH_COST]),
            ':name' => $data['name'],
            ':role' => $data['role'] ?? 'user',
        ]);
        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }

    public function update(int $id, array $data): bool
    {
        // Sanitize inputs
        $data = array_map([$this, 'sanitizeInput'], $data);

        // Use the DB update method directly
        return $this->db->update(
            $this->table,    // table name
            $data,     // data to update
            'id = ?',        // where condition
            [$id]            // where parameters
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->delete(
            $this->table,    // table name
            'id = ?',        // where condition
            [$id]            // where parameters
        );
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function count(): int
    {
        $this->db->query("SELECT COUNT(*) as count FROM {$this->table}");
        $result = $this->db->fetchOne();
        return (int) ($result['count'] ?? 0);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $this->db->query("UPDATE {$this->table} SET password = :password, updated_at = NOW() WHERE id = :id", [
            ':password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => self::HASH_COST]),
            ':id' => $id,
        ]);
        return $this->db->execute();
    }

    public function generateResetToken(string $email): string|false
    {
        $user = $this->getByEmail($email);
        if (!$user)
            return false;

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->db->query("UPDATE {$this->table} SET reset_token = :token, reset_expires = :expires WHERE id = :id", [
            ':token' => $token,
            ':expires' => $expires,
            ':id' => $user['id'],
        ]);

        return $this->db->execute() ? $token : false;
    }

    public function verifyResetToken(string $token): array|false
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE reset_token = :token AND reset_expires > NOW()", [':token' => $token]);
        return $this->db->fetchOne();
    }

    public function clearResetToken(int $id): bool
    {
        $this->db->query("UPDATE {$this->table} SET reset_token = NULL, reset_expires = NULL WHERE id = :id", [':id' => $id]);
        return $this->db->execute();
    }

    private function sanitizeInput($input): string
    {
        return htmlspecialchars(trim($input));
    }
}