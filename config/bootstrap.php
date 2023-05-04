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
use MeCms\DatabaseBackup\View\Helper\BackupsMenuHelper;

//Sets the menu helpers that will be used
Configure::write('MeCms/DatabaseBackup.MenuHelpers', [BackupsMenuHelper::class]);

//Sets the directories to be created and which must be writable
Configure::write('MeCms/DatabaseBackup.WritableDirs', [getConfigOrFail('DatabaseBackup.target')]);

if (!getConfig('DatabaseBackup.mailSender')) {
    Configure::write('DatabaseBackup.mailSender', getConfigOrFail('MeCms.email.webmaster'));
}
