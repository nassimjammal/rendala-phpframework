<?php

namespace Rendala\Core\App;

use Rendala\Core\Exception as CoreException;

Class Exception extends CoreException 
{
    public static function display()
    {
        echo 'Wrong Application';
    }
}