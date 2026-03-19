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

view("components/header", ["auth" => $model->auth(), "view" => $view]);
?>
    <main class="main">
        <?php view("components/message"); ?>
        <div class="content">
            <h2 style="text-align: center;"><?= $model->allUser()[$user]["name"] ?></h2>
        </div>
        <hr>
        <?php if($is_user_user){
            $list_state = ["watch" => [], "waiting" => [], "finalized" => []];
            foreach ($list_only as $key => $value) {
                $list_state[$value["state"]][$key] = $value;
            }
            $list_convert = array_merge(array_reverse($list_state["watch"]), array_reverse($list_state["waiting"]), array_reverse($list_state["finalized"]));
            view("components/form-add", ["list_only" => $list_only, "user" => $user ?? false, "is_user_user" => $is_user_user ?? false]);
            echo "<hr>";
        }?>
        <?php view("components/list", ["list_only" => array_reverse($list_convert), "user" => $user ?? false, "is_user_user" => $is_user_user ?? false]); ?>
    </main>
<?php view("components/footer"); ?>