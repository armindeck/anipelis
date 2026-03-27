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
        "add" => "inc/actions/add.php",
        "delete" => "inc/actions/delete.php",
        "login" => "inc/actions/login.php",
        "register" => "inc/actions/register.php",
        "core" => "database/core.json",
        "config" => "database/config.json",
        "list" => "database/list.json",
        "birthday" => "database/birthday.json",
        "language" => "database/language.json",
        "counter" => "database/counter.json",
        "users" => "database/users.json",
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

function array_post(string $title, string $url, int|string $episode, int|string $episodes, int|string $season, string $state, string $type, int|string $stars): array {
    return [
        "title" => $title,
        "url" => $url,
        "episode" => $episode,
        "episodes" => $episodes,
        "season" => $season,
        "state" => $state,
        "type" => $type,
        "stars" => $stars
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
    return file_put_contents(filePath($path), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
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
    if (!empty($language) && strlen($language) > 5 && substr($language, 0, 5) == "lang."){
        $language = secureString(substr($language, 5, strlen($language)));
        $in_list = in_array($language, core("languages"));
        
        if (!$in_list){
            message("error", language("no_access"));
            redirect(route());
        }

        $_SESSION["language"] = $language;
        redirect(route());
    }
}

function changeTheme(string $theme): void {
    if (!empty($theme) && strlen($theme) > 6 && substr($theme, 0, 6) == "theme."){
        $_SESSION["theme"] = secureString(substr($theme, 6, strlen($theme)));
        redirect(route());
    }
}

function counter(string $slug): void {
    $counterPath = pathFiles("counter");
    $read = read($counterPath);
    $read[$slug] = isset($read[$slug]) ? $read[$slug] + 1 : 1;
    $read["counter"] = isset($read["counter"]) ? $read["counter"] + 1 : 1;
    write($counterPath, $read);
}

function zone(): void {
    date_default_timezone_set('America/Bogota');
}

function date_year_month_day(): string {
    zone();
    return date('Y-m-d');
}

function date_year_month_day_minute(){
    zone();
    return date('Y-m-d H:i');
}

function date_year_month_day_minute_second(){
    zone();
    return date('Y-m-d H:i:s');
}

function hashPassword(string $pass): string {
    return password_hash($pass, PASSWORD_DEFAULT);
}

function verifyPassword(string $pass, string $pass_origin): bool {
    return password_verify($pass, $pass_origin);
}

function generateToken(): string {
    return bin2hex(random_bytes(16));
}

function is_par_letter($numero){
    $letras = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "K", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    //$letras = ['A','E','I','O','U'];
    return ($numero % 2 == 0) ? $letras[rand(0, count($letras)-1)] : $numero;
}

function generatePin(array $cantidad = [4, 5, 7]): string {
    $numeros = '';
    foreach ($cantidad as $key => $valor) {
        $numeros .= $key >= 1 ? '-' : '';
        for($i=0; $i < $valor; $i++){
            $numeros .= is_par_letter(rand(0,9));
        }
    }

    return $numeros;
}


function view(string $view, ?array $data = [], ?bool $extract = true): void {
    $file = filePath("inc/views/$view-view.php");
    
    if (!file_exists($file)){
        die("El archivo $view no existe!");
    }
    
    unset($view);

    if(!empty($data) && $extract){
        extract($data, EXTR_SKIP);
    }

    require $file;
}

function actions(string $action, ?array $data = []): void {
    $file = filePath("inc/actions/$action.php");
    
    if (!file_exists($file)){
        die("El archivo $action no existe!");
    }

    if(!empty($data)){
        extract($data, EXTR_SKIP);
    }

    require $file;
}

function route(string $string = ""): string {
	$s = trim($string);
	
	// Si es una URL absoluta válida (http(s)://...) o protocolo relativo (//dominio), devolver tal cual
	if (filter_var($s, FILTER_VALIDATE_URL) || strpos($s, '//') === 0) {
		return $s;
	}

	return path_directory() . $string;
}


function path_directory(): string {
	$route = trim(get_slug(), "/");
	if($route === "") { return "./"; }

	// eliminar segmentos vacíos (por dobles slashes) y contar profundidad
	$segments = array_values(array_filter(explode("/", $route), function($s) { return $s !== ""; }));

	$count = count($segments);

	// si sólo hay un segmento (ej. "page") seguimos en ./, si hay más, subimos niveles
	if ($count <= 1) { return "./"; }

	return str_repeat("../", $count - 1);
}

function get_slug(): string { return secureString($_GET["slug"] ?? ""); }

function month(string $string): string {
    return match ($string) {
        "01" => "Enero",
        "02" => "Febrero",
        "03" => "Marzo",
        "04" => "Abril",
        "05" => "Mayo",
        "06" => "Junio",
        "07" => "Julio",
        "08" => "Agosto",
        "09" => "Septiembre",
        "10" => "Octubre",
        "11" => "Noviembre",
        "12" => "Diciembre",
        default => "Indefinido"
    };
}

function strDate(string $date): string {
    $date = explode("-", $date);
    $string = substr($date[2], 0, 1) == 0 ? substr($date[2], 1) : $date[2]; // Day
    $string .= " de ";
    $string .= strtolower(month($date[1])); // Month
    $string .= " del ";
    $string .= $date[0]; // Year

    return $string;
}