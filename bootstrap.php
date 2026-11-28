<?php
require_once __DIR__ . '/src/Infrastructure/Database.php';

// Planning (Personne B)
require_once __DIR__ . '/src/Application/PlanningStore.php';
require_once __DIR__ . '/src/Infrastructure/PlanningMySQL.php';

function planningStore(): PlanningStore {
    return new PlanningMySQL();
}

// Catalogue des salles (Personne A)
require_once __DIR__ . '/src/Application/CatalogueStore.php';
require_once __DIR__ . '/src/Infrastructure/CatalogueMySQL.php';

function catalogueStore(): CatalogueStore {
    return new CatalogueMySQL();
}

// --- Utilisateurs (Personne A) ---
require_once __DIR__ . '/src/Application/UserStore.php';
require_once __DIR__ . '/src/Infrastructure/UserMySQL.php';

function userStore(): UserStore {
    return new UserMySQL();
}

// --- Session, rôles, CSRF, vues ---
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}

function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function view(string $name, array $data = []): void {
    extract($data);
    require __DIR__ . '/views/' . $name . '.php';
}

function redirect(string $url) {
    header('Location: index.php?url=' . $url);
    exit;
}

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function requireLogin(): array {
    $user = currentUser();
    if ($user === null) {
        redirect('connexion');
    }
    return $user;
}

function requireAdmin(): array {
    $user = requireLogin();
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        exit('Accès refusé');
    }
    return $user;
}

function csrfToken(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfCheck(): void {
    $sent = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        http_response_code(419);
        exit('Session expirée, rechargez la page.');
    }
}