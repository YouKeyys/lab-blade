<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertNotification;

class ProcessAlertEmails extends Command
{
    // Nama perintah yang akan dipanggil di terminal
    protected $signature = 'alerts:process-emails';
    
    // Deskripsi perintah
    protected $description = 'Cek alert baru yang belum dikirim emailnya, lalu kirim ke Supervisor & Admin';

    public function handle()
    {
        $this->info('🔍 Memulai pengecekan alert baru...');

        // 1. Ambil semua alert yang statusnya 'active' DAN belum dikirim emailnya (email_sent = 0)
        $unmailedAlerts = DB::table('alerts')
            ->join('devices', 'alerts.device_id', '=', 'devices.device_id')
            ->join('labs', 'devices.lab_id', '=', 'labs.lab_id')
            ->where('alerts.status', 'active')
            ->where('alerts.email_sent', 0)
            ->select('alerts.*', 'labs.lab_name', 'labs.lab_id')
            ->get();

        if ($unmailedAlerts->isEmpty()) {
            $this->info('✅ Tidak ada alert baru yang perlu dikirim.');
            return Command::SUCCESS;
        }

        $this->info("📬 Ditemukan {$unmailedAlerts->count()} alert baru yang perlu dikirim.");

        foreach ($unmailedAlerts as $alert) {
            $this->line("  ➡️  Memproses Alert ID: {$alert->alert_id} ({$alert->parameter} di {$alert->lab_name})");

            // 2. Cari Email Supervisor yang di-assign ke lab ini
            $supervisors = DB::table('users')
                ->join('supervisor_labs', 'users.user_id', '=', 'supervisor_labs.user_id')
                ->where('supervisor_labs.lab_id', $alert->lab_id)
                ->where('users.role', 'supervisor')
                ->pluck('users.email')
                ->toArray();

            // 3. Cari Email SEMUA Admin (sebagai fallback/tembusan)
            $admins = DB::table('users')
                ->where('role', 'admin')
                ->pluck('email')
                ->toArray();

            // 4. Gabungkan dan hapus duplikat
            $recipients = array_unique(array_merge($supervisors, $admins));

            if (empty($recipients)) {
                $this->warn("  ⚠️  Tidak ada penerima email untuk Lab: {$alert->lab_name}. Alert ditandai sebagai terkirim agar tidak diproses berulang.");
                DB::table('alerts')->where('alert_id', $alert->alert_id)->update(['email_sent' => 1]);
                continue;
            }

            // 5. Siapkan Data untuk Template Email
            $emailData = [
                'location'  => $alert->lab_name,
                'deviceId'  => $alert->device_id,
                'parameter' => $alert->parameter,
                'value'     => $alert->triggered_value,
                'level'     => $alert->level,
                'time'      => date('d M Y, H:i', strtotime($alert->triggered_at)),
            ];

            // 6. Kirim Email
            try {
                Mail::to($recipients)->send(new AlertNotification($emailData));
                
                // 7. Tandai bahwa email sudah dikirim
                DB::table('alerts')->where('alert_id', $alert->alert_id)->update(['email_sent' => 1]);
                
                $this->info("  ✅ Email terkirim ke " . count($recipients) . " penerima: " . implode(', ', $recipients));
            } catch (\Exception $e) {
                $this->error("  ❌ Gagal kirim email untuk Alert ID {$alert->alert_id}: " . $e->getMessage());
            }
        }

        $this->info('🏁 Selesai.');
        return Command::SUCCESS;
    }
}