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

use Cake\Core\Configure;

//Sets directories to be created and must be writable
$writableDirs = Configure::read('WRITABLE_DIRS', []);
if (!in_array(getConfigOrFail('DatabaseBackup.target'), $writableDirs)) {
    Configure::write('WRITABLE_DIRS', array_merge($writableDirs, [getConfigOrFail('DatabaseBackup.target')]));
}

if (!getConfig('DatabaseBackup.mailSender')) {
    Configure::write('DatabaseBackup.mailSender', getConfigOrFail('MeCms.email.webmaster'));
}
