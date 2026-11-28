<?php

interface UserStore
{
    public function findByEmail(string $email): ?array;
    public function findById(int $id): ?array;
    public function emailExists(string $email, ?int $exceptId = null): bool;
    public function create(string $name, string $email, string $passwordHash): int;
    public function updateProfile(int $id, string $name, string $email): void;
    public function updatePassword(int $id, string $passwordHash): void;
    public function listAll(): array;
    }