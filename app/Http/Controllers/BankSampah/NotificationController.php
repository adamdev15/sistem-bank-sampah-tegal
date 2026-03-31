<?php

namespace App\Http\Controllers\BankSampah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:bank_sampah', 'user.status']);
    }

    public function index()
    {
        // Notifikasi sederhana - bisa dikembangkan dengan database notifications
        $notifications = [
            [
                'type' => 'info',
                'message' => 'Selamat datang di sistem BASMAN',
                'time' => now()->diffForHumans()
            ]
        ];

        return view('bank-sampah.notifications.index', compact('notifications'));
    }
}