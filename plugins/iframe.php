<?php
/**
array(
    array('name' => 'style', 'type' => 'text', 'description' => 'CSS style'),
    array('name' => 'file',  'type' => 'file', 'description' => 'File of iframe')
);
**/
if(isset($p['style'])){
    $p['style'] = ' style="' . $p['style'] . '"';
} else {
    $p['style'] = '';
}

$g['slide'] .= '<div class="iframe"' . $p['style'] .'><iframe onload="oniframeload(this)" scrolling="no" ' . $p['style'] . ' src="' . "{$g['weburl']}/{$g['base_path']}/codes/{$p['file']}" . '"></iframe></div>';
