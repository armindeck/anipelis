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

if (isset($_POST["login"]) || !empty($_POST["login"])){
    $user = secureString($_POST["user"] ?? "");
    $pass = $_POST["password"] ?? "";
    $h_captcha_response = $_POST["h-captcha-response"];
    
    require_once __DIR__ . "/../captcha.php";
    $captcha = new Captcha();
    if (!$captcha->checkCaptcha($h_captcha_response)) {
        message("error", language("Captcha inválido"));
        $_SESSION["tmp_form"] = ["user" => $user];
        redirect("./login");
    }

    if (empty($user) || empty($pass)){
        message("error", language("fill_required"));
        $_SESSION["tmp_form"] = ["user" => $user];
        redirect("./login");
    }

    $confirm = $model->login($user, $pass);

    message($confirm["result"] ? "success" : "error", language($confirm["message"]));
    redirect("./" . (!$confirm["result"] ? "login" : ""));
}