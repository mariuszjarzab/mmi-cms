<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */
//określanie ścieżki
define('BASE_PATH', realpath(dirname(__FILE__) . '/../../../../'));

//ładowanie autoloadera aplikacji
require BASE_PATH . '/app/autoload.php';

//powołanie i uruchomienie aplikacji
$application = new \Mmi\App('\Mmi\App\BootstrapCli');
$application->run();

Cms\Model\Cron::run();