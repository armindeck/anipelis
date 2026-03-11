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

if (isset($_POST["register"]) || !empty($_POST["register"])){
    $user = secureString($_POST["user"] ?? "");
    $name = secureString($_POST["name"] ?? "");
    $email = secureString($_POST["email"] ?? "");
    $pass = $_POST["password"] ?? "";
    $pass_confirm = $_POST["confirm_password"] ?? "";

    if (empty($user) || empty($name) || empty($email) || empty($pass) || empty($pass_confirm)){
        message("error", language("fill_required"));
        $_SESSION["tmp_form"] = ["user" => $user, "name" => $name, "email" => $email];
        redirect("./register");
    }

    if ($pass != $pass_confirm){
        message("error", language("password_is_diferent"));
        $_SESSION["tmp_form"] = ["user" => $user, "name" => $name, "email" => $email];
        redirect("./register");
    }

    if (
        strlen($user) < 4 || strlen($user) > 25 ||
        strlen($name) < 4 || strlen($name) > 25 ||
        strlen($email) < 4 || strlen($email) > 150 ||
        strlen($pass) < 8 || strlen($pass) > 150 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        ){
        message("error", language("llene_los_campos_con_los_datos_solicitados"));
        $_SESSION["tmp_form"] = ["user" => $user, "name" => $name, "email" => $email];
        redirect("./register");
    }

    $confirm = $model->newUser($user, $name, $email, $pass);

    if($confirm["result"]){
        $model->login($user, $pass);
    }

    message($confirm["result"] ? "success" : "error", language($confirm["message"]));
    redirect("./" . (!$confirm["result"] ? "register" : ""));
}