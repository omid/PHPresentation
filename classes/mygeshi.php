<?php
require_once('geshi/geshi.php');

class mygeshi extends GeSHi
{
    function __construct($source = '', $language = '', $path = '')
    {
        parent::__construct($source = '', $language = '', $path = '');
    }
}
