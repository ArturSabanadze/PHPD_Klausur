<?php

trait T_User
{

    //Login verification
    public function verifyUsername($pdo, $username): bool
    {
        $stmt = $pdo->prepare("
        SELECT id, username, email
        FROM users
        WHERE username = :u OR email = :u
        LIMIT 1
    ");

        $stmt->execute([':u' => trim($username)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        $this->id = $user['id'];
        $this->username = trim($user['username']);
        $this->email = trim($user['email']);

        return true;
    }

    public function verifyPassword($pdo, $password_plain, $username): bool
    {
        $stmt = $pdo->prepare("
        SELECT id, username, email, status, password_hash
        FROM users
        WHERE username = :u OR email = :u
        LIMIT 1
    ");

        $stmt->execute([':u' => trim($username)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password_plain, $user['password_hash'])) {
            return false;
        }

        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->email = $user['email'];
        $this->status = $user['status'];

        return true;
    }




}