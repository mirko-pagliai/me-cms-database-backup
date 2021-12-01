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
$this->extend('MeCms./Admin/common/index');
$this->assign('title', __d('me_cms/database_backup', 'Database backups'));

$this->append('actions', $this->Html->button(
    I18N_ADD,
    ['action' => 'add'],
    ['class' => 'btn-success', 'icon' => 'plus']
));
$this->append('actions', $this->Form->postButton(
    __d('me_cms/database_backup', 'Delete all'),
    ['action' => 'delete-all'],
    ['class' => 'btn-danger', 'icon' => 'trash']
));
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th><?= I18N_FILENAME ?></th>
            <th class="text-nowrap text-center"><?= __d('me_cms/database_backup', 'Extension') ?></th>
            <th class="text-nowrap text-center"><?= __d('me_cms/database_backup', 'Compression') ?></th>
            <th class="text-nowrap text-center"><?= __d('me_cms/database_backup', 'Size') ?></th>
            <th class="text-nowrap text-center"><?= I18N_DATE ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($backups as $backup) : ?>
            <tr>
                <td>
                    <strong>
                        <?= $this->Html->link($backup->get('filename'), ['action' => 'download', $backup->get('slug')]) ?>
                    </strong>
                    <?php
                    $actions = [
                        $this->Html->link(
                            I18N_DOWNLOAD,
                            ['action' => 'download', $backup->get('slug')],
                            ['icon' => 'download']
                        ),
                        $this->Form->postLink(__d('me_cms/database_backup', 'Restore'), ['action' => 'restore', $backup->get('slug')], [
                            'icon' => 'upload',
                            'confirm' => __d('me_cms/database_backup', 'This will overwrite the current database and ' .
                                'some data may be lost. Are you sure?'),
                        ]),
                        $this->Form->postLink(__d('me_cms', 'Send'), ['action' => 'send', $backup->get('slug')], [
                            'icon' => 'envelope',
                            'confirm' => __d('me_cms/database_backup', 'The backup file will be sent by mail. Are you sure?'),
                        ]),
                        $this->Form->postLink(
                            I18N_DELETE,
                            ['action' => 'delete', $backup->get('slug')],
                            ['class' => 'text-danger', 'icon' => 'trash-alt', 'confirm' => I18N_SURE_TO_DELETE]
                        ),
                    ];

                    echo $this->Html->ul($actions, ['class' => 'actions']);
                    ?>
                </td>
                <td class="text-nowrap text-center">
                    <?= $backup->get('extension') ?>
                </td>
                <td class="text-nowrap text-center">
                    <?= $backup->get('compression') ?>
                </td>
                <td class="text-nowrap text-center">
                    <?= $this->Number->toReadableSize($backup->get('size')) ?>
                </td>
                <td class="text-nowrap text-center">
                    <?= $backup->get('datetime')->i18nFormat() ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
