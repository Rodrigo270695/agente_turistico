<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{

    protected $rootView = 'app';


    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'user.roles'=> $request->user() ? $request->user()->roles->pluck('name') : [],
            'user.permissions'=> $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name') : [],
            'toast' => session('toast'),
            'flash' => [
                'message' => fn () => $request->session()->get('message')
            ]
        ]);
    }
}
