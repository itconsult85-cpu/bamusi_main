<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if ($session->get('is_logged_in') === true && $session->get('user_role') === 'admin') {
            return null;
        }

        $session->setFlashdata('error', 'Silakan masuk sebagai administrator terlebih dahulu.');
        return redirect()->to(site_url('login'))->with('redirect_to', current_url());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
