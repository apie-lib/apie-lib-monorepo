<?php
class AdminerLoginPasswordLess
{
    public function __construct()
    {
        if ($_POST["auth"]) {
            $key = $_POST["auth"]["server"];
            $_POST["auth"]["driver"] = $key === 'mysql' ? 'server' : 'pgsql';
            $_POST['auth']['username'] = 'project';
            $_POST['auth']['password'] = 'project';
            $_POST['auth']['db'] = 'project';
        }
    }
    function login($login, $password) {
        if (!SERVER) {
            return false;
        }
    }

    function loginFormField($name, $heading, $value) {
        if ($name == 'driver' || $name === 'username' || $name === 'password' || $name == 'db') {
            return '';
        } elseif ($name == 'server') {
            return $heading . "<select name='auth[server]'>" . optionlist(['pgsql', 'mysql'], SERVER) . "</select>\n";
        }
    }
    public function credentials() {
        return [SERVER === 'pgsql' ? 'postgres:5432' : 'mysql:3306', 'project', 'project', 'project'];
    }
}
return new AdminerLoginPasswordLess();
