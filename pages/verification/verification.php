<?php

ini_set('display_errors', 1);

// checks to make sure the string matches the required specifications:
// - string is within 3 and 16 characters, inclusive
// - string only contains: A-Z, a-z, 0-9, _ 
function is_invalid($input)
{
    $length = strlen($input);
    if($length > 2 && $length < 17){
        return !(preg_match('/^[A-Za-z0-9_]+$/', $input));
    }
    return true;
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
            
            $decoded_json = new stdClass();
            $decoded_json->username = $username;
            $decoded_json->hash = $hash;

            $encoded_json = json_encode($decoded_json);

            chdir('../../../buddies-data/session');
            file_put_contents("$username.json", $encoded_json);
            header('Location: ../success/registered.html');
            exit();

        case 'login':
            // input validation
            $username = $_POST['username'];
            $password = $_POST['password'];
            if(is_invalid($username) || is_invalid($password))
            {
                header('Location: ../login/index.html');
                exit();
            }
            // verify that given information matches stored information
            $encoded_json = '';
            try{
                $encoded_json = file_get_contents("../../../buddies-data/session/$username.json");
            }
            catch(Exception $e){
                // lookup failure. redirect the user
                header('Location: ../error/usernotfound.html');
                exit();
            }
            $decoded_json = json_decode($encoded_json);
            if(!(password_verify($password, $decoded_json->hash)))
            {
                // given password could not produce hash. redirect the user
                header('Location: ../error/invalidcredentials.html');
                exit();
            } 
            // create session cookie
            setcookie('buddies-login', $encoded_json, 0, '', 'people.eecs.ku.edu', true, false);
            // login suggess. redirect the user
            header('Location: ../success/loggedin.html');
            exit();

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