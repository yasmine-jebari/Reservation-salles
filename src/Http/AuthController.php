<?php

class AuthController
{
    private UserStore $users;

    public function __construct()
    {
        $this->users = userStore();
    }

    public function inscription(): void
    {
        $errors = [];
        $old = ['name' => '', 'email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();
            $name = trim($_POST['name'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['password_confirm'] ?? '';
            $old = ['name' => $name, 'email' => $email];

            if ($name === '' || mb_strlen($name) > 100) {
                $errors[] = 'Nom obligatoire (100 caractères maximum).';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
                $errors[] = 'Adresse e-mail invalide.';
            } elseif ($this->users->emailExists($email)) {
                $errors[] = 'Cette adresse e-mail est déjà utilisée.';
            }
            if (strlen($password) < 8) {
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }
            if ($password !== $confirm) {
                $errors[] = 'Les mots de passe ne correspondent pas.';
            }

            if (!$errors) {
                $id = $this->users->create($name, $email, password_hash($password, PASSWORD_DEFAULT));
                $this->openSession($this->users->findById($id));
                redirect('disponibilite');
            }
        }

        view('auth/register', compact('errors', 'old'));
    }

    public function connexion(): void
    {
        $errors = [];
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';
            $user = $this->users->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $this->openSession($user);
                redirect('disponibilite');
            }
            $errors[] = 'E-mail ou mot de passe incorrect.';
        }

        view('auth/login', compact('errors', 'email'));
    }

    public function deconnexion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();
            $_SESSION = [];
            session_destroy();
        }
        redirect('connexion');
    }

    public function profil(): void
    {
        $current = requireLogin();
        $user = $this->users->findById($current['id']);
        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();
            $name = trim($_POST['name'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $currentPw = $_POST['current_password'] ?? '';
            $newPw = $_POST['new_password'] ?? '';

            if ($name === '' || mb_strlen($name) > 100) {
                $errors[] = 'Nom obligatoire (100 caractères maximum).';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
                $errors[] = 'Adresse e-mail invalide.';
            } elseif ($this->users->emailExists($email, $user['id'])) {
                $errors[] = 'Cette adresse e-mail est déjà utilisée.';
            }
            if ($newPw !== '') {
                if (!password_verify($currentPw, $user['password'])) {
                    $errors[] = 'Mot de passe actuel incorrect.';
                } elseif (strlen($newPw) < 8) {
                    $errors[] = 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
                }
            }

            if (!$errors) {
                $this->users->updateProfile($user['id'], $name, $email);
                if ($newPw !== '') {
                    $this->users->updatePassword($user['id'], password_hash($newPw, PASSWORD_DEFAULT));
                }
                $_SESSION['user']['name'] = $name;
                $user = $this->users->findById($user['id']);
                $success = true;
            }
        }

        view('auth/profile', compact('user', 'errors', 'success'));
    }

    private function openSession(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'   => (int) $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
        ];
    }
}