<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ThrottleFilter implements FilterInterface
{
    /**
     * Rate Limiting für Login-Versuche
     * Prüft nur ob IP bereits geblockt ist
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $cache = \Config\Services::cache();
        $ipAddress = $request->getIPAddress();
        $cacheKey = 'login_blocked_' . $ipAddress;
        
        // Prüfe ob IP geblockt ist
        $blockedUntil = $cache->get($cacheKey);
        
        if ($blockedUntil !== null && time() < $blockedUntil) {
            $remainingTime = $blockedUntil - time();
            $minutes = ceil($remainingTime / 60);
            
            // Redirect zur Login-Seite mit Flash-Message
            return redirect()->to('/login')
                ->with('error', "Zu viele fehlgeschlagene Login-Versuche. Bitte warten Sie {$minutes} Minute(n).");
        }
        
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}