<?php
class ErrorController {
    //Answer with a 404 Response to a bad API Access.
    public function error($msg) {
        header("Content-Type: application/json");
        http_response_code(404);
        echo json_encode([
            "status"=> "error",
            "message"=> htmlentities($msg),
        ]);
    }
}