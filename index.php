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
require_once __DIR__ . "/inc/model.php"; // Model

$slug = secureString($_GET["slug"] ?? "home");
$view = $slug;
$view_explode = explode("/", $view);
define("RAIZ", __DIR__ . "/");

changeLanguage($_GET["config"] ?? ""); // Change Language
changeTheme($_GET["config"] ?? ""); // Change Theme
$model = new model;
//$model->newUser("admin", "Admin", "admin@example.com", "1234567890");
//$model->login("admin", "1234567890");
//$model->logout();

date_default_timezone_set("America/Bogota");

require_once __DIR__ . '/inc/web.php';