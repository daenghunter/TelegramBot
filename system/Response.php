<?php

declare(strict_types=1);

namespace system;

class Response
{
    public function withStatusCode(int $status = 200)
    {
        http_response_code($status); // status code default 200
        return $this;
    }

    public function withHeader(string $key = '', string $value = '')
    {
        header($key . ': ' . $value); // headers
        return $this;
    }

    public function HTML(string $html = ' ')
    {
        return $html; // return html
    }

    public function JSON(array $data = [])
    {
        return json_encode($data); // return json
    }
}
