<?php
/**
array(
    array('name' => 'style', 'type' => 'text', 'description' => 'CSS style'),
    array('name' => 'type',  'type' => 'select', 'options' => array('inc', 'noinc'), 'description' => 'Could be "inc" to be incremental list or "noinc" to be normal. Default value is "noinc".')
);
**/
if(isset($p['style'])){
    $p['style'] = ' style="' . $p['style'] . '"';
} else {
    $p['style'] = '';
}

if( isset($p['type']) && $p['type'] == 'inc' ) {
    $class = ' class="incremental"';
} else {
    $class = '';
}
$g['slide'] .= "<ul{$class}{$p['style']}><li>" . str_replace("\n", '</li><li>', $body) . '</li></ul>';
