<?php
/**
array(
    array('name' => 'style', 'type' => 'text', 'description' => 'CSS style')
);
**/

if(isset($p['style'])){
    $p['style'] = ' style="' . $p['style'] . '"';
} else {
    $p['style'] = '';
}

if($g['main']){
    $g['title'] = $body;
}else{
    $g['slide'] .= "<h1{$p['style']}>" . nl2br($body) . '</h1>';
}
