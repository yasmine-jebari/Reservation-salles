<?php

require_once __DIR__ . '/../Application/UserStore.php';
require_once __DIR__ . '/Database.php';

class UserMySQL implements UserStore
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function findByEmail(string $email): ?array
    {
        $st = $this->pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = ?');
        $st->execute([$email]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findById(int $id): ?array
    {
        $st = $this->pdo->prepare('SELECT id, name, email, password, role FROM users WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function emailExists(string $email, ?int $exceptId = null): bool
    {
        $st = $this->pdo->prepare('SELECT 1 FROM users WHERE email = ? AND id <> ?');
        $st->execute([$email, $exceptId ?? 0]);
        return (bool) $st->fetchColumn();
    }

    public function create(string $name, string $email, string $passwordHash): int
    {
        $st = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $st->execute([$name, $email, $passwordHash]);
        return (int) $this->pdo->lastInsertId();
    }

    public function updateProfile(int $id, string $name, string $email): void
    {
        $st = $this->pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
        $st->execute([$name, $email, $id]);
    }

    public function updatePassword(int $id, string $passwordHash): void
    {
        $st = $this->pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $st->execute([$passwordHash, $id]);
    }
    public function listAll(): array
    {
        $st = $this->pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC, id DESC');
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
    }