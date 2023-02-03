<?php
/** @noinspection PhpUnhandledExceptionInspection */
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
namespace MeCms\DatabaseBackup\Test\TestCase\Command\Install;

use MeTools\TestSuite\CommandTestCase;
use Tools\Filesystem;

/**
 * CreateDirectoriesCommandTest class
 */
class CreateDirectoriesCommandTest extends CommandTestCase
{
    /**
     * Tests for `execute()` method
     * @test
     */
    public function testExecute(): void
    {
        if (!file_exists(getConfigOrFail('DatabaseBackup.target'))) {
            mkdir(getConfigOrFail('DatabaseBackup.target'), 0755, true);
        }

        $this->exec('me_tools.create_directories -v');
        $this->assertOutputContains('File or directory `' . Filesystem::instance()->rtr(getConfigOrFail('DatabaseBackup.target')) . '` already exists');
    }
}
