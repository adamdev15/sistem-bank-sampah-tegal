<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'user.status']);
    }

    public function index(Request $request)
    {
        $query = AktivitasLog::with('user')->latest();
        
        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }
        
        // Filter by module
        if ($request->has('modul') && $request->modul) {
            $query->where('modul', $request->modul);
        }
        
        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        $logs = $query->paginate(50);
        
        return view('admin.logs.index', compact('logs'));
    }
}