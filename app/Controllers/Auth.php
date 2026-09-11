<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AuthAuditLogModel;

class Auth extends BaseController
{
    private UserModel $users;
    private AuthAuditLogModel $auditLogs;

    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOGIN_WINDOW_SECONDS = 900;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->auditLogs = new AuthAuditLogModel();
    }

    public function login()
    {
        if (session()->get('is_logged_in') === true && session()->get('user_role') === 'admin') {
            return redirect()->to(site_url('admin'));
        }

        return view('auth/login', [
            'redirectTo' => (string) (session()->getFlashdata('redirect_to') ?: site_url('admin')),
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email|max_length[190]',
            'password' => 'required|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Email atau password belum memenuhi format yang benar.');
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        [$emailAttemptKey, $ipAttemptKey] = $this->loginAttemptKeys($email);
        $emailAttempts = (int) (cache()->get($emailAttemptKey) ?? 0);
        $ipAttempts = (int) (cache()->get($ipAttemptKey) ?? 0);

        if ($emailAttempts >= self::MAX_LOGIN_ATTEMPTS || $ipAttempts >= self::MAX_LOGIN_ATTEMPTS) {
            $this->recordAuthEvent($email, 'rate_limited');
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan masuk. Silakan coba lagi dalam 15 menit.');
        }

        $user = $this->users->findAdminByEmail($email);

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            cache()->save($emailAttemptKey, $emailAttempts + 1, self::LOGIN_WINDOW_SECONDS);
            cache()->save($ipAttemptKey, $ipAttempts + 1, self::LOGIN_WINDOW_SECONDS);
            $this->recordAuthEvent($email, 'login_failed', $user['id'] ?? null);
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            $this->users->update($user['id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        }

        session()->regenerate(true);
        session()->set([
            'is_logged_in' => true,
            'user_id' => (int) $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
        ]);
        cache()->delete($emailAttemptKey);
        cache()->delete($ipAttemptKey);
        $this->recordAuthEvent($email, 'login_success', (int) $user['id']);

        $redirectTo = (string) $this->request->getPost('redirect_to');
        if (! str_starts_with($redirectTo, site_url())) {
            $redirectTo = site_url('admin');
        }

        return redirect()->to($redirectTo);
    }

    public function logout()
    {
        $this->recordAuthEvent((string) session()->get('user_email'), 'logout', (int) session()->get('user_id'));
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Anda telah keluar dari CMS.');
    }

    private function loginAttemptKeys(string $email): array
    {
        $ip = (string) ($this->request->getIPAddress() ?? 'unknown');
        return [
            'auth_login_email_' . hash('sha256', $email),
            'auth_login_ip_' . hash('sha256', $ip),
        ];
    }

    private function recordAuthEvent(string $email, string $event, ?int $userId = null): void
    {
        try {
            $this->auditLogs->insert([
                'email' => $email !== '' ? $email : null,
                'user_id' => $userId,
                'event' => $event,
                'ip_hash' => hash('sha256', (string) ($this->request->getIPAddress() ?? 'unknown')),
                'user_agent' => substr((string) $this->request->getUserAgent(), 0, 512),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $exception) {
            log_message('error', 'Auth audit logging failed: {message}', ['message' => $exception->getMessage()]);
        }
    }
}
