    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\SensorController;
    use App\Http\Controllers\LabController;
    use App\Http\Controllers\DeviceController;
    use App\Http\Controllers\AlertController;
    use App\Http\Controllers\ExportController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\UserController;

    // Sensors
    Route::get('/sensors/latest', [SensorController::class, 'getLatest']);
    Route::get('/sensors/lab/{labName}/history', [SensorController::class, 'getHistory']);

    // Labs
    Route::get('/labs', [LabController::class, 'index']);
    Route::get('/labs/detail', [LabController::class, 'getDetails']);
    Route::post('/labs', [LabController::class, 'store']);
    Route::put('/labs/{id}', [LabController::class, 'update']);
    Route::delete('/labs/{id}', [LabController::class, 'destroy']);
        
    // Devices
    Route::get('/devices', [DeviceController::class, 'index']);
    Route::post('/devices', [DeviceController::class, 'store']);
    Route::put('/devices/{id}', [DeviceController::class, 'update']);
    Route::get('/devices/{id}', [DeviceController::class, 'index']);
    Route::delete('/devices/{id}', [DeviceController::class, 'destroy']);
    Route::get('/devices/{id}/logs', [DeviceController::class, 'getLogs']); // <--- Rute Device Logs

    // Alerts & Rules
    Route::get('/alerts', [AlertController::class, 'index']);
    Route::get('/alerts/history', [AlertController::class, 'history']); // <--- Rute History
    Route::get('/alerts/lab/{labId}/rules', [AlertController::class, 'getRulesByLab']); // <--- Rute Rules Lab
    Route::post('/alerts/rules', [AlertController::class, 'storeRule']); // <--- Rute Simpan 4 Rules
    Route::delete('/alerts/rules/{ruleId}', [AlertController::class, 'destroyRule']); // <--- Rute Hapus Rule
    Route::put('/alerts/{id}/status', [AlertController::class, 'updateStatus']); // <--- Acknowledge & Resolve

    // Export
    Route::get('/export/download-form', [ExportController::class, 'downloadForm']);

    // Dashboard (Aggregated Data - Sangat Cepat)
    Route::get('/overview/overall', [DashboardController::class, 'getOverallData']);
    Route::get('/overview/lab/{labName}', [DashboardController::class, 'getLabData']);

    // Otentikasi
    Route::get('/setup', [AuthController::class, 'setup']);
    Route::post('/login', [AuthController::class, 'login']);

    // Manajemen User
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);