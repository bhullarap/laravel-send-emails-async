## Instructions
 - Laravel Jobs To Implement Send Emails Async
 - Redis Service
     - Cache Data.
     - Get Cached Data
 - Elastic Search Service.
     - To Index Data
     - To Get Indexed Data
 - Php Unit tests
     - Email Controller endpoints
     - Send Email Job  
 - Updgraded Laravel From version 9 to 10

## Second Method of implmenting Async functionality for sending emails is to use Symfony Processes Component.

## Open your server's crontab configuration
crontab -e

## Add the following line to run the Laravel scheduler every minute:
## Replace /path-to-your-laravel-project with the actual path to your Laravel project.
* * * * * cd /path-to-your-laravel-project && php artisan schedule:run >> /dev/null 2>&1

## Now, the php artisan queue:work redis --queue=emails command will be executed every minute as scheduled, processing jobs from the "emails" queue using the Redis driver.
## Please note that you should configure your Laravel application to use Redis for the queue driver and ensure that your queue jobs are properly dispatched to the "emails" queue.