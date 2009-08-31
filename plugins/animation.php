<?php
/**
array(
    array('name' => 'file', 'type' => 'text', 'description' => 'Address of animated files, you must add "XXX" string to path to be variable')
);
**/

list($pre, $post) = explode('XXX', $p['file']);

$ext = substr($post, strrpos($post, '.') + 1);

$paths = glob("{$g['base_path']}/images/{$pre}*{$post}");
sort($paths);

$g['slide'] .= '<div id="anim">';
if ( $ext == 'svg'){
    foreach ($paths as $filename) {
        $class = ' class="incremental"';
        $g['slide'] .= '<embed src="' . "{$g['webdir']}/{$filename}" . '" type="image/svg+xml"' . $class . '/>';
    }
} else {
    foreach ($paths as $filename) {
		$class = ' class="incremental"';
        $g['slide'] .= '<img src="' . "{$g['webdir']}/{$filename}" . '"' . $class . '/>';
    }
}

$g['slide'] .= '</div>';
