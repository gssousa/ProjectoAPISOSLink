<?php
class User {
    //Database Object for db access.
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    //Verifies individual fields for their existance and properties.
    function verifyFields($fields, $str, $limit) {
        $field = $fields[$str] ?? null;
        $isPasswordValid = true;
        if (($str == 'password' || $str == 'cpassword') && str_contains($field,' ')) {$isPasswordValid = false;}
        $isValid = $field && (!empty(trim($field))) && strlen($field) <= $limit;
        if ($isValid && $isPasswordValid) {
            return $field;
        }
    
        throw match (true) {
            !$field || empty(trim($field)) => new Exception("Include / Fill the $str field."),
            !$isPasswordValid => new Exception("Passwords can only have alphanumeric and symbolic characters."),
            strlen($field) > $limit => new Exception("Please respect the $str field character limit of $limit."),
            default => new Exception("Error in $str field.")
        };
    }

    //Assigns the received fields (from UserController) into the database and creates the User.
    public function signUp(array $data){
        $username = $this->verifyFields($data,'username',USER_STR_LIMIT);
        $password = $this->verifyFields($data,'password',PASS_STR_LIMIT);
        $cpassword = $this->verifyFields($data,'cpassword',PASS_STR_LIMIT);
        $email = filter_var($this->verifyFields($data,'email',EMAIL_STR_LIMIT),FILTER_SANITIZE_EMAIL);
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if(strcmp($password,$cpassword) == 0) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $database_connection = $this->db->DB_connect();
                $query = 'INSERT INTO user (username, userpassword, useremail) VALUES (:username, :userpassword, :useremail)';
                $preparedquery = $database_connection->prepare($query);
                if($preparedquery) {
                    $preparedquery->bindParam(":username", $username, PDO::PARAM_STR);
                    $preparedquery->bindParam(":userpassword", $hashed_password, PDO::PARAM_STR);
                    $preparedquery->bindParam(":useremail", $email, PDO::PARAM_STR);
                    $queryexecute = $preparedquery->execute();
                    return $queryexecute;
                } else {
                    throw new Exception('It was not possible to execute your request.');
                }
            } else {
                throw new Exception('Inserted passwords were different.');
            }
        } else {
            throw new Exception('Please write a valid email address.');
        }
    }

    //Assigns the received fields (from UserController) into the database and signs in the User.
    public function signIn(array $data) {
        $username = $this->verifyFields($data,'username',USER_STR_LIMIT);
        $password = $this->verifyFields($data,'password',PASS_STR_LIMIT);
        $database_connection = $this->db->DB_connect();
        if($database_connection != null) {
            $query = 'SELECT * FROM user WHERE user.username= :username';
            $preparedquery = $database_connection->prepare($query);
            if($preparedquery) {
                $preparedquery->bindParam(":username", $username, PDO::PARAM_STR);
                $db_response = $preparedquery->execute();
                if($db_response) {
                    $querydata = $preparedquery->fetch(PDO::FETCH_ASSOC);
                    if(!empty($querydata)) {
                        if(password_verify($password,$querydata['UserPassword'])) {
                        return $db_response;
                        } else {
                            throw new Exception('Invalid password.');
                        }
                    } else {
                        throw new Exception('Invalid username.');
                    }
                } else {
                    throw new Exception('It was not possible to execute your request.');
                }
            } else {
                throw new Exception('It was not possible to execute your request.');
            }
        }
    }
}