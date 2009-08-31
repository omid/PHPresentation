<?php
/**
array(
    array('name' => 'color', 'type' => 'text', 'description' => 'Hexadecimal code of color')
);
**/

if($g['main']){
    @$g['main'] = $p['color'];
    @$g['mainbg'] = $p['color'];
}else{
    @$g['slidebg'] = $p['color'];
}
