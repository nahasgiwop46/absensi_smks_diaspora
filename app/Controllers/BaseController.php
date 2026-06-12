<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Request;
use CodeIgniter\Validation\Validation;
use Psr\Log\LoggerInterface;
use CodeIgniter\HTTP\IncomingRequest;

abstract class BaseController extends Controller
{
    protected $request;
    protected $session;
    protected $validation;
    protected $currentUser = null;
    protected $currentRole = '';
    protected $isLoggedIn = false;
    protected $viewData = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->helpers = ['form', 'url', 'text', 'html', 'tanggal', 'auth', 'setting', 'notifikasi', 'menu', 'absensi', 'security'];

        parent::initController($request, $response, $logger);

        $this->session    = service('session');
        $this->validation = service('validation');

        // ✅ Auto update user data dari database
        if ($this->session->get('is_logged_in')) {
            try {
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($this->session->get('user_id'));
                
                if ($user) {
                    $this->isLoggedIn   = true;
                    $this->currentUser  = [
                        'user_id'      => $user['id'],
                        'username'     => $user['username'],
                        'nama_lengkap' => $user['nama_lengkap'],
                        'email'        => $user['email'],
                        'foto'         => $user['foto'] ?? null,
                        'role_id'      => $user['role_id'],
                        'role_kode'    => $this->session->get('role_kode'),
                        'role_nama'    => $this->session->get('role_nama'),
                    ];
                    $this->currentRole  = $this->session->get('role_kode') ?? '';
                    
                    // ✅ Update session dengan data terbaru
                    $this->session->set('nama_lengkap', $user['nama_lengkap']);
                    $this->session->set('foto', $user['foto'] ?? null);
                }
            } catch (\Exception $e) {
                // Fallback ke session
                $this->isLoggedIn   = true;
                $this->currentUser  = [
                    'user_id'      => $this->session->get('user_id'),
                    'username'     => $this->session->get('username'),
                    'nama_lengkap' => $this->session->get('nama_lengkap'),
                    'role_id'      => $this->session->get('role_id'),
                    'role_kode'    => $this->session->get('role_kode'),
                    'role_nama'    => $this->session->get('role_nama'),
                ];
                $this->currentRole  = $this->session->get('role_kode') ?? '';
            }
            
            // ✅ Auto logout jika idle > 30 menit
            $lastActivity = $this->session->get('last_activity');
            if ($lastActivity && (time() - $lastActivity > 1800)) {
                $this->session->destroy();
                header('Location: ' . base_url('login'));
                exit;
            }
            $this->session->set('last_activity', time());
        }

        $this->viewData = [
            'currentUser'  => $this->currentUser,
            'currentRole'  => $this->currentRole,
            'isLoggedIn'   => $this->isLoggedIn,
            'appName'      => 'Absensi SMK QR Code',
            'appVersion'   => '1.0.0',
            'breadcrumbs'  => [],
            'pageTitle'    => 'Dashboard',
        ];
    }

    // ==================== AUTH & ROLE ====================

    protected function requireLogin(string $redirectTo = '/login')
    {
        if (!$this->isLoggedIn) {
            return redirect()->to($redirectTo)->with('error', 'Silakan login terlebih dahulu.');
        }
        return false;
    }

    protected function hasRole($roles): bool
    {
        if (!$this->isLoggedIn) return false;
        if (is_string($roles)) return $this->currentRole === $roles;
        if (is_array($roles))   return in_array($this->currentRole, $roles);
        return false;
    }

    protected function requireRole($roles, string $redirectTo = '/login')
    {
        if (!$this->hasRole($roles)) {
            return redirect()->to($redirectTo)->with('error', 'Anda tidak memiliki akses.');
        }
        return false;
    }

    // ==================== FLASH MESSAGE ====================

    protected function setFlashSuccess(string $message): void
    {
        $this->session->setFlashdata('success', $message);
    }

    protected function setFlashError(string $message): void
    {
        $this->session->setFlashdata('error', $message);
    }

    protected function setFlashWarning(string $message): void
    {
        $this->session->setFlashdata('warning', $message);
    }

    // ==================== VIEW & LAYOUT ====================

    protected function setPageTitle(string $title): void
    {
        $this->viewData['pageTitle'] = $title;
    }

    protected function addBreadcrumb(string $name, string $url = ''): void
    {
        $this->viewData['breadcrumbs'][] = ['name' => $name, 'url' => $url];
    }

    protected function render(string $view, array $data = []): string
    {
        $viewData = array_merge($this->viewData, $data);
        $layout = $this->getLayoutByRole();
        $viewData['content'] = view($view, $viewData);
        return view($layout, $viewData);
    }

    protected function renderPartial(string $view, array $data = []): string
    {
        return view($view, array_merge($this->viewData, $data));
    }

    private function getLayoutByRole(): string
    {
        if (!$this->isLoggedIn) return 'layouts/auth';
        return match ($this->currentRole) {
            'admin'  => 'layouts/admin',
            'guru'   => 'layouts/guru',
            'siswa'  => 'layouts/siswa',
            'kepsek' => 'layouts/kepsek',
            default  => 'layouts/default',
        };
    }

    // ==================== JSON RESPONSE ====================

    protected function jsonResponse(bool $success, string $message = '', $data = null, int $statusCode = 200)
    {
        return $this->response->setJSON([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ])->setStatusCode($statusCode);
    }

    protected function jsonSuccess(string $message = 'Berhasil', $data = null, int $code = 200)
    {
        return $this->jsonResponse(true, $message, $data, $code);
    }

    protected function jsonError(string $message = 'Gagal', $data = null, int $code = 400)
    {
        return $this->jsonResponse(false, $message, $data, $code);
    }

    // ==================== VALIDATION & INPUT ====================

    protected function redirectBackWithErrors(array $errors = [])
    {
        $redirect = redirect()->back()->withInput();
        if (!empty($errors)) {
            $redirect->with('errors', $errors);
        }
        return $redirect;
    }

    protected function sanitizeInput(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }

    // ==================== PAGINATION ====================

    protected function getPagination(int $totalRows, int $perPage = 10): array
    {
        return [
            'totalRows'  => $totalRows,
            'perPage'    => $perPage,
            'page'       => (int) ($this->request->getGet('page') ?? 1),
        ];
    }
}