<?php
/*
MIT License

Copyright (c) 2026 Armin Deck

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

view("components/header", ["auth" => $model->auth()]);
?>
    <main class="main">
        <?php view("components/message"); ?>
        <div class="content">
            <div class="button-switch-content">
                <a href="<?= route() ?>">AniPelis</a>
                <a href="<?= route("birthday") ?>"><?= language("birthday") ?></a>
                <a href="<?= route("goals") ?>" class="active"><?= language("goals") ?></a>
            </div>
        </div>
        <form class="form" method="post">
            <h2><?= language("goals") ?> (<?= language("private") ?>)</h2>
            <input type="text" name="goal" id="goal" value="<?= getListValueGetTmp($list_only, "id", "goal") ?>" placeholder="<?= language("goal") ?>" title="<?= language("goal") ?>" required>
            <input type="date" name="date" id="date" value="<?= getListValueGetTmp($list_only, "id", "date") ?>" title="<?= language("date") ?>" required>
            <hgroup class="flex flex-wrap flex-between gap-4">
                <select name="time" id="time" required>
                    <?php foreach ([
                        "" => "time",
                        "short" => "short",
                        "medium" => "medium",
                        "long" => "long"
                        ] as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $key == "" ? 'class="option_title"' : '' ?> <?= getListValueGetTmp($list_only, "id", "time") == $key ? "selected" : "" ?>><?= language($value) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="state" id="state" required>
                    <?php foreach ([
                        "" => "state",
                        "in_progress" => "in_progress",
                        "on_pause" => "on_pause",
                        "completed" => "completed"
                        ] as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $key == "" ? 'class="option_title"' : '' ?> <?= getListValueGetTmp($list_only, "id", "state") == $key ? "selected" : "" ?>><?= language($value) ?></option>
                    <?php endforeach; ?>
                </select>
            </hgroup>
            <button type="submit" name="add" id="add"><?= language(getListValueGetTmp($list_only, "id", "goal") ? "edit" : "add") ?></button>
        </form>
        <hr>
        <div class="p-8 scroll-auto">
            <table>
                <thead>
                    <tr>
                        <td><?= language("goal") ?></td>
                        <td><?= language("state") ?></td>
                        <td><?= language("time") ?></td>
                        <td><?= language("date") ?></td>
                        <td><?= language("action") ?></td>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($list_order_by_state as $key => $value): ?>
                    <tr <?= $i % 2 == 0 ? "style='background:rgb(0,0,0,.1);'" : ""  ?>>
                        <td><?= $value["goal"] ?></td>
                        <td><?= language($value["state"]) ?></td>
                        <td><?= language($value["time"]) ?></td>
                        <td title="<?= $value["date"] ?>"><?= strDate($value["date"]) ?></td>
                        <td>
                            <a href="?action=edit&id=<?= $key ?>">📝</a>
                            <a href="?action=delete&id=<?= $key ?>&confirm=1" onclick="return confirm('Quieres eliminar los datos de <?= $value["goal"] ?>');">❌</a>
                        </td>
                    </tr>
                    <?php $i++; endforeach ?>
                </tbody>
            </table>
        </div>
    </main>
<?php view("components/footer"); ?>