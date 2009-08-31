<?php
/**
array(
    array('name' => 'style', 'type' => 'text', 'description' => 'CSS style'),
    array('name' => 'file',  'type' => 'file', 'description' => 'File of image')
);
**/
if(isset($p['style'])){
    $p['style'] = ' style="' . $p['style'] . '"';
} else {
    $p['style'] = '';
}

$ext = substr($p['file'], strrpos($p['file'], '.') + 1);

if ( $ext == 'svg'){
    @$g['slide'] .= '<div class="img"' . $p['style'] . '><embed src="' . "{$g['weburl']}/{$g['base_path']}/images/{$p['file']}" . '" type="image/svg+xml"/></div>';
} else {
    @$g['slide'] .= '<div class="img"' . $p['style'] . '><img src="' . "{$g['weburl']}/{$g['base_path']}/images/{$p['file']}" . '"/></div>';
}
