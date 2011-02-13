<?php

namespace Fluid;

/*
Copyright (c) 2009 artBits snc

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/

// Setup the environment

// Switch the environment to choose wich configuration read
defined('APPLICATION_ENVIRONMENT') or define('APPLICATION_ENVIRONMENT', 'development');

// Define some useful constants
defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname(__FILE__)));   // The current directory

// Add libs dir to the include path
set_include_path(APPLICATION_PATH . '/app/' . PATH_SEPARATOR . get_include_path());
set_include_path(APPLICATION_PATH . '/libs/' . PATH_SEPARATOR . get_include_path());

// Where is my code ? 
require_once("src/bootstrap.php");