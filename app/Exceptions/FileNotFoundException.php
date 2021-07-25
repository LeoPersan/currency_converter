<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class FileNotFoundException extends Exception
{
    public function __construct($file_path, $code = 0, Throwable $previous = null) {
        parent::__construct("File not extis: \"{$file_path}\".", $code, $previous);
    }
}