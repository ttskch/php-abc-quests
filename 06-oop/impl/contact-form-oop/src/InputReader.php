<?php

class InputReader
{
    public function read($input)
    {
        return array(
            'type' => isset($input['type']) ? $input['type'] : '',
            'name' => isset($input['name']) ? $input['name'] : '',
            'email' => isset($input['email']) ? $input['email'] : '',
            'tel' => isset($input['tel']) ? $input['tel'] : '',
            'message' => isset($input['message']) ? $input['message'] : '',
        );
    }
}
