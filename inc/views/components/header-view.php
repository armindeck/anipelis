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
<!-- anipelis core v<?= core("version") ?> (<?= core("state") ?>) (Copyright © 2026 Armin Deck – Licencia de Uso No Transferible) – https://github.com/armindeck/anipelis -->
<!DOCTYPE html>
<html lang="<?= config("language") ?? "en" ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= config("app_name") ?? core("name") ?></title>
    <meta name="description" content="Listado de animes, peliculas, series">
    <link rel="stylesheet" href="<?= route("style.css") ?>">
	<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
</head>
<body data-theme="<?= $_SESSION["theme"] ?? (!empty(config("theme")) ? config("theme") : "light") ?>">
    <div class="app">
        <header class="header">
            <div>
                <h2><?= config("app_name") ?? core("name") ?></h2>
            </div>
            <nav>
                <a href="<?= route() ?>">🏠 <?= language("home") ?></a>
                <a href="<?= route(!$auth ? "login" : "p/" . ($_SESSION["user"] ?? "")) ?>">👦 <?= language(!$auth ? "login" : "profile") ?></a>
                <?php if(!$auth): ?>
                    <a href="<?= route("register") ?>">👪 <?= language("register") ?></a>
                <?php endif ?>
                <label>📖
                    <select name="language" id="language" onchange="window.location.href='?language='+this.value">
                        <?php foreach (core("languages") as $key): ?>
                            <option value="<?= $key ?>" <?= ($_SESSION["language"] ?? config("language")) == $key ? "selected" : "" ?>><?= strtoupper($key) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>🌓
                    <select name="theme" id="theme" onchange="window.location.href='?theme='+this.value">
                        <?php foreach (core("themes") as $key): ?>
                            <option value="<?= $key ?>" <?= ($_SESSION["theme"] ?? config("theme")) == $key ? "selected" : "" ?>><?= strtoupper(substr($key, 0, 1)) . substr($key, 1, strlen($key)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <?php if($auth): ?>
                    <a href="<?= route("logout") ?>">🚪 <?= language("logout") ?></a>
                <?php endif ?>
            </nav>
        </header>
