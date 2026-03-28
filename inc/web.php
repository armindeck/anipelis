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

$actions = new Actions\Actions;

// Profiles
if (count($view_explode) == 2 && $view_explode[0] == "p"){
    $view = "profile";
    $user = $view_explode[1] ?? "";
}

switch ($view) {
    case "home":
    case "profile":
        $list = read(pathFiles("list"));
        $actions->addListAniPelis($list, $model);
        $actions->deleteListAniPelis($list, $model);
        $data = [
            "model" => $model,
            "list" => $list,
            "list_only" => $view == "home" ? $list["public"] ?? [] : $list["user"][$user ?? ""] ?? [],
            "user" => $user ?? "",
            "is_user_user" => isset($user) && $model->auth() && $_SESSION["user"] == $user,
            "view" => $view
        ];

        $list_state = ["watch" => [], "waiting" => [], "finalized" => []];
        foreach ($data["list_only"] as $key => $value) {
            $list_state[$value["state"]][$key] = $value;
        }
        $data["list_order_by_state"] = array_merge(array_reverse($list_state["watch"]), array_reverse($list_state["waiting"]), array_reverse($list_state["finalized"]));
        
        if($view == "profile" && !isset($model->allUser()[$user])){
            $view = "error";
            $data = ["auth" => $model->auth(), "title" => "profile_not_found", "text" => "profile_not_found_searched"];
        }
        break;
        
    case "birthday":
        if(!$model->auth()){ redirect(route("login")); }
        
        $list = read(pathFiles("birthday"));
        $actions->addBirthday($list);
        $actions->deleteBirthday($list);
        $data = [
            "model" => $model,
            "list" => $list,
            "list_only" => array_reverse($list[$_SESSION["user"]] ?? []),
            "user" => $_SESSION["user"],
            "view" => $view
        ];
        break;

    case "goals":
        if(!$model->auth()){ redirect(route("login")); }
        
        $list = read(pathFiles("goals"));
        $actions->addGoals($list);
        $actions->deleteGoals($list);
        $data = [
            "model" => $model,
            "list" => $list,
            "list_only" => $list[$_SESSION["user"]] ?? [],
            "user" => $_SESSION["user"],
            "view" => $view
        ];

        $list_state = ["in_progress" => [], "on_pause" => [], "completed" => []];
        foreach ($data["list_only"] as $key => $value) {
            $list_state[$value["state"]][$key] = $value;
        }
        $data["list_order_by_state"] = array_merge(array_reverse($list_state["in_progress"]), array_reverse($list_state["on_pause"]), array_reverse($list_state["completed"]));
        break;

    case "login":
        if($model->auth()){ redirect(route()); }
        $data = ["model" => $model];
        $actions->login(new inc\Captcha, $model);
        break;

    case "register":
        if($model->auth()){ redirect(route()); }
        $data = ["model" => $model];
        $actions->register(new inc\Captcha, $model);
        break;

    case "logout":
        if($model->auth()){
            $model->logout();
        }
        redirect("./login");
        break;

    case "community":
        $data = ["auth" => $model->auth(), "users" => $model->allUser()];
        break;

    default:
        $data = ["auth" => $model->auth()];
        $view = "error";
        break;
}

counter($view);
view("layout/$view", $data ?? []);