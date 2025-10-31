<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => \App\Filters\AuthFilter::class,
        'throttle'      => \App\Filters\ThrottleFilter::class,
    ];

    /**
     * Liste von Filtern, die immer angewendet werden
     * Format: ['alias' => ['before' => ['route1', 'route2']]]
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            'csrf' => ['except' => ['persons/search', 'persons/tree-data']],  // AJAX Routes ausschließen
            'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * Methoden-spezifische Filter
     */
    public array $methods = [];

    /**
     * Filter für spezifische Routes
     * Diese werden NUR genutzt, wenn Filter NICHT in Routes.php definiert sind
     */
    public array $filters = [
        // Optional: Hier könntest du auch throttle definieren, aber besser in Routes.php
        // 'throttle' => ['before' => ['login', 'login/*']],
    ];
}