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
 * @property \MeCms\Photos\View\Helper\MenuHelper $Helper
 */
class MenuHelperTest extends MenuHelperTestCase
{
    /**
     * Tests for `backups()` method
     * @test
     */
    public function testBackups(): void
    {
        $this->assertEmpty($this->Helper->backups());

        $this->writeAuthOnSession(['group' => ['name' => 'manager']]);
        $this->assertEmpty($this->Helper->backups());

        $this->writeAuthOnSession(['group' => ['name' => 'admin']]);
        [$links,,, $handledControllers] = $this->Helper->backups();
        $this->assertNotEmpty($links);
        $this->assertEquals(['Backups'], $handledControllers);
    }
}
