<?php

/*
  The MIT License (MIT)

Copyright (c) 2015 Pierre Ruyter and Xavier Van de Woestyne

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
 */


/**
 *  PHUN is an extra small PHP Framework
 *  
 *  @author Xavier Van de Woestyne
 *  @author Pierre Ruyter
 *  @version 2.0
 *  @licence MIT License
 */


session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

class Phun {
    public static function start() {
        $current = Service::findCurrent();
        $current->render();
    }
}

require 'exceptions.php';
require 'parameters.php';
require 'url.php';
require 'services.php';


?>