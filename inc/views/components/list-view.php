<?php if (!empty($list_only)): ?>
    <div class="p-8 scroll-auto">
    <table>
        <thead>
            <tr>
                <td><?= language("title") ?></td>
                <td><?= language("episode") ?></td>
                <td><?= language("episodes") ?></td>
                <td><?= language("season") ?></td>
                <td><?= language("state") ?></td>
                <td><?= language("type") ?></td>
                <?php if(!$user || $user && $is_user_user): ?>
                <td><?= language("action") ?></td>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach (array_reverse($list_only) as $key => $value): ?>
            <tr <?= $i % 2 == 0 ? "style='background:rgb(0,0,0,.1);'" : ""  ?>>
                <td><?= $value["title"] ?? "" ?></td>
                <td><?= $value["episode"] ?? "" ?></td>
                <td><?= $value["episodes"] ?? "" ?></td>
                <td><?= $value["season"] ?? "" ?></td>
                <td><?= language($value["state"] ?? "") ?></td>
                <td><?= language($value["type"] ?? "") ?></td>
                <?php if(!$user || $user && $is_user_user): ?>
                    <td class="flex flex-between gap-4">
                        <a href="?id=<?= $key ?? "" ?>&action=edit<?= $user ? "&to_user=true" : "" ?>">📝</a>
                        <a href="?id=<?= $key ?? "" ?>&action=delete<?= $user ? "&to_user=true" : "" ?>" onclick="return confirm('<?= language("confirm_delete"); ?>');">❌</a>
                    </td>
                <?php endif; ?>
            </tr>
            <?php $i += 1; endforeach; ?>
        </tbody>
    </table>
    </div>
<?php endif; ?>