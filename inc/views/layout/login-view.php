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

view("components/header", ["auth" => $model->auth()]);
?>
    <main class="main">
        <?php view("components/message"); ?>
        <form method="post" class="form" id="formProcess">
            <h3><?= language("login") ?> - <a href="<?= route("register") ?>"><?= language("register") ?></a></h3>
            <input type="text" name="user" id="user" placeholder="<?= language("user") ?>" minlength="4" maxlength="25" required>
            <input type="password" name="password" id="password" placeholder="<?= language("password") ?>" minlength="8" maxlength="150" required>
            <?php view("components/captcha") ?>
            <button type="submit" name="login" id="login"><?= language("login") ?></button>
        </form>
    </main>
<?php view("components/footer") ?>