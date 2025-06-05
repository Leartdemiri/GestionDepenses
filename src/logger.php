<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Provides a single instance of the Monolog logger for the entire project.
 * 
 * - The logger is instantiated only once (singleton pattern via `static $logger`)
 * - Logs are saved in a `logs/app.log` file with DEBUG level
 *
 * @return Logger The configured Monolog logger instance
 */
function getLogger(): Logger {
    // Use a static variable to ensure the logger is created only once
    static $logger = null;

    if (!$logger) {
        // Create the logger with a symbolic name "UB$"
        $logger = new Logger('UB$');

        // Define the path to the log file
        $logPath = __DIR__ . '/logs/app.log';

        // Add a handler to write to the log file with a minimum level of DEBUG
        $logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));
    }
    
    return $logger;
}
