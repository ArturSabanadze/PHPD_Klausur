<?php
require 'TraitUser.php';

class User
{
    use T_User;

    // Only store what we need
    private ?int $id = null;
    private string $username;
    private string $email;
    private string $password_hash;

    public function __construct(array $data = [])
    {
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password_hash = password_hash($data['plain_password'], PASSWORD_BCRYPT);
    }


    // Optional getter
    public function __get($key)
    {
        return $this->$key ?? null;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Check if username or email already exists
    public function exist($db): bool
    {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1");
        $stmt->execute([':u' => $this->username, ':e' => $this->email]);
        return (bool) $stmt->fetch();
    }

    // Save the user into the database
    public function save($db): void
    {
        $insert = $db->prepare("
            INSERT INTO users (username, email, password_hash)
            VALUES (:username, :email, :hash)
        ");
        $insert->execute([
            ':username' => $this->username,
            ':email' => $this->email,
            ':hash' => $this->password_hash
        ]);

        $this->id = (int) $db->lastInsertId();
    }

    public function getAllUsers($pdo) : array
    {
        $stmt = $pdo->prepare("SELECT id, username, email, status, created_at, updated_at, deleted_at, last_login_at FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function banUser($pdo, string $username): void
    {
        $stmt = $pdo->prepare("UPDATE users SET status = 'banned' WHERE username = ?");
        $stmt->execute([$username]);
    }
}
?>
