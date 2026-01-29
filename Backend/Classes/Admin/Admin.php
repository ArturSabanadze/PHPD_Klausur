<?php

class Admin {
   

    public function getAllUsers(PDO $pdo) {
        $stmt = $pdo->prepare("SELECT id, email, username, status, created_at FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFlagedComments(PDO $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE reported = TRUE");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeUserStatus(PDO $pdo, $userId, $newStatus) {
        $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $newStatus, ':id' => $userId]);
    }

    public function getAllContactMessages(PDO $pdo) {
        $stmt = $pdo->prepare("SELECT cm.*, u.username, u.email
                                     FROM contact_messages cm
                                     JOIN users u ON cm.user_id = u.id
                                     ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     
}