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

function filePath(string $path): string {
    return __DIR__ . "/../$path";
}

function pathFiles(string $string): string {
    $routes = [
        "script" => "inc/script.php",
        "add" => "inc/add.php",
        "delete" => "inc/delete.php",
        "core" => "database/core.json",
        "config" => "database/config.json",
        "list" => "database/list.json",
        "language" => "database/language.json",
        "counter" => "database/counter.json",
    ];

    return $routes[$string] ?? $string;
}

function secureString(string $string): string {
    return trim(htmlspecialchars($string ?? ""));
}

function secureStringFile(string $string): string {
    $string = strtolower(secureString($string));
    $string = str_replace(["-", "_"], " ", $string);
    $string = str_replace(" ", "-", removeSymbols($string));
    $string = replaceAccents($string);
    $string = replaceEnye($string);
    return $string;
}

function array_post(string $title, int|string $episode, int|string $episodes, int|string $season, string $state, string $type): array {
    return [
        "title" => $title,
        "episode" => $episode,
        "episodes" => $episodes,
        "season" => $season,
        "state" => $state,
        "type" => $type
    ];
}

function removeSymbols(string $string): string {
    return str_replace([
        '☺', '☻', '♥', '♦', '♣', '♠', '•', '◘', '○', '◙',
        '♂', '♀', '♪', '♫', '☼', '►', '◄', '↕', '‼', '¶',
        '§', '▬', '↨', '↑', '↓', '→', '←', '∟', '↔', '▲',
        '▼', '!', '"', '#', '$', '%', '&', '(', ')', '*',
        '+', ',', ':', ';', '<', '=', '>', '?', '@', '[',
        ']', '^', '`', '{', '|', '}', '~', '⌂', 'ª', 'º',
        '¿', '®', '¬', '½', '¼', '¡', '«', '»', '░', '▒',
        '▓', '│', '┤', '©', '╣', '║', '╗', '╝', '¢', '¥',
        '┐', '└', '‼', '┴', '┬', '├', '─', '┼', '╚', '╔',
        '╩', '╦', '╠', '═', '╬', '¤', 'ð', '┘', '┌', '█',
        '▄', '¦', '▀', '¯', '´', '±', '³', '²', '¶', '§',
        '÷', '¸', '°', '¨', '·', '¹', '³', '²', '■', "'",
        '“', '”', '-', '/', '.', '_'
    ], '', $string);
};

function replaceAccents($string): string {
    foreach ([
        "á" => "a", "Á" => "A",
        "é" => "e", "É" => "E",
        "í" => "i", "Í" => "I",
        "ó" => "o", "Ó" => "O",
        "ú" => "u", "Ú" => "U",
    ] as $key => $value) {
        $string = str_replace($key, $value, $string);
    }
    return $string;
}

function replaceEnye($string): string {
    $string = str_replace('ñ', 'n', $string);
    $string = str_replace('Ñ', 'N', $string);
    return $string;
}

function getListValue(array $list, string $id, string $string): string {
    return $list[$id][$string] ?? "";
}

function getValueTmp(string $string): string {
    return !empty($_SESSION["tmp_form"][$string]) ? $_SESSION["tmp_form"][$string] : "";
}

function getListValueGet(array $list, string $id, string $string): string {
    return getListValue($list, $_GET[$id] ?? "", $string);
}

function getListValueGetTmp(array $list, string $id, string $string): string {
    return getListValueGet($list, $id, $string) ?? getValueTmp($string);
}

function language(string $string): string {
    static $lang = null;
    if ($lang === null) {
        $lang = read(pathFiles("language"));
    }
    return $lang[$string][$_SESSION["language"] ?? (config("language") ?? "en")] ?? $string;
}

function read(string $path): array {
    return file_exists(filePath($path)) ? json_decode(file_get_contents(filePath($path)), true) : [];
}

function write(string $path, array $data): bool {
    return file_put_contents(filePath($path), json_encode($data)) !== false;
}

function message(string $type, string $content): void {
    $_SESSION["message"] = ["type" => $type, "content" => $content];
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function core(string $key): array|string {
    return read(pathFiles("core"))[$key] ?? [];
}

function config(string $key): array|string {
    return read(pathFiles("config"))[$key] ?? [];
}

function changeLanguage(string $language): void {
    if (!empty($language)){
        $language = secureString($language);
        $in_list = in_array($language, core("languages"));
        
        if (!$in_list){
            message("error", language("no_access"));
            redirect("./");
        }

        $_SESSION["language"] = $language;
        redirect("./");
    }
}

function changeTheme(string $theme): void {
    if (!empty($theme)){
        $_SESSION["theme"] = secureString($theme);
        redirect("./");
    }
}

function counter(string $slug): void {
    $counterPath = pathFiles("counter");
    $read = read($counterPath);
    $read[$slug] = isset($read[$slug]) ? $read[$slug] + 1 : 1;
    $read["counter"] = isset($read["counter"]) ? $read["counter"] + 1 : 1;
    write($counterPath, $read);
}