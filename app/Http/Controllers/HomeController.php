<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AktivitasLog;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        // Update last login
        $user->updateLastLogin();
        
        // Log aktivitas login
        AktivitasLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Login Sistem',
            'modul' => 'Auth',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        // Redirect berdasarkan role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('bank-sampah.dashboard');
    }
}