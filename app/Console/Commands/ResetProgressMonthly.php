<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;
use Carbon\Carbon;

class ResetProgressMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'progress:reset-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all site progress to "Belum Visit" on the 1st of each month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly progress reset...');
        
        // Reset all sites with "Sudah Visit" status to "Belum Visit"
        $updatedCount = Site::where('progres', 'Sudah Visit')
            ->update([
                'progres' => 'Belum Visit',
                'tgl_visit' => null,
                'keterangan' => null,
                'updated_at' => Carbon::now()
            ]);

        if ($updatedCount > 0) {
            $this->info("✅ Successfully reset {$updatedCount} sites to 'Belum Visit' status");
            
            // Log the reset action
            \Log::info("Monthly progress reset completed. {$updatedCount} sites reset to 'Belum Visit'");
        } else {
            $this->info('ℹ️  No sites found with "Sudah Visit" status to reset');
        }

        $this->info('Monthly progress reset completed successfully!');
        
        return Command::SUCCESS;
    }
}
