<?php

class model {
    public function newUser(string $user, string $name, string $email, string $pass): array {
        $read = read(pathFiles("users"));
        $time = date_year_month_day_minute_second();
        $user = strtolower($user);
        $email = strtolower($email);

        if(isset($read[$user])){
            return ["result" => false, "message" => "El usuario ya existe"];
        }

        $read[$user] = [
            "user" => $user,
            "name" => $name,
            "email" => $email,
            "pass" => hashPassword($pass),
            "state" => "public",
            "rol" => "user",
            "recovery_code" => generatePin(),
            "date_registered" => $time
        ];

        $result = write(pathFiles("users"), $read);

        $message = $result ? "El usuario se creo exitosamente" : "Fallo al registrar el usuario";
        return ["result" => $result, "message" => $message];
    }

    public function updateUser(string $user_origin, string $user, string $name, string $email, string $pass): array {
        $read = read(pathFiles("users"));
        $update = $read;
        $time = date_year_month_day_minute_second();
        $user_origin = strtolower($user_origin);
        $user = strtolower($user);
        $email = strtolower($email);

        if(empty($read)){
            return ["result" => false, "message" => "No existe ningún usuario"];
        }

        if(!isset($read[$user_origin])){
            return ["result" => false, "message" => "El usuario a modificar no existe"];
        }

        if(isset($read[$user]) && $user_origin != $user){
            return ["result" => false, "message" => "El usuario ya existe"];
        }

        $changes = [
            "user" => $read[$user_origin]["user"] == $user,
            "name" => $read[$user_origin]["name"] == $name,
            "email" => $read[$user_origin]["email"] == $email,
            "pass" => verifyPassword($pass, $read[$user_origin]["pass"]),
        ];

        $updates = [];
        foreach ($changes as $key => $value) {
            if(!$value){
                $updates[$key] = match ($key) {
                    "user" => $user,
                    "name" => $name,
                    "email" => $email,
                    "pass" => hashPassword($pass),
                };
            }
        }

        $changes_history = [];
        foreach ($changes as $key => $value) {
            if(!$value){
                $changes_history[$key] = $read[$user_origin][$key];
            }
        };

        if(empty($updates)){
            return ["result" => false, "message" => "No se actualizo ningún dato"];
        }

        $update[$user] = array_merge($read[$user_origin], $updates);
        $update[$user] = array_merge($update[$user], ["date_updated" => $time]);
        $update[$user]["history"] = array_merge($update[$user]["history"] ?? [], [$time => $changes_history]);

        if ($user_origin != $user){
            unset($update[$user_origin]);
        }

        $result = write(pathFiles("users"), $update);

        $message = $result ? "El usuario se modifico exitosamente" : "Fallo al modificar el usuario";
        return ["result" => $result, "message" => $message];
    }

    public function newUserRecoveryCode(string $user): array {
        $read = read(pathFiles("users"));
        $update = $read;
        $time = date_year_month_day_minute_second();
        $user = strtolower($user);

        if(empty($read)){
            return ["result" => false, "message" => "No existe ningún usuario"];
        }

        if(!isset($read[$user])){
            return ["result" => false, "message" => "El usuario a modificar no existe"];
        }

        $update[$user]["recovery_code"] = generatePin();
        $update[$user]["date_updated"] = $time;
        $update[$user]["history"][$time] = ["recovery_code" => $read[$user]["recovery_code"]];

        $result = write(pathFiles("users"), $update);

        $message = $result ? "Se cambio el pin exitosamente" : "Fallo al cambiar el pin";
        return ["result" => $result, "message" => $message];
    }

    public function login(string $user, string $pass): array {
        $read = read(pathFiles("users"));
        $update = $read;
        $time = date_year_month_day_minute_second();
        $user = strtolower($user);

        if(empty($read)){
            return ["result" => false, "message" => "No existe ningún usuario"];
        }

        if(!isset($read[$user])){
            return ["result" => false, "message" => "El usuario no existe"];
        }
        
        if(!verifyPassword($pass, $read[$user]["pass"])){
            return ["result" => false, "message" => "El usuario o la contraseña es incorrecta"];
        }

        $update[$user]["date_login"] = $time;
        $update[$user]["history"][$time] = "login";
        
        $result = write(pathFiles("users"), $update);
        
        if($result) {
            $_SESSION["user"] = $user;
            $_SESSION["token"] = generateToken();
        }

        $message = $result ? "Inicio sesión exitosamente" : "Fallo al iniciar sesión";
        return ["result" => $result, "message" => $message];
    }

    public function auth(): bool {
        return !empty($_SESSION["user"]) && !empty($_SESSION["token"]);
    }

    public function logout(): void {
        unset($_SESSION["user"]);
        unset($_SESSION["token"]);
    }

    public function allUser(): array {
        return read(pathFiles("users"));
    }
}