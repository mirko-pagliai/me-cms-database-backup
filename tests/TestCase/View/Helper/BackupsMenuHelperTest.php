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
 * BackupsMenuHelperTest class
 */
class BackupsMenuHelperTest extends MenuHelperTestCase
{
    /**
     * @test
     * @uses \MeCms\DatabaseBackup\View\Helper\BackupsMenuHelper::getLinks()
     */
    public function testGetLinks(): void
    {
        $this->assertEmpty($this->getLinksAsHtml());

        $this->setIdentity(['group' => ['name' => 'manager']]);
        $this->assertEmpty($this->getLinksAsHtml());

        $this->setIdentity(['group' => ['name' => 'admin']]);
        $this->assertSame([
            '<a href="/me-cms-database-backup/admin/backups" title="List backups">List backups</a>',
            '<a href="/me-cms-database-backup/admin/backups/add" title="Add backup">Add backup</a>',
        ], $this->getLinksAsHtml());
    }
}
