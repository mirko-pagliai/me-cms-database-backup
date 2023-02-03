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

namespace MeCms\DatabaseBackup\Test\TestCase\View\Helper;

use MeCms\TestSuite\MenuHelperTestCase;

/**
 * MenuHelperTest class
 * @property \MeCms\DatabaseBackup\View\Helper\MenuHelper $Helper
 */
class MenuHelperTest extends MenuHelperTestCase
{
    /**
     * @test
     * @uses \MeCms\DatabaseBackup\View\Helper\MenuHelper::backups()
     */
    public function testBackups(): void
    {
        foreach (['user', 'manager'] as $name) {
            $this->setIdentity(['group' => compact('name')]);
            $this->assertEmpty($this->Helper->backups());
        }

        $this->setIdentity(['group' => ['name' => 'admin']]);
        [$links,,, $handledControllers] = $this->Helper->backups();
        $this->assertNotEmpty($links);
        $this->assertEquals(['Backups'], $handledControllers);
    }
}
