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

namespace MeCms\DatabaseBackup\View\Helper;

use MeCms\View\Helper\AbstractMenuHelper;

/**
 * BackupsMenuHelper
 */
class BackupsMenuHelper extends AbstractMenuHelper
{
    /**
     * Gets the links for this menu. Each links is an array of parameters
     * @return array[]
     * @throws \ErrorException
     */
    public function getLinks(): array
    {
        //Only admins can access this controller
        if (!$this->Identity->isGroup('admin')) {
            return [];
        }

        $params = ['controller' => 'Backups', 'plugin' => 'MeCms/DatabaseBackup', 'prefix' => ADMIN_PREFIX];
        $links[] = [__d('me_cms/database_backup', 'List backups'), ['action' => 'index'] + $params];
        $links[] = [__d('me_cms/database_backup', 'Add backup'), ['action' => 'add'] + $params];

        return $links;
    }

    public function getOptions(): array
    {
        return ['icon' => 'database'];
    }

    /**
     * Gets the title for this menu
     * @return string
     */
    public function getTitle(): string
    {
        return __d('me_cms/database_backup', 'Backups');
    }
}
