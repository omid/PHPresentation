<?php

class generate
{
    function text($var_array)
    {
        return "<input type=\"text\" value=\"{$var_array['value']}\" />";
    }
    
    function file($var_array, $path)
    {
        return "<input type=\"file\" /><img src=\"{$path}images/{$var_array['value']}\"/>";
    }
    
    function select($var_array)
    {
        $ret = "<select>";
        for($i=0; isset($var_array['options'][$i]); $i++){
            $ret .= "<option id=\"{$i}\">{$var_array['options'][$i]}</option>";
        }
        $ret .= "</select>";
        
        return $ret;
    }
}
