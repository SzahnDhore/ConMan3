<?php

namespace Szandor\ConMan\Logic;

use \Szandor\ConMan\Data as Data;
use \Szandor\ConMan\Logic as Logic;
use \Szandor\ConMan\View as View;

/**
 * Takes care of everything password related.
 */
class Password
{
    /**
     * Compares a user-submitted password to a password in the database.
     *
     * This function takes a user-submitted password, hashes it and compares the result to the already hashed password
     * stored in the database for that user. It needs both parameters or it returns false.
     *
     * @param string $user The username of the user we want to compare passwords with.
     * @param string $user_password The user-submitted password.
     *
     * @return bool If the password checks out, true. False if it doesn't.
     */
    public function checkPass($user, $user_password)
    {
        if ($user == '' || $user_password == '') {
            return false;
        } else {
            $check_cols = $this->user_name_col;
            if (!is_array($check_cols)) {
                $check_cols = str_split((string)$check_cols, 512);
            }

            foreach ($check_cols as $column) {
                $retrieved_password = $this->getPass($column, $user);
                if (isset($retrieved_password[0]['password'])) {
                    $stored_password = $retrieved_password[0]['password'];
                    $user_name = $retrieved_password[0]['username'];
                }
            }

            if (isset($stored_password)) {
                $checks_out = crypt($user_password, $stored_password) === $stored_password;
                if ($checks_out) {
                    // $_SESSION['user']['user_name'] = $user_name;
                    session_regenerate_id();
                    
                    return $user_name;
                } else { return false; }
            } else {
                return false;
            }

            // session_regenerate_id();
            // return $checks_out;
        }
    }

    /**
     * Hashes a string.
     *
     * Takes a password and hashes it using Blowfish and a random salt. I say 'password', but naturally you can pass any
     * string to it. If you feel the need to hash anything else. Which I can't see happening right now, but who knows?
     *
     * @param string $password The string to be hashed.
     *
     * @return string The hashed string.
     */
    public function hashPass($password)
    {
        $salt = '$2y$11$' . str_replace('+', '.', substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22));
        return crypt($password, $salt);
    }

    /**
     * Contains all columns in the database table that could correspond to the user name.
     */
    private $user_name_col = array(
        'username',
        'email',
    );

    /**
     * Returns the password for the specified user.
     *
     * @param string $user_col Which column in the table to check for user name.
     * @param string $user_name The user name to check for in specified column.
     *
     * @return string The stored password hash.
     */
    private function getPass($user_col, $user_name)
    {
        $event_title_request = array(
            'table' => 'users',
            'limit' => 1,
            'where' => array(
                'col' => $user_col,
                'values' => $user_name,
            ),
        );
        return Data\Database::read($event_title_request, false);
    }

}
