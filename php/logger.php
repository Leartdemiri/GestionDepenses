    <?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once __DIR__ . '/../vendor/autoload.php';

function getLogger(): Logger {
    static $logger = null;

    if (!$logger) {
        $logger = new Logger('UB$');
        $logPath = __DIR__ . '/../logs/app.log';
        $logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));
    }

    return $logger;
}
