<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Fournit une instance unique du logger Monolog pour tout le projet.
 * 
 * - Le logger est instancié une seule fois (pattern singleton via `static $logger`)
 * - Les logs sont enregistrés dans un fichier `logs/app.log` avec le niveau DEBUG
 *
 * @return Logger L'instance de logger Monolog configurée
 */
function getLogger(): Logger {
    // Utilisation d'une variable statique pour s'assurer qu'on ne crée qu'un seul logger
    static $logger = null;

    if (!$logger) {
        // Création du logger avec un nom symbolique "UB$"
        $logger = new Logger('UB$');

        // Définition du chemin du fichier de log
        $logPath = __DIR__ . '/logs/app.log';

        // Ajout d’un handler pour écrire dans le fichier log avec le niveau DEBUG minimum
        $logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));
    }
    
    return $logger;
}
