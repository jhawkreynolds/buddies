<?php

// checks to make sure the string matches the required specifications:
// - string is within 3 and 16 characters, inclusive
// - string only contains: A-Z, a-z, 0-9, _ 
function is_invalid($input)
{
    $length = strlen($input);
    if($length > 2 && $length < 17){
        return !(preg_match('/^[A-Za-z0-9_]+$/', $input));
    }
    return false;
}

try{
    $action = $_POST['action'];

    switch($action) {
        case 'register':
            // input validation
            $username = $_POST['username'];
            $password = $_POST['password'];
            if(is_invalid($username) || is_invalid($password))
            {
                header('Location: ../register/index.html');
                exit();
            }
            // add user to registry
            $hash = password_hash($password, PASSWORD_DEFAULT);
            echo $username . "\n";
            echo $password . "\n";
            echo $hash . "\n";
            chdir('../../data/session')
            $filepath = $username . '.json'
            $json_data = [
                "username" => $username,
                "hash" => $hash
            ];
            $json_string = json_encode($json_data, JSON_PRETTY_PRINT);
            $file = fopen($filepath, 'w');
            fwrite($file, $json_string);
            fclose($file);
            break;
        case 'login':
            // input validation
            $username = $_POST['username'];
            $password = $_POST['password'];
            if(is_invalid($username) || is_invalid($password))
            {
                header('Location: ../login/index.html');
                exit();
            }
            // create session cookie
            break;
        case 'change-password':
            // input validation
            $username = $_POST['username'];
            $password = $_POST['password'];
            $new_password = $_POST['new_password'];
            if(is_invalid($username) || is_invalid($password) || is_invalid($new_password))
            {
                header('Location: ../change-password/index.html');
                exit();
            }
            // update password information
            break;
        case 'logout':
            // destroy session cookie
            break;
        default:
            break;
    }
}
catch(Exception $e){

}
?>