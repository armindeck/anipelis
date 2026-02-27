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

session_start();
require_once __DIR__ . "/inc/script.php"; // Scripts
changeLanguage($_GET["language"] ?? ""); // Change Language
changeTheme($_GET["theme"] ?? ""); // Change Theme
counter("index"); // Counter

// List
$list = read(pathFiles("list"));

// Add and delete process
require_once filePath(pathFiles("add"));
require_once filePath(pathFiles("delete"));
?>
<!-- anipelis core v<?= core("version") ?> (<?= core("state") ?>) (Copyright © 2026 Armin Deck – Licencia de Uso No Transferible) – https://github.com/armindeck/anipelis -->
<!DOCTYPE html>
<html lang="<?= config("language") ?? "en" ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= config("app_name") ?? core("name") ?></title>
    <meta name="description" content="Listado de animes, peliculas, series">
    <link rel="stylesheet" href="style.css">
</head>
<body data-theme="<?= $_SESSION["theme"] ?? config("theme") ?? "light" ?>">
    <div class="app">
        <header class="header">
            <div>
                <h2><?= config("app_name") ?? core("name") ?></h2>
            </div>
            <nav>
                <a href="./"><?= language("home") ?></a>
                <select name="language" id="language" onchange="window.location.href='?language='+this.value">
                    <?php foreach (core("languages") as $key): ?>
                        <option value="<?= $key ?>" <?= ($_SESSION["language"] ?? config("language")) == $key ? "selected" : "" ?>><?= strtoupper($key) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="theme" id="theme" onchange="window.location.href='?theme='+this.value">
                    <?php foreach (core("themes") as $key): ?>
                        <option value="<?= $key ?>" <?= ($_SESSION["theme"] ?? config("theme")) == $key ? "selected" : "" ?>><?= strtoupper(substr($key, 0, 1)) . substr($key, 1, strlen($key)) ?></option>
                    <?php endforeach; ?>
                </select>
            </nav>
        </header>
        <main class="main">
            <?php if (isset($_SESSION["message"])): ?>
                <input type="checkbox" id="hidden_message" class="hidden_message" hidden>
                <label for="hidden_message" class="message <?= $_SESSION["message"]["type"] ?? "" ?>">
                    <?= $_SESSION["message"]["content"] ?? "" ?>
                </label>
            <?php endif; unset($_SESSION["message"]); ?>
            <form method="post" class="form" id="formProcess">
                <h3><?= language(getListValueGetTmp($list, "id", "title") ? "edit" : "add") ?></h3>
                <input type="text" name="title" id="title" placeholder="<?= language("title") ?>" value="<?= getListValueGetTmp($list, "id", "title") ?>" required>
                <hgroup class="flex flex-wrap flex-between gap-4">
                    <input type="number" name="episode" id="episode" class="mini" placeholder="<?= language("episode") ?>" value="<?= getListValueGetTmp($list, "id", "episode") ?>" min="0" required>
                    <input type="number" name="episodes" id="episodes" class="mini" placeholder="<?= language("episodes") ?>" value="<?= getListValueGetTmp($list, "id", "episodes") ?>" min="0">
                    <input type="number" name="season" id="season" class="mini" placeholder="<?= language("season") ?>" value="<?= getListValueGetTmp($list, "id", "season") ?>" min="0">
                    <select name="state" id="state" required>
                        <?php foreach ([
                            "" => "state",
                            "watch" => "watch",
                            "waiting" => "waiting",
                            "finalized" => "finalized"
                            ] as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $key == "" ? 'class="option_title"' : '' ?> <?= getListValueGetTmp($list, "id", "state") == $key ? "selected" : "" ?>><?= language($value) ?></option>
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
                            <option value="<?= $key ?>" <?= $key == "" ? 'class="option_title"' : '' ?> <?= getListValueGetTmp($list, "id", "type") == $key ? "selected" : "" ?>><?= language($value) ?></option>
                        <?php endforeach; ?>
                    </select>
                </hgroup>
                <button type="submit" name="add" id="add"><?= language(getListValueGetTmp($list, "id", "title") ? "Edit" : "Add") ?></button>
            </form>
            <hr>
            <?php if (!empty($list)): ?>
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
                            <td><?= language("action") ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach (array_reverse($list) as $key => $value): ?>
                        <tr <?= $i % 2 == 0 ? "style='background:rgb(0,0,0,.1);'" : ""  ?>>
                            <td><?= $value["title"] ?? "" ?></td>
                            <td><?= $value["episode"] ?? "" ?></td>
                            <td><?= $value["episodes"] ?? "" ?></td>
                            <td><?= $value["season"] ?? "" ?></td>
                            <td><?= language($value["state"] ?? "") ?></td>
                            <td><?= language($value["type"] ?? "") ?></td>
                            <td class="flex flex-between gap-4">
                                <a href="?id=<?= $key ?? "" ?>&action=edit">📝</a>
                                <a href="?id=<?= $key ?? "" ?>&action=delete" onclick="return confirm('<?= language("confirm_delete"); ?>');">❌</a>
                            </td>
                        </tr>
                        <?php $i += 1; endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </main>
        <footer class="footer">
            <small style="float: left; opacity: 0.8;" title="<?= language("license") . " - " . language("counter") ?>">MIT - <?= read(pathFiles("counter"))["counter"] ?? 1 ?></small>
            &copy; 2026 <a href="https://github.com/armindeck" target="_blank">Armin Deck</a>.
            <small style="float: right; opacity: 0.8;" title="<?= language(core("state")) . " - " . core("updated") ?>">v<?= core("version") ?></small>
        </footer>
    </div>
</body>
</html>