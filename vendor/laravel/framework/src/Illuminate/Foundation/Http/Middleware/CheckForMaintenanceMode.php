<?php

namespace Illuminate\Foundation\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\IpUtils;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;

class CheckForMaintenanceMode
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The URIs that should be accessible while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        'adminlogin',
        'adminsubmit/*'
    ];
    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        // if ($this->app->isDownForMaintenance()) {
        //     $data = json_decode(file_get_contents($this->app->storagePath().'/framework/down'), true);

        //     if (isset($data['allowed']) && IpUtils::checkIp($request->ip(), (array) $data['allowed'])) {
        //         return $next($request);
        //     }

        //     if ($this->inExceptArray($request)) {
        //         return $next($request);
        //     }

        //     throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
        // }

        // return $next($request);
        if ($this->app->isDownForMaintenance() && !$this->isBackendRequest($request)) {
            $data = json_decode(file_get_contents($this->app->storagePath() . '/framework/down'), true);

            throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should be accessible in maintenance mode.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
    private function isBackendRequest($request)
    {
        // dd($request);
        // dd($request->is('adminsubmit/*'));   
        return ($request->is('ims/application/*') or $request->is('/*') or $request->is('adminlogin/*') or $request->is('adminsubmit/*'));
    }
}
