<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ExportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================
// PUBLIC ROUTES
// =====================
Route::get('/', function () {
    return redirect()->route('login');
});

// =====================
// AUTH ROUTES (GUEST ONLY)
// =====================
Route::middleware('guest')->group(function () {
    
    // Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    // Register Bank Sampah
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.post');
    
    // Forgot Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'reset'])
        ->name('password.update');
    
    // Force Change Password (for temporary password)
    Route::get('change-password/required', [ChangePasswordController::class, 'showRequiredForm'])
        ->name('password.change.required');
    Route::post('change-password/force', [ChangePasswordController::class, 'forceChange'])
        ->name('password.change.force');
});

// =====================
// LOGOUT (AUTHENTICATED ONLY)
// =====================
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Link verifikasi baru sudah dikirim.');
    })->middleware('throttle:6,1')->name('verification.send');
});

Route::get('/email/verify/{id}/{hash}', function (Request $request, string $id, string $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Link verifikasi tidak valid.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    if ($user->status === 'menunggu_verifikasi') {
        $user->update(['status' => 'aktif']);
    }

    return redirect()->route('login')->with('success', 'Email berhasil diverifikasi. Silakan login.');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

// =====================
// AUTHENTICATED ROUTES (REQUIRE LOGIN)
// =====================
Route::middleware(['auth'])->group(function () {
    
    // Home/Redirect after login
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // =====================
    // ADMIN ROUTES (REQUIRE ADMIN ROLE)
    // =====================
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['verified', 'role:admin', 'user.status'])
        ->group(function () {
            
        // Dashboard
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');
        
        // ========== DATA MASTER BANK SAMPAH ==========
        Route::resource('bank-sampah', App\Http\Controllers\Admin\BankSampahController::class);
        
        // Additional routes for bank sampah
        Route::get('bank-sampah/{id}/get-kelurahan', 
            [App\Http\Controllers\Admin\BankSampahController::class, 'getKelurahan'])
            ->name('bank-sampah.get-kelurahan');
        
        // ========== DATA OPERASIONAL ==========
        Route::get('operasional', [App\Http\Controllers\Admin\OperasionalController::class, 'index'])
            ->name('operasional.index');
        
        Route::get('operasional/{bankSampah}', [App\Http\Controllers\Admin\OperasionalController::class, 'show'])
            ->name('operasional.show');
        
        // ========== LAPORAN BULANAN ==========
        Route::get('laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])
            ->name('laporan.index');
        
        Route::get('laporan/{laporan}', [App\Http\Controllers\Admin\LaporanController::class, 'show'])
            ->name('laporan.show');
        
        Route::post('laporan/{laporan}/verify', [App\Http\Controllers\Admin\LaporanController::class, 'verify'])
            ->name('laporan.verify');
        
        Route::post('laporan/{laporan}/reject', [App\Http\Controllers\Admin\LaporanController::class, 'reject'])
            ->name('laporan.reject');
        
        Route::get('laporan/{laporan}/edit', [App\Http\Controllers\Admin\LaporanController::class, 'edit'])
            ->name('laporan.edit');
        
        Route::put('laporan/{laporan}', [App\Http\Controllers\Admin\LaporanController::class, 'update'])
            ->name('laporan.update');
        
        // ========== USER MANAGEMENT ==========
        Route::get('users', [UserController::class, 'index'])
            ->name('users.index');
        
        Route::get('users/{user}/reset', [UserController::class, 'showResetForm'])
            ->name('users.reset');
        
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.reset-password');
        
        Route::post('users/{user}/verify', [UserController::class, 'verify'])
            ->name('users.verify');
        
        Route::delete('users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');
        
        // ========== EXPORT DATA ==========
        Route::get('export', [ExportController::class, 'index'])
            ->name('export.index');
        
        Route::post('export/generate', [ExportController::class, 'generate'])
            ->name('export.generate');
        
        Route::get('export/preview', [ExportController::class, 'preview'])
            ->name('export.preview');
        
        // ========== WILAYAH MANAGEMENT ==========
        Route::get('wilayah/kecamatan', [App\Http\Controllers\Admin\WilayahController::class, 'kecamatan'])
            ->name('wilayah.kecamatan');
        
        Route::get('wilayah/kelurahan', [App\Http\Controllers\Admin\WilayahController::class, 'kelurahan'])
            ->name('wilayah.kelurahan');
        Route::post('wilayah/kecamatan', [App\Http\Controllers\Admin\WilayahController::class, 'storeKecamatan'])
            ->name('wilayah.kecamatan.store');
        Route::put('wilayah/kecamatan/{kecamatan}', [App\Http\Controllers\Admin\WilayahController::class, 'updateKecamatan'])
            ->name('wilayah.kecamatan.update');
        Route::delete('wilayah/kecamatan/{kecamatan}', [App\Http\Controllers\Admin\WilayahController::class, 'destroyKecamatan'])
            ->name('wilayah.kecamatan.destroy');
        Route::post('wilayah/kelurahan', [App\Http\Controllers\Admin\WilayahController::class, 'storeKelurahan'])
            ->name('wilayah.kelurahan.store');
        Route::put('wilayah/kelurahan/{kelurahan}', [App\Http\Controllers\Admin\WilayahController::class, 'updateKelurahan'])
            ->name('wilayah.kelurahan.update');
        Route::delete('wilayah/kelurahan/{kelurahan}', [App\Http\Controllers\Admin\WilayahController::class, 'destroyKelurahan'])
            ->name('wilayah.kelurahan.destroy');
        
        // ========== ACTIVITY LOGS ==========
        Route::get('logs', [App\Http\Controllers\Admin\LogController::class, 'index'])
            ->name('logs.index');
        
        // ========== REPORTS & STATISTICS ==========
        Route::get('reports/statistics', [App\Http\Controllers\Admin\ReportController::class, 'statistics'])
            ->name('reports.statistics');
    });
    
    // =====================
    // BANK SAMPAH ROUTES (REQUIRE BANK_SAMPAH ROLE)
    // =====================
    Route::prefix('bank-sampah')
        ->name('bank-sampah.')
        ->middleware(['verified', 'role:bank_sampah', 'user.status'])
        ->group(function () {
            
        // Dashboard
        Route::get('dashboard', [App\Http\Controllers\BankSampah\DashboardController::class, 'index'])
            ->name('dashboard');
        
        // ========== DATA OPERASIONAL ==========
        Route::prefix('operasional')->name('operasional.')->group(function () {
            Route::get('/', [App\Http\Controllers\BankSampah\OperasionalController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\BankSampah\OperasionalController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\BankSampah\OperasionalController::class, 'store'])->name('store');
            Route::get('/edit', [App\Http\Controllers\BankSampah\OperasionalController::class, 'edit'])->name('edit');
            Route::put('/update', [App\Http\Controllers\BankSampah\OperasionalController::class, 'update'])->name('update');
            Route::get('/show', [App\Http\Controllers\BankSampah\OperasionalController::class, 'show'])->name('show');
            
            // Export Routes
            Route::get('/export/pdf', [App\Http\Controllers\BankSampah\OperasionalController::class, 'exportPdf'])
                ->name('export.pdf');
            Route::get('/export/excel', [App\Http\Controllers\BankSampah\OperasionalController::class, 'exportExcel'])
                ->name('export.excel');
        });
        
        // ========== LAPORAN BULANAN ==========
        Route::resource('laporan', App\Http\Controllers\BankSampah\LaporanController::class)->except(['destroy']);
        
        // Additional laporan routes
        Route::get('laporan/check-period/{period}', 
            [App\Http\Controllers\BankSampah\LaporanController::class, 'checkPeriod'])
            ->name('laporan.check-period');
        
        // ========== PROFILE ==========
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [App\Http\Controllers\BankSampah\ProfileController::class, 'index'])->name('index');
            Route::put('/update', [App\Http\Controllers\BankSampah\ProfileController::class, 'update'])->name('update');
            Route::get('/change-password', [App\Http\Controllers\BankSampah\ProfileController::class, 'changePasswordForm'])
                ->name('change-password.form');
            Route::post('/change-password', [App\Http\Controllers\BankSampah\ProfileController::class, 'changePassword'])
                ->name('change-password');
        });
        
        // ========== DOWNLOAD/EXPORT ==========
        Route::prefix('download')->name('download.')->group(function () {
            Route::get('/', [App\Http\Controllers\BankSampah\DownloadController::class, 'index'])->name('index');
            
            // Download Operasional
            Route::get('/operasional', [App\Http\Controllers\BankSampah\DownloadController::class, 'operasional'])
                ->name('operasional');
            
            // Download Single Laporan
            Route::get('/laporan/{laporan}', [App\Http\Controllers\BankSampah\DownloadController::class, 'laporan'])
                ->name('laporan');
            
            // Download All Laporan
            Route::get('/all-reports', [App\Http\Controllers\BankSampah\DownloadController::class, 'allReports'])
                ->name('all-reports');
            
            // Download by Period
            Route::get('/reports-by-period', [App\Http\Controllers\BankSampah\DownloadController::class, 'reportsByPeriod'])
                ->name('reports-by-period');
            
            // Preview Laporan
            Route::get('/preview/laporan/{laporan}', [App\Http\Controllers\BankSampah\DownloadController::class, 'previewLaporan'])
                ->name('preview.laporan');
            
            // Preview Operasional
            Route::get('/preview/operasional', [App\Http\Controllers\BankSampah\DownloadController::class, 'previewOperasional'])
                ->name('preview.operasional');
        });
        
        // ========== NOTIFICATIONS (OPTIONAL) ==========
        Route::get('notifications', [App\Http\Controllers\BankSampah\NotificationController::class, 'index'])
            ->name('notifications.index');
    });
});

// =====================
// API ROUTES (FOR AJAX)
// =====================
Route::prefix('api')->middleware('auth')->group(function () {
    // Get kelurahan by kecamatan
    Route::get('kelurahan/{kecamatanId}', 
        [App\Http\Controllers\Api\WilayahController::class, 'getKelurahan'])
        ->name('api.kelurahan');
});

// =====================
// ERROR PAGES
// =====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});