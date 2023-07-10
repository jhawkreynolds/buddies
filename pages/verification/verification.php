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

$username = $_POST['username'];
$password = $_POST['password'];

switch($_POST['action']) {
    case 'register':
        break;
    case 'login':
        break;
    case 'change-password':
        break;
    default:
        break;
}
?>