<?php
declare(strict_types=1);

/**
 * This file is part of me-cms-database-backup.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/me-cms-database-backup
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;

ini_set('intl.default_locale', 'en_US');
date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

define('ROOT', dirname(__DIR__) . DS);
define('VENDOR', ROOT . 'vendor' . DS);
define('CORE_PATH', VENDOR . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('TESTS', ROOT . 'tests' . DS);
define('TEST_APP', TESTS . 'test_app' . DS);
define('APP', TEST_APP . 'TestApp' . DS);
define('APP_DIR', 'TestApp');
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', APP . 'webroot' . DS);
define('TMP', sys_get_temp_dir() . DS . 'me_cms_database_backup' . DS);
define('CONFIG', APP . 'config' . DS);
define('CACHE', TMP . 'cache' . DS);
define('LOGS', TMP . 'log' . DS);
define('SESSIONS', TMP . 'sessions' . DS);
define('UPLOADED', WWW_ROOT . 'files' . DS);

foreach ([
    TMP,
    LOGS,
    SESSIONS,
    CACHE . 'models',
    CACHE . 'persistent',
    CACHE . 'views',
] as $dir) {
    @mkdir($dir, 0777, true);
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once CORE_PATH . 'config' . DS . 'bootstrap.php';

Configure::write('debug', true);
Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'base' => false,
    'baseUrl' => false,
    'dir' => APP_DIR,
    'webroot' => 'webroot',
    'wwwRoot' => WWW_ROOT,
    'fullBaseUrl' => 'http://localhost',
    'imageBaseUrl' => 'img/',
    'jsBaseUrl' => 'js/',
    'cssBaseUrl' => 'css/',
    'paths' => [
        'plugins' => [APP . 'Plugin' . DS],
        'templates' => [APP . 'templates' . DS],
    ],
]);
Configure::write('Session', ['defaults' => 'php']);
Configure::write('Assets.target', TMP . 'assets');
Configure::write('DatabaseBackup', ['connection' => 'test', 'target' => TMP . 'backups']);
Configure::write('pluginsToLoad', ['Thumber/Cake', 'MeCms', 'MeCms/DatabaseBackup']);

Cache::setConfig([
    '_cake_core_' => [
        'engine' => 'File',
        'prefix' => 'cake_core_',
        'serialize' => true,
    ],
    '_cake_model_' => [
        'engine' => 'File',
        'prefix' => 'cake_model_',
        'serialize' => true,
    ],
    'default' => [
        'engine' => 'File',
        'prefix' => 'default_',
        'serialize' => true,
    ],
]);

if (!getenv('db_dsn')) {
    putenv('db_dsn=mysql://travis@localhost/test?encoding=utf8&quoteIdentifiers=true');
}
ConnectionManager::setConfig('test', ['url' => getenv('db_dsn')]);

Log::setConfig('error', [
    'className' => 'File',
    'path' => LOGS,
    'file' => 'error',
    'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
]);

TransportFactory::setConfig('debug', ['className' => 'Debug']);
Mailer::setConfig('default', ['transport' => 'debug', 'log' => true]);

$_SERVER['PHP_SELF'] = '/';
