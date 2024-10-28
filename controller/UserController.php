<?php
require_once USERRESOURCE_PATH;

class UserController {
    //Database Object for db access.
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    //Catches the JSON Request and sends it to User's SignUp function.
    public function signUp() {
        header("Content-Type: application/json");
        try {
            $json = json_decode(file_get_contents('php://input'), true) ?? [];
            if ($json != []) {
                $user = new User($this->db);
                $res = $user->signUp($json);
                if($res) {
                    echo json_encode([
                        "status"=> "success",
                        "message"=> "You successfully signed up your account."
                    ]);
                } else {
                    throw new Exception('It was not possible to register your account.');
                }
            } else {
                throw new Exception('Invalid JSON.');
            }
        } catch (Exception $e) {
            $this->db == null ?: $this->db->DB_disconnect();
            $msg = $e->getMessage();
            if($e instanceof PDOException) {
                $code = (filter_var($e->errorInfo[0],FILTER_VALIDATE_INT)) ? $e->errorInfo[0] : $e->errorInfo[1];
                switch ($code) {
                    case 2002:
                        $msg = 'Not able to connect API Endpoint to the Database.';
                        break;
                    case 23000:
                        $msg = "That username and / or email is already taken.";
                        break;
                    default:
                        break;
                }
            }
            echo json_encode([
                'status' => 'error',
                'message' => htmlspecialchars($msg)
            ]);
        }
    }

    //Catches the JSON Request and sends it to User's SignIn function.
    public function signIn() {
        header("Content-Type: application/json");
        try {
            $json = json_decode(file_get_contents('php://input'), true) ?? [];
            if ($json != []) {
                $user = new User($this->db);
                $res = $user->signIn($json);
                if($res) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'You successfully signed in into your account.'
                    ]);
                } else {
                    throw new Exception('Invalid password.');
                }
            } else {
                throw new Exception('Invalid JSON.');
            }
        } catch (Exception $e) {
            $this->db == null ?: $this->db->DB_disconnect();
            $msg = $e->getMessage();
            if($e instanceof PDOException) {
                $code = (filter_var($e->errorInfo[0],FILTER_VALIDATE_INT)) ? $e->errorInfo[0] : $e->errorInfo[1];
                switch ($code) {
                    case 2002:
                        $msg = 'Not able to connect API Endpoint to the Database.';
                        break;
                    default:
                        break;
                }
            }
            echo json_encode([
                'status' => 'error',
                'message' => htmlspecialchars($msg)
            ]);
        }
    }
}