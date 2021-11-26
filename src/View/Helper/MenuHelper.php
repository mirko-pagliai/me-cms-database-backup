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

use Cake\View\Helper;

/**
 * Menu Helper.
 *
 * This helper contains methods that will be called automatically to generate
 *  menus for the admin layout.
 * You don't need to call these methods manually, use instead the
 *  `MenuBuilderHelper` helper.
 *
 * Each method must return an array with four values:
 *  - the menu links, as an array of parameters;
 *  - the menu title;
 *  - the options for the menu title;
 *  - the controllers handled by this menu, as an array.
 *
 * See the `\MeCms\View\Helper\MenuBuilderHelper::generate()` method for more
 *  information.
 * @property \MeCms\View\Helper\AuthHelper $Auth
 */
class MenuHelper extends Helper
{
    /**
     * Helpers
     * @var array
     */
    public $helpers = ['MeCms.Auth'];

    /**
     * Internal function to generate the menu for "backups" actions
     * @return array Array with links, title, title options and handled controllers
     */
    public function backups(): array
    {
        //Only admins can access this controller
        if (!$this->Auth->isGroup('admin')) {
            return [];
        }

        $params = ['controller' => 'Backups', 'plugin' => 'MeCms/DatabaseBackup', 'prefix' => ADMIN_PREFIX];
        $links[] = [__d('me_cms_database_backup', 'List backups'), ['action' => 'index'] + $params];
        $links[] = [__d('me_cms_database_backup', 'Add backup'), ['action' => 'add'] + $params];

        return [$links, __d('me_cms_database_backup', 'Backups'), ['icon' => 'database'], ['Backups']];
    }
}
