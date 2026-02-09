<?php

namespace Quizic\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * Trust only the reverse proxy network by default.
     *
     * @var array|string|null
     */
    protected $proxies;

    public function __construct()
    {
        $trusted = env('TRUSTED_PROXIES');
        $this->proxies = $trusted ? array_map('trim', explode(',', $trusted)) : null;
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
