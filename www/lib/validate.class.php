<?php

class Validate
{
    private $fail;
    private $email;
    private $password;
    private $passwordRepeat;

    public function __construct($params)
    {
        foreach ($params as $key => $value)
        {
            switch ($key)
            {
                case 'email':
                case 'password':
                case 'passwordRepeat':
                    $value = $this->fixString($value);
                    $this->$key = $value;
                    $this->fail.= $this->$key($value);
                    break;
                default:
                    $this->fail.= 'Неверные данные формы';
                    return $this->fail;
                    break;
            }
        }
    }

    public function email($email)
    {
        if ($email == "") return "Не введен адрес электронной почты<br>";
        else if (!((strpos($email, ".") > 0) &&
                (strpos($email, "@") > 0)) ||
            preg_match("/[^a-zA-Z0-9.@_-]/", $email)
        )
            return "Электронный адрес имеет неверный формат<br>";
        return "";
    }

    public function password($password)
    {
        if ($password == "") return "Не введен пароль<br>";
        else if (strlen($password) < 6)
            return "В пароле должно быть не менее 6 символов<br>";
        else if (!preg_match("/[a-z]/", $password) ||
            !preg_match("/[A-Z]/", $password) ||
            !preg_match("/[0-9]/", $password)
        )
            return "Пароль требует по 1-му символу из каждого набора a-z, A-Z и 0-9<br>";
        return "";
    }

    public function passwordRepeat($passwordRepeat)
    {
        if($this->password != ''){
            if ($passwordRepeat != $this->password) {
                return "Пароли не совпадают! Будьте внимательны!<br>";
            }
            else {
                return "";
            }
        }
    }

    public static function fixString($string)
    {
        $string = strip_tags($string);
        $string = htmlentities($string);
        $string = stripslashes($string);
        return $string;
    }


    public function getFail()
    {
        return $this->fail;
    }
}