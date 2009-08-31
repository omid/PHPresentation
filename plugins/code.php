<?php
/**
array(
    array('name' => 'style', 'type' => 'text',   'description' => 'CSS style'),
    array('name' => 'lang',  'type' => 'select', 'options' => array('php', 'sql', 'html'), 'description' => 'language type'),
    array('name' => 'lines', 'type' => 'text',   'description' => 'lines to be highlighted')
);
**/

if(isset($p['style'])){
    $p['style'] = ' style="' . $p['style'] . '"';
} else {
    $p['style'] = '';
}

if ( ! $p['lang'] ) {
    $p['lang'] = 'php-brief';
} else {
    $p['lang'] = $p['lang'];
}

@list($type, $body) = explode(':', $body, 2);

if ($type == 'file'){
    $geshi = new GeSHi(trim(file_get_contents("{$g['base_path']}/codes/{$body}")), $p['lang']);
    
    $geshi->set_header_content('Filename: ' . $body);
} elseif ($type == 'code') {
    $geshi = new GeSHi(trim($body), $p['lang']);
}

if(@count($p['lines'])){
    $geshi->highlight_lines_extra(explode(',', $p['lines']));
    $geshi->set_highlight_lines_extra_style('background: #330');
}

$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
$geshi->set_line_style('margin: 3px 0;');

$geshi->set_header_content_style('font-size: 0.8em; color: #333');
$geshi->set_header_type(GESHI_HEADER_DIV);


@$g['slide'] .= '<div class="code">' . $geshi->parse_code() . '</div>';
