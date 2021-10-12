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
$this->extend('MeCms./common/form');
$this->assign('title', $title = __d('me_cms_database_backup', 'Add backup'));
?>

<?= $this->Form->create($backup); ?>
<fieldset>
    <?= $this->Form->control('filename', [
        'default' => 'backup_{$DATABASE}_{$DATETIME}.sql.gz',
        'help' => __d('me_cms_database_backup', 'Valid extensions: {0}', 'sql, sql.gz, sql.bz2'),
        'label' => I18N_FILENAME,
    ]) ?>
</fieldset>
<?= $this->Form->submit($title) ?>
<?= $this->Form->end() ?>

<table class="table mt-4">
    <thead>
        <tr>
            <th><?= __d('me_cms_database_backup', 'Pattern') ?></th>
            <th><?= I18N_DESCRIPTION ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>{$DATABASE}</code></td>
            <td><?= __d('me_cms_database_backup', 'Database name') ?>.</td>
        </tr>
        <tr>
            <td><code>{$DATETIME}</code></td>
            <td>
                <?= __d('me_cms_database_backup', 'Datetime. This is the equivalent of {0}', $this->Html->code('date(\'YmdHis\')')) ?>
            </td>
        </tr>
        <tr>
            <td><code>{$HOSTNAME}</code></td>
            <td><?= __d('me_cms_database_backup', 'Database hostname') ?></td>
        </tr>
        <tr>
            <td><code>{$TIMESTAMP}</code></td>
            <td>
                <?= __d('me_cms_database_backup', 'Timestamp. This is the equivalent of {0}', $this->Html->code('time()')) ?>
            </td>
        </tr>
    </tbody>
</table>
