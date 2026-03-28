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

namespace Actions;

class Actions {
    public function addListAniPelis($list, $model): void {
        if (isset($_POST["add"]) || !empty($_POST["add"])){
            $title = secureString($_POST["title"] ?? "");
            $url = htmlspecialchars($_POST["url"] ?? "", ENT_QUOTES, 'UTF-8');
            $episode = secureString($_POST["episode"] ?? "");
            $episodes = secureString($_POST["episodes"] ?? "");
            $season = secureString($_POST["season"] ?? "");
            $state = secureString($_POST["state"] ?? "");
            $type = secureString($_POST["type"] ?? "");
            $stars = secureString($_POST["stars"] ?? "");
            $to_user = isset($_POST["to_user"]) && !empty($_POST["to_user"]) && $model->auth();

            if (empty($title) || empty($episode) || empty($state) || empty($type)){
                message("error", language("fill_required"));
                $_SESSION["tmp_form"] = array_post(title: $title, url: $url, episode: $episode, episodes: $episodes, season: $season, state: $state, type: $type, stars: $stars);
                redirect(route($to_user ? "p/" . $_SESSION["user"] : ""));
            }

            if (!empty($url) && filter_var($_POST["url"] ?? "", FILTER_VALIDATE_URL) === false) {
                message("error", language("error"));
                redirect(route($to_user ? "p/" . $_SESSION["user"] : ""));
            }

            $id = secureStringFile($_POST["title"] ?? "");
            $search = $to_user ? isset($list["user"][$_SESSION["user"]][$id]) : isset($list["public"][$id]);
            
            if($to_user){
                $list["user"][$_SESSION["user"]][$id] = array_post(title: $title, url: $url, episode: $episode, episodes: $episodes, season: $season, state: $state, type: $type, stars: $stars);
            } else {
                $list["public"][$id] = array_post(title: $title, url: $url, episode: $episode, episodes: $episodes, season: $season, state: $state, type: $type, stars: $stars);
            }

            $confirm = write(pathFiles("list"), $list);

            message($confirm ? "success" : "error", $confirm ? language($search ? "updated" : "added") : language("fail"));
            redirect(route($to_user ? "p/" . $_SESSION["user"] : ""));
        }
    }

    public function deleteListAniPelis($list, $model): void {
        if (isset($_GET["action"]) && $_GET["action"] == "delete" && !empty($list) && isset($_GET["id"])){
            $id = secureString($_GET["id"] ?? "");
            $to_user = isset($_GET["to_user"]) && !empty($_GET["to_user"]) && $model->auth();

            $search = $to_user ? isset($list["user"][$_SESSION["user"]][$id]) : isset($list["public"][$id]);
            if($search){
                if($to_user){
                    unset($list["user"][$_SESSION["user"]][$id]);
                } else {
                    unset($list["public"][$id]);
                }

                $confirm = write(pathFiles("list"), $list);
                message($confirm ? "success" : "error", language($confirm ? "deleted" : "fail"));
                redirect(route($to_user ? "p/" . $_SESSION["user"] : ""));
            }
        }
    }

    public function addBirthday($list): void {
        if (isset($_POST["add"]) || !empty($_POST["add"])){
            $name = secureString($_POST["name"] ?? "");
            $date = secureString($_POST["date"] ?? "");

            if (empty($name) || empty($date)){
                message("error", language("fill_required"));
                $_SESSION["tmp_form"] = ["name" => $name, "date" => $date];
                redirect(route("birthday"));
            }

            $id = secureStringFile($_POST["name"] ?? "");
            $search = isset($list[$_SESSION["user"]][$id]);
            
            $list[$_SESSION["user"]][$id] = ["name" => $name, "date" => $date];

            $confirm = write(pathFiles("birthday"), $list);

            message($confirm ? "success" : "error", $confirm ? language($search ? "updated" : "added") : language("fail"));
            redirect(route("birthday"));
        }
    }

    public function deleteBirthday($list): void {
        if (isset($_GET["action"]) && $_GET["action"] == "delete" && !empty($list) && isset($_GET["id"])){
            $id = secureString($_GET["id"] ?? "");

            $search = isset($list[$_SESSION["user"]][$id]);
            if($search){
                unset($list[$_SESSION["user"]][$id]);

                $confirm = write(pathFiles("birthday"), $list);
                message($confirm ? "success" : "error", language($confirm ? "deleted" : "fail"));
                redirect(route("birthday"));
            }
        }
    }

    public function addGoals($list): void {
        if (isset($_POST["add"]) || !empty($_POST["add"])){
            $goal = secureString($_POST["goal"] ?? "");
            $state = secureString($_POST["state"] ?? "");
            $time = secureString($_POST["time"] ?? "");
            $date = secureString($_POST["date"] ?? "");

            $dates = ["goal" => $goal, "state" => $state, "time" => $time, "date" => $date];

            if (empty($goal) || empty($state) || empty($time) || empty($date)){
                message("error", language("fill_required"));
                $_SESSION["tmp_form"] = $dates;
                redirect(route("goals"));
            }

            $id = secureStringFile($_POST["goal"] ?? "");
            $search = isset($list[$_SESSION["user"]][$id]);
            
            $list[$_SESSION["user"]][$id] = $dates;

            $confirm = write(pathFiles("goals"), $list);

            message($confirm ? "success" : "error", $confirm ? language($search ? "updated" : "added") : language("fail"));
            redirect(route("goals"));
        }
    }

    public function deleteGoals($list): void {
        if (isset($_GET["action"]) && $_GET["action"] == "delete" && !empty($list) && isset($_GET["id"])){
            $id = secureString($_GET["id"] ?? "");

            $search = isset($list[$_SESSION["user"]][$id]);
            if($search){
                unset($list[$_SESSION["user"]][$id]);

                $confirm = write(pathFiles("goals"), $list);
                message($confirm ? "success" : "error", language($confirm ? "deleted" : "fail"));
                redirect(route("goals"));
            }
        }
    }

    public function login($captcha, $model): void {
        if (isset($_POST["login"]) || !empty($_POST["login"])){
            $user = secureString($_POST["user"] ?? "");
            $pass = $_POST["password"] ?? "";
            $h_captcha_response = $_POST["h-captcha-response"];
            
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
    }

    public function register($captcha, $model): void {
        if (isset($_POST["register"]) || !empty($_POST["register"])){
            $user = secureString($_POST["user"] ?? "");
            $name = secureString($_POST["name"] ?? "");
            $email = secureString($_POST["email"] ?? "");
            $pass = $_POST["password"] ?? "";
            $pass_confirm = $_POST["confirm_password"] ?? "";
            $h_captcha_response = $_POST["h-captcha-response"];

            if (!$captcha->checkCaptcha($h_captcha_response)) {
                message("error", language("Captcha inválido"));
                $_SESSION["tmp_form"] = ["user" => $user];
                redirect("./register");
            }

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
    }
}