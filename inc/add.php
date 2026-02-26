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

if (isset($_POST["add"]) || !empty($_POST["add"])){
    $title = secureString($_POST["title"] ?? "");
    $episode = secureString($_POST["episode"] ?? "");
    $episodes = secureString($_POST["episodes"] ?? "");
    $season = secureString($_POST["season"] ?? "");
    $state = secureString($_POST["state"] ?? "");
    $type = secureString($_POST["type"] ?? "");

    if (empty($title) || empty($episode) || empty($state) || empty($type)){
        message("error", language("fill_required"));
        $_SESSION["tmp_form"] = array_post($title, $episode, $episodes, $season, $state, $type);
        redirect("./");
    }

    $id = secureStringFile($_POST["title"] ?? "");
    $search = isset($list[$id]);
    $list[$id] = array_post($title, $episode, $episodes, $season, $state, $type);
    $confirm = write(pathFiles("list"), $list);

    message($confirm ? "success" : "error", $confirm ? language($search ? "updated" : "added") : language("fail"));
    redirect("./");
}