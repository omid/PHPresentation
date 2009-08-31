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

$g['slide'] .= "<div class=\"html\"{$p['style']}>" . $body . '</div>';
