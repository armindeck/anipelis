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
?>

<form method="post" class="form" id="formProcess">
    <h3><?= !empty($user) ? "AniPelis - " : "" ?> <?= language(getListValueGetTmp($list_only, "id", "title") ? "edit" : "add") ?> <?= empty($user) ? "(" . language("public") . ")" : "" ?></h3>
    <input type="text" name="title" id="title" placeholder="<?= language("title") ?>" value="<?= getListValueGetTmp($list_only, "id", "title") ?>" required>
    <input type="url" name="url" id="url" placeholder="<?= language("url") . " (". language("optional") .")" ?>" value="<?= getListValueGetTmp($list_only, "id", "url") ?>">
    <hgroup class="flex flex-wrap flex-between gap-4">
        <input type="number" name="episode" id="episode" class="mini" placeholder="<?= language("episode") ?>" value="<?= getListValueGetTmp($list_only, "id", "episode") ?>" min="0" required>
        <input type="number" name="episodes" id="episodes" class="mini" placeholder="<?= language("episodes") ?>" value="<?= getListValueGetTmp($list_only, "id", "episodes") ?>" min="0">
        <input type="number" name="season" id="season" class="mini" placeholder="<?= language("season") ?>" value="<?= getListValueGetTmp($list_only, "id", "season") ?>" min="0">
        <select name="state" id="state" required>
            <?php foreach ([
                "" => "state",
                "watch" => "watch",
                "waiting" => "waiting",
                "finalized" => "finalized"
                ] as $key => $value): ?>
                <option value="<?= $key ?>" <?= $key == "" ? 'class="option_title"' : '' ?> <?= getListValueGetTmp($list_only, "id", "state") == $key ? "selected" : "" ?>><?= language($value) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="type" id="type" required>
            <?php foreach ([
                "" => "type",
                "anime" => "anime",
                "movie" => "movie",
                "series" => "series",
                "ova" => "ova",
                "other" => "other"
                ] as $key => $value): ?>
                <option value="<?= $key ?>" <?= $key == "" ? 'class="option_title"' : '' ?> <?= getListValueGetTmp($list_only, "id", "type") == $key ? "selected" : "" ?>><?= language($value) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="stars" id="stars">
            <?php foreach ([
                "" => "stars",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4",
                "5" => "5"
                ] as $key => $value): ?>
                <option value="<?= $key ?>" <?= $key == "" ? 'class="option_title"' : '' ?> <?= getListValueGetTmp($list_only, "id", "stars") == $key ? "selected" : "" ?>><?= language($value) ?></option>
            <?php endforeach; ?>
        </select>
    </hgroup>
    <?php if($user ?? false): ?>
        <input type="checkbox" name="to_user" id="to_user" checked required hidden>
    <?php endif; ?>
    <button type="submit" name="add" id="add"><?= language(getListValueGetTmp($list_only, "id", "title") ? "edit" : "add") ?></button>
</form>