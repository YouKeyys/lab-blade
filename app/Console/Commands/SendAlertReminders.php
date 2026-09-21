<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertNotification;

class SendAlertReminders extends Command
{
    protected $signature = 'alerts:send-reminders';
    protected $description = 'Kirim pengingat untuk alert yang belum di-resolve (dengan anti-spam)';

    public function handle()
    {
        $this->info('🔍 Mengecek alert yang belum di-resolve untuk dikirim pengingat...');

        // LOGIKA ANTI-SPAM: 
        // Ambil alert yang statusnya BUKAN 'Resolved', 
        // DAN (belum pernah dikirim reminder ATAU sudah lebih dari 2 jam sejak reminder terakhir)
        // (Ganti '2' dengan '24' jika ingin pengingat harian)
        $query = "
            SELECT a.*, l.lab_name, l.lab_id 
            FROM alerts a
            JOIN devices d ON a.device_id = d.device_id
            JOIN labs l ON d.lab_id = l.lab_id
            WHERE a.status != 'Resolved' 
            AND (a.last_reminder_sent_at IS NULL OR DATEDIFF(hour, a.last_reminder_sent_at, GETDATE()) >= 2)
        ";

        $unresolvedAlerts = DB::select($query);

        if (empty($unresolvedAlerts)) {
            $this->info('✅ Tidak ada alert yang membutuhkan pengingat saat ini.');
            return Command::SUCCESS;
        }

        $this->info("📬 Ditemukan {$unresolvedAlerts->count()} alert yang perlu diingatkan.");

        foreach ($unresolvedAlerts as $alert) {
            $this->line("  ➡️  Mengingatkan Alert ID: {$alert->alert_id} ({$alert->parameter} di {$alert->lab_name})");

            // 1. Cari Penerima (Supervisor Lab + Admin)
            $supervisors = DB::table('users')
                ->join('supervisor_labs', 'users.user_id', '=', 'supervisor_labs.user_id')
                ->where('supervisor_labs.lab_id', $alert->lab_id)
                ->where('users.role', 'supervisor')
                ->pluck('users.email')->toArray();

            $admins = DB::table('users')->where('role', 'admin')->pluck('email')->toArray();
            $recipients = array_unique(array_merge($supervisors, $admins));

            if (empty($recipients)) {
                DB::table('alerts')->where('alert_id', $alert->alert_id)->update(['last_reminder_sent_at' => DB::raw('GETDATE()')]);
                continue;
            }

            // 2. Kirim Email Reminder
            $emailData = [
                'location'  => $alert->lab_name,
                'deviceId'  => $alert->device_id,
                'parameter' => $alert->parameter,
                'value'     => $alert->triggered_value,
                'level'     => $alert->level,
                'time'      => date('d M Y, H:i', strtotime($alert->triggered_at)),
            ];

            try {
                // Perhatikan parameter kedua 'true' menandakan ini adalah REMINDER
                Mail::to($recipients)->send(new AlertNotification($emailData, true));
                
                // 3. UPDATE WAKTU REMINDER TERAKHIR (Ini yang mencegah spam!)
                DB::table('alerts')->where('alert_id', $alert->alert_id)->update([
                    'last_reminder_sent_at' => DB::raw('GETDATE()')
                ]);
                
                $this->info("  ✅ Pengingat terkirim ke " . count($recipients) . " penerima.");
            } catch (\Exception $e) {
                $this->error("  ❌ Gagal kirim reminder: " . $e->getMessage());
            }
        }

        $this->info('🏁 Selesai.');
        return Command::SUCCESS;
}
}