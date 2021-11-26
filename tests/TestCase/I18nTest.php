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

namespace MeCms\DatabaseBackup\Test\TestCase;

use Cake\I18n\I18n;
use MeTools\TestSuite\TestCase;

/**
 * I18nTest class
 */
class I18nTest extends TestCase
{
    /**
     * Tests I18n translations
     * @test
     */
    public function testI18n(): void
    {
        $translator = I18n::getTranslator('database_backup', 'it');
        $this->assertEquals('Aggiungi backup', $translator->translate('Add backup'));
        $this->assertEquals('Il file di backup sarà inviato tramite mail. Sei sicuro?', $translator->translate('The backup file will be sent by mail. Are you sure?'));
    }
}
