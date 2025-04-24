<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmailController;

use App\Http\kernel;

// Track email opens
Route::get('/track/open/{id}', function($id) {
    \Log::info("Email opened: $id"); // Log to file
    DB::table('email_tracking')->where('tracking_id', $id)->update([
        'status' => 'opened',
        'opened_at' => now()
    ]);
    
    // Return transparent pixel
    return response(base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='))
        ->header('Content-Type', 'image/gif');
})->name('track.open');

// Track link clicks
Route::get('/track/click/{id}', function($id, Request $request) {
    \Log::info("Link clicked: $id"); // Log to file
    DB::table('email_tracking')->where('tracking_id', $id)->update([
        'status' => 'clicked',
        'clicked_at' => now()
    ]);
    
    return redirect(urldecode($request->url));
})->name('track.click');
Route::group(['middleware' => 'admin.guest'], function() {
    Route::get('/', [EmailController::class, 'form'])->name('admin.login');
    Route::post('authenticate', [EmailController::class, 'data'])->name('admin.authenticate');
});

Route::group(['middleware' => 'admin.auth'], function(){
    Route::get('dashboard', [EmailController::class, 'index'])->name('admin.dashboard');
    Route::get('email_managment', [EmailController::class, 'change_email'])->name('admin.change');
    // Route::get('/emails/compose', [YourController::class, 'compose'])->name('emails.compose');
    Route::get('/compose', [EmailController::class, 'showComposePage'])->name('emails.compose');
    Route::post('/import/google-sheet', [EmailController::class, 'importGoogleSheet'])->name('emails.import.google');
    Route::get('/import/recipients', [EmailController::class, 'emailsrecipients'])->name('emails.recipients');

    // routes/web.php
    Route::post('/send-emails', [EmailController::class, 'sendBatch'])->name('send.emails');
    // Route::get('/test-google-api', function() {
    //     try {
    //         $client = new Google\Client();
    //         $client->setAuthConfig(storage_path('app/google-credentials.json'));
    //         $client->addScope(Google\Service\Sheets::SPREADSHEETS_READONLY);
            
    //         // Test with a public sample sheet
    //         $service = new Google\Service\Sheets($client);
    //         $response = $service->spreadsheets_values->get(
    //             '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms', // Sample Google Sheet
    //             'Class Data!A2:E'
    //         );
            
    //         return response()->json($response->getValues());
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ], 500);
    //     }
    // });
    
    Route::post('/admin/logout', [EmailController::class, 'logout'])->name('admin.logout');
});


