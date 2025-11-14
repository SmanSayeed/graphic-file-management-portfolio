# Queue Cron Job Setup for cPanel Shared Hosting

Since your shared hosting doesn't have shell access, you need to set up a cron job in cPanel to process queue jobs automatically.

## Step 1: Generate a Secure Token

1. Generate a secure random token (you can use an online generator or run this in your local terminal):
   ```bash
   php -r "echo bin2hex(random_bytes(32));"
   ```

2. Add the token to your `.env` file on the server:
   ```env
   QUEUE_CRON_TOKEN=your-generated-token-here
   ```

## Step 2: Set Up cPanel Cron Job

1. Log in to your cPanel account
2. Navigate to **Advanced** → **Cron Jobs** (or search for "Cron Jobs" in cPanel)
3. Click **Add New Cron Job** or **Standard (cPanel)**
4. Configure the cron job as follows:

   **Common Settings:**
   - **Minute:** `*` (every minute) or `*/5` (every 5 minutes)
   - **Hour:** `*` (every hour)
   - **Day:** `*` (every day)
   - **Month:** `*` (every month)
   - **Weekday:** `*` (every day of the week)

   **Command:**
   ```bash
   /usr/bin/curl -s "https://yourdomain.com/queue/cron?token=YOUR_SECRET_TOKEN&max_jobs=5" > /dev/null 2>&1
   ```

   Replace:
   - `yourdomain.com` with your actual domain
   - `YOUR_SECRET_TOKEN` with the token you generated in Step 1
   - `max_jobs=5` can be adjusted (1-25) based on your needs

## Step 3: Test the Cron Job

Before setting up the automated cron, test it manually:

1. Open your browser or use curl:
   ```
   https://yourdomain.com/queue/cron?token=YOUR_SECRET_TOKEN&max_jobs=5
   ```

2. You should see a JSON response like:
   ```json
   {
     "status": "success",
     "processed": 2,
     "pending": 0,
     "failed": 0
   }
   ```

## Recommended Cron Frequency

- **Every minute (`* * * * *`)**: Best for real-time processing, but uses more server resources
- **Every 5 minutes (`*/5 * * * *`)**: Good balance for most applications
- **Every 15 minutes (`*/15 * * * *`)**: Suitable for low-traffic sites

## Alternative: Using PHP CLI

If `curl` is not available, you can use PHP directly:

```bash
/usr/bin/php /home/username/public_html/artisan queue:work database --once --tries=3 --timeout=120
```

Replace `/home/username/public_html` with your actual project path.

**Note:** This method processes only one job per execution. You'll need to run it more frequently (every minute) or process multiple jobs in a loop.

## Monitoring Queue Jobs

You can monitor queue jobs from the admin panel:

1. Log in to your admin panel
2. Go to **Queue Monitor** (in the sidebar)
3. View pending jobs, failed jobs, and recent runs

## Troubleshooting

### Cron job not running?
- Check cPanel cron job logs
- Verify the URL is accessible
- Ensure the token matches your `.env` file
- Check file permissions

### Jobs not processing?
- Verify queue connection is set to `database` (not `sync`) in Storage Management
- Check the `jobs` table exists in your database
- Review Laravel logs: `storage/logs/laravel.log`

### Token authentication failing?
- Ensure `QUEUE_CRON_TOKEN` is set in your `.env` file
- Clear config cache: Run `php artisan config:clear` (if you have access)
- Verify the token in the cron command matches the `.env` file

## Security Notes

- **Never commit your `.env` file** to version control
- Use a strong, random token (at least 32 characters)
- The cron route is public but protected by token authentication
- Consider restricting access by IP if possible (advanced)

## Manual Queue Processing

You can also process queue jobs manually from the admin panel:

1. Go to **Storage Management** → **Queue Management** section
2. Enter the number of jobs to process (1-25)
3. Click **Process Jobs**

This is useful for testing or processing jobs immediately without waiting for the cron job.

