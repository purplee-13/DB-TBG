# Automatic Monthly Progress Reset Setup

## Overview
This setup automatically resets all site progress from "Sudah Visit" to "Belum Visit" on the 1st of each month at 00:00.

## Files Created/Modified

### 1. Artisan Command: `app/Console/Commands/ResetProgressMonthly.php`
- **Purpose**: Resets all sites with "Sudah Visit" status to "Belum Visit"
- **Command**: `php artisan progress:reset-monthly`
- **Features**:
  - Resets `progres` to "Belum Visit"
  - Clears `tgl_visit` (visit date)
  - Clears `operator` field
  - Updates `updated_at` timestamp
  - Logs the action for audit trail

### 2. Scheduler Configuration: `routes/console.php`
- **Schedule**: Runs monthly on the 1st at 00:00
- **Prevention**: Uses `withoutOverlapping()` to prevent duplicate runs
- **Description**: Clear task description for monitoring

## Setup Instructions

### For Development/Testing:
```bash
# Test the command manually
php artisan progress:reset-monthly

# Check scheduled tasks
php artisan schedule:list

# Run scheduler manually (for testing)
php artisan schedule:run
```

### For Production:
1. **Set up Cron Job** on your server:
   ```bash
   # Add this to your crontab (crontab -e)
   * * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
   ```

2. **Verify Cron is Working**:
   ```bash
   # Check if cron is running
   php artisan schedule:list
   
   # Monitor logs
   tail -f storage/logs/laravel.log
   ```

## What Happens Automatically:

### Every 1st of the Month at 00:00:
1. **Finds all sites** with `progres = 'Sudah Visit'`
2. **Resets them to**:
   - `progres = 'Belum Visit'`
   - `tgl_visit = null`
   - `keterangan = null`
   - `updated_at = current timestamp`
3. **Logs the action** in Laravel logs
4. **Prevents overlapping** runs for safety

## Manual Commands:

```bash
# Run reset manually anytime
php artisan progress:reset-monthly

# Check what's scheduled
php artisan schedule:list

# Test scheduler without waiting
php artisan schedule:run
```

## Monitoring:

### Check Logs:
```bash
# View recent logs
tail -f storage/logs/laravel.log | grep "Monthly progress reset"
```

### Database Verification:
```sql
-- Check current progress status
SELECT progres, COUNT(*) as count FROM sites GROUP BY progres;

-- Check recent updates
SELECT site_code, progres, updated_at FROM sites 
WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
ORDER BY updated_at DESC;
```

## Troubleshooting:

### If Scheduler Doesn't Run:
1. **Check Cron**: Ensure cron is installed and running
2. **Check Permissions**: Ensure Laravel can write to logs
3. **Check Path**: Verify the project path in cron job
4. **Test Manually**: Run `php artisan schedule:run`

### If Command Fails:
1. **Check Database**: Ensure sites table exists
2. **Check Permissions**: Ensure database user has UPDATE permissions
3. **Check Logs**: Look for error messages in Laravel logs

## Security Notes:
- Command includes `withoutOverlapping()` to prevent duplicate runs
- All actions are logged for audit trail
- Only affects sites with "Sudah Visit" status
- Preserves all other data (site info, service area, etc.)

## Success Indicators:
- ✅ Command runs without errors
- ✅ Sites are reset to "Belum Visit"
- ✅ Logs show successful completion
- ✅ Scheduler runs automatically on 1st of month
