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

namespace MeCms\DatabaseBackup\Test\TestCase\Form;

use DatabaseBackup\Utility\BackupExport;
use Exception;
use MeCms\DatabaseBackup\Form\BackupForm;
use MeCms\TestSuite\TestCase;

/**
 * BackupFormTest class
 */
class BackupFormTest extends TestCase
{
    /**
     * @var \DatabaseBackup\Utility\BackupExport&\PHPUnit\Framework\MockObject\MockObject
     */
    protected BackupExport $BackupExport;

    /**
     * @var \MeCms\DatabaseBackup\Form\BackupForm
     */
    protected BackupForm $Form;

    /**
     * Called before every test method
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if (empty($this->BackupExport)) {
            $this->BackupExport = $this->getMockBuilder(BackupExport::class)
                ->onlyMethods(['export', 'filename'])
                ->getMock();

            $this->BackupExport->method('filename')->willReturnSelf();
        }

        $this->Form ??= new BackupForm();
    }

    /**
     * Test validation.
     * It tests the proper functioning of the example data.
     * @test
     */
    public function testValidationExampleData(): void
    {
        $this->assertTrue($this->Form->validate(['filename' => 'file.sql']));
        $this->assertEmpty($this->Form->getErrors());

        $expected = ['filename' => ['_required' => 'This field is required']];
        $this->assertFalse($this->Form->validate([]));
        $this->assertEquals($expected, $this->Form->getErrors());
    }

    /**
     * Test validation for `filename` property
     * @test
     */
    public function testValidationForFilename(): void
    {
        $expected = ['filename' => ['extension' => 'Valid extensions: sql, sql.gz, sql.bz2']];
        foreach ([
            'file',
            'file.sql.',
            'file.bz2',
            'file.gz',
            '.sql',
            'file.gif',
        ] as $value) {
            $this->assertFalse($this->Form->validate(['filename' => $value]));
            $this->assertEquals($expected, $this->Form->getErrors());
        }

        foreach (['file.sql', 'file.sql.bz2', 'file.sql.gz'] as $value) {
            $this->assertTrue($this->Form->validate(['filename' => $value]));
            $this->assertEmpty($this->Form->getErrors());
        }

        $expected = ['filename' => ['maxLength' => 'Must be at most 255 chars']];
        $this->assertFalse($this->Form->validate(['filename' => str_repeat('a', 252) . '.sql']));
        $this->assertEquals($expected, $this->Form->getErrors());

        $this->assertTrue($this->Form->validate(['filename' => str_repeat('a', 251) . '.sql']));
        $this->assertEmpty($this->Form->getErrors());
    }

    /**
     * Tests for `getBackupExportInstance()` method
     * @test
     */
    public function testGetBackupExportInstance(): void
    {
        $instance = $this->invokeMethod($this->Form, 'getBackupExportInstance');
        $this->assertInstanceOf(BackupExport::class, $instance);
        $this->assertEquals($instance, $this->getProperty($this->Form, 'BackupExport'));
    }

    /**
     * Tests for `_execute()` method
     * @test
     */
    public function testExecute(): void
    {
        $BackupForm = $this->getMockBuilder(BackupForm::class)
            ->onlyMethods(['getBackupExportInstance'])
            ->getMock();

        $BackupForm->method('getBackupExportInstance')->willReturnCallback(function () {
            $this->BackupExport->method('export')->willReturn('test.sql');

            return $this->BackupExport;
        });
        $this->assertTrue($BackupForm->execute(['filename' => 'test.sql']));

        $BackupForm->method('getBackupExportInstance')->willThrowException(new Exception());
        $this->assertFalse($BackupForm->execute(['filename' => 'test.sql']));
    }
}
