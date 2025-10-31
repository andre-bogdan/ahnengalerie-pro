<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Check if user is logged in
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            // Store intended URL for redirect after login
            session()->set('redirect_url', current_url());
            
            // Redirect to login with message
            return redirect()->to('/login')
                ->with('error', 'Bitte melden Sie sich an, um fortzufahren.');
        }

        // Check if admin access required
        if ($arguments && in_array('admin', $arguments)) {
            if (!session()->get('is_admin')) {
                return redirect()->to('/dashboard')
                    ->with('error', 'Zugriff verweigert. Admin-Berechtigung erforderlich.');
            }
        }
    }

    /**
     * After controller execution
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}