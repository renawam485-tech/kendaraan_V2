<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user tidak login, lanjutkan
        if (!Auth::check()) {
            return $next($request);
        }

        // Ambil last activity dari session
        $lastActivity = session('last_activity', time());
        
        // Hitung timeout dalam detik (lifetime dalam menit)
        $timeout = config('session.lifetime') * 60;

        // Jika idle melebihi timeout
        if ((time() - $lastActivity) > $timeout) {
            Auth::logout();
            session()->flush();
            
            // Redirect ke login dengan pesan
            return redirect('/login')->with('error', 
                'Session habis karena tidak ada aktivitas selama ' . config('session.lifetime') . ' menit'
            );
        }

        // Reset last activity setiap ada request
        session(['last_activity' => time()]);

        return $next($request);
    }
}