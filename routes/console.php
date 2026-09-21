<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;



// 1. Cek alert BARU setiap 1 menit (yang sudah kamu buat)
Schedule::command('alerts:process-emails')->everyMinute();

// 2. Cek alert BELUM DI-RESOLVE setiap 1 jam (untuk reminder)
Schedule::command('alerts:send-reminders')->hourly();