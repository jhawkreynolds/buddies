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

// accepts $username, $password, $destination
// hashes password
// creates JSON to store
// puts JSON in file
// redirects user
// runs exit() in ALL modes 
function write_to_user($username, $password, $destination)
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
            
    $decoded_json = new stdClass();
    $decoded_json->username = $username;
    $decoded_json->hash = $hash;

    $encoded_json = json_encode($decoded_json);

    chdir('../../../buddies-data/session');
    file_put_contents("$username.json", $encoded_json);
    header('Location: ../success/registered.html');
    exit();
}

// accepts $username, $password, $mode
// tries to lookup user, on fail exits
// tries to validate user credentials, on fail exits
// on other conditions, user data is valid runs mode-specific code
// runs exit() in SOME modes
function check_password($username, $password)
{
    $encoded_json = file_get_contents("../../../buddies-data/session/$username.json");
    if($encoded_json == false)
    {
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
    // valid login beyond this point
    if($mode == 'login')
    { 
        // create session cookie
        setcookie('buddies-login', $encoded_json, 0, '', 'people.eecs.ku.edu', true, false);
        // login suggess. redirect the user
        header('Location: ../success/loggedin.html');
        exit();
    }
    elseif($mode == 'change-password')
    {
        destroy_session_cookie($mode);
        return;
    }   
}

function destroy_session_cookie($mode)
{
    // set expiry date for cookie to one hour ago
    setcookie('buddies-login', '', time() - 3600);
    if($mode == 'logout')
    {
        header('Location: ../success/loggedout.html');
        exit();
    }
    elseif($mode == 'change-password')
    {
        header('Location: ../success/passwordchanged.html');
        exit();
    }
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
            write_to_user($username, $password, 'register');
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
            // verify that given information matches stored information
            check_password($username, $password, 'login');
            break;
        case 'change-password':
            // input validation
            $username = $_POST['username'];
            $old_password = $_POST['old_password'];
            $new_password = $_POST['new_password'];
            if(is_invalid($username) || is_invalid($old_password) || is_invalid($new_password))
            {
                header('Location: ../change-password/index.html');
                exit();
            }
            // update password information
            check_password($username, $old_password, 'change-password');
            write_to_user($username, $new_password, 'change-password');
            break;
        case 'logout':
            // destroy session cookie
            destroy_session_cookie('logout');
            break;
        default:
            break;
    }
}
catch(Exception $e){

}

?>