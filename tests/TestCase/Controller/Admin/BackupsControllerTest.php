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

namespace MeCms\DatabaseBackup\Test\TestCase\Controller\Admin;

use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\ORM\Entity;
use Cake\TestSuite\EmailTrait;
use DatabaseBackup\Utility\BackupImport;
use MeCms\DatabaseBackup\Form\BackupForm;
use MeCms\TestSuite\ControllerTestCase;
use Tools\Filesystem;

/**
 * BackupsControllerTest class
 */
class BackupsControllerTest extends ControllerTestCase
{
    use EmailTrait;

    /**
     * @var \MeCms\DatabaseBackup\Controller\Admin\BackupsController
     */
    protected $_controller;

    /**
     * Internal method to create a backup file
     * @param string $extension Extension
     * @return string File path
     */
    protected function createSingleBackup(string $extension = 'sql'): string
    {
        $file = getConfigOrFail('DatabaseBackup.target') . DS . 'backup.' . $extension;
        Filesystem::instance()->createFile($file);

        return $file;
    }

    /**
     * Internal method to create some backup files
     * @return array<int, string> Files paths
     */
    protected function createSomeBackups(): array
    {
        return array_map([$this, 'createSingleBackup'], ['sql', 'sql.gz', 'sql.bz2']);
    }

    /**
     * Called after every test method
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        Filesystem::instance()->unlinkRecursive(getConfigOrFail('DatabaseBackup.target'), false, true);
    }

    /**
     * Adds additional event spies to the controller/view event manager
     * @param \Cake\Event\EventInterface $event A dispatcher event
     * @param \Cake\Controller\Controller|null $controller Controller instance
     * @return void
     */
    public function controllerSpy(EventInterface $event, ?Controller $controller = null): void
    {
        parent::controllerSpy($event, $controller);

        $this->_controller->BackupImport = $this->getMockBuilder(BackupImport::class)
            ->setMethods(['import'])
            ->getMock();
    }

    /**
     * Tests for `isAuthorized()` method
     * @test
     */
    public function testIsAuthorized(): void
    {
        $this->assertGroupsAreAuthorized([
            'admin' => true,
            'manager' => false,
            'user' => false,
        ]);
    }

    /**
     * Tests for `index()` method
     * @test
     */
    public function testIndex(): void
    {
        $this->createSomeBackups();
        $this->get($this->url + ['action' => 'index']);
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('Admin' . DS . 'Backups' . DS . 'index.php');
        $this->assertContainsOnlyInstancesOf(Entity::class, $this->viewVariable('backups'));
    }

    /**
     * Tests for `add()` method
     * @test
     */
    public function testAdd(): void
    {
        $url = $this->url + ['action' => 'add'];

        $this->get($url);
        $this->assertResponseOkAndNotEmpty();
        $this->assertTemplate('Admin' . DS . 'Backups' . DS . 'add.php');
        $this->assertInstanceof(BackupForm::class, $this->viewVariable('backup'));

        //POST request. Data are invalid
        $this->post($url, ['filename' => 'backup.txt']);
        $this->assertResponseOkAndNotEmpty();
        $this->assertResponseContains(I18N_OPERATION_NOT_OK);
        $this->assertFileDoesNotExist(getConfigOrFail('DatabaseBackup.target') . DS . 'backup.txt');

        //POST request. Now data are valid
        $this->post($url, ['filename' => 'backup.sql']);
        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage(I18N_OPERATION_OK);
        $this->assertFileExists(getConfigOrFail('DatabaseBackup.target') . DS . 'backup.sql');
    }

    /**
     * Tests for `delete()` method
     * @test
     */
    public function testDelete(): void
    {
        $file = $this->createSingleBackup();
        $this->post($this->url + ['action' => 'delete', urlencode(basename($file))]);
        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage(I18N_OPERATION_OK);
        $this->assertFileDoesNotExist($file);
    }

    /**
     * Tests for `deleteAll()` method
     * @test
     */
    public function testDeleteAll(): void
    {
        $files = $this->createSomeBackups();
        $this->post($this->url + ['action' => 'deleteAll']);
        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage(I18N_OPERATION_OK);
        array_map([$this, 'assertFileDoesNotExist'], $files);
    }

    /**
     * Tests for `download()` method
     * @test
     */
    public function testDownload(): void
    {
        $file = $this->createSingleBackup();
        $this->get($this->url + ['action' => 'download', urlencode(basename($file))]);
        $this->assertFileResponse($file);
    }

    /**
     * Tests for `restore()` method
     * @test
     */
    public function testRestore(): void
    {
        Cache::writeMany(['firstKey' => 'firstValue', 'secondKey' => 'secondValue']);
        $file = $this->createSingleBackup();
        $this->post($this->url + ['action' => 'restore', urlencode(basename($file))]);
        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage(I18N_OPERATION_OK);
        array_map([$this, 'assertNull'], [Cache::read('firstKey'), Cache::read('secondKey')]);
    }

    /**
     * Tests for `send()` method
     * @test
     */
    public function testSend(): void
    {
        $file = $this->createSingleBackup();
        $this->post($this->url + ['action' => 'send', urlencode(basename($file))]);
        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage(I18N_OPERATION_OK);
        $this->assertMailSentFrom(Configure::read('DatabaseBackup.mailSender'));
        $this->assertMailSentTo(Configure::read('DatabaseBackup.mailSender'));
        $this->assertMailSentWith('Database backup ' . basename($file) . ' from localhost', 'subject');
        $this->assertMailContainsAttachment(basename($file), compact('file') + ['mimetype' => mime_content_type($file)]);
    }
}
