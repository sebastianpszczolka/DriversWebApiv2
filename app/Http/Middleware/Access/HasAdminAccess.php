<?php

namespace App\Http\Middleware\Access;


use App\Exceptions\BaseException;
use App\Exceptions\NoPermissionsException;
use App\Security\AccessProvider;
use Closure;
use Illuminate\Http\Request;

class HasAdminAccess
{
    protected AccessProvider $accessProvider;

    public function __construct(AccessProvider $accessProvider)
    {
        $this->accessProvider = $accessProvider;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws BaseException
     * @throws NoPermissionsException
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->accessProvider->isAdmin()) {
            throw new NoPermissionsException();
        }

        return $next($request);
    }
}
