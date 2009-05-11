<?php

function find_path()
{
    global $g;
    // go to find a presentation.txt :>
    if(!is_file("{$g['base_path']}/presentation.txt")){
        $paths = glob("./presentation/{$_GET['dir']}/*");
        
        $has_dir = false;
        $body = '<ul>';
        foreach ($paths as $filename) {
            if(is_dir($filename)){
                $has_dir = true;
                $filename = substr($filename, strrpos($filename, '/') + 1);
                $name = explode('_', $filename);
                $name = "{$name[0]} ({$name[1]})";
                $body .= "<li><a href=\"{$g['weburl']}/{$_GET['dir']}/{$filename}\">{$name}</a></li>";
            }
        }
        $body .= '</ul>';
        
        if(!$has_dir && !is_file("{$base_path}/presentation.txt")){
            print_html('ERROR', 'There is not any presentation file here!');
        }
        
        print_html('Choose', $body);
    }
}

function print_output()
{ // generating output
    global $g;
    
    $g['of'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    
    <html xmlns="http://www.w3.org/1999/xhtml">
    
    <head>
    <title>' . $g['title'] . '</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="version" content="S5 1.1" />
    <!-- configuration parameters -->
    <meta name="defaultView" content="slideshow" />
    <meta name="controlVis" content="hidden" />
    <!-- style sheet links -->
    <link rel="stylesheet" href="' . $g['weburl'] . '/ui/default/slides.css" type="text/css" media="projection" id="slideProj" />
    <link rel="stylesheet" href="' . $g['weburl'] . '/ui/default/outline.css" type="text/css" media="screen" id="outlineStyle" />
    <link rel="stylesheet" href="' . $g['weburl'] . '/ui/default/print.css" type="text/css" media="print" id="slidePrint" />
    <link rel="stylesheet" href="' . $g['weburl'] . '/ui/default/opera.css" type="text/css" media="projection" id="operaFix" />
    <link rel="stylesheet" href="' . $g['weburl'] . '/ui/style.css" type="text/css" media="all"/>
    <script src="' . $g['weburl'] . '/ui/default/slides.js" type="text/javascript"></script>
    </head>
    <body>
    <div class="layout">
    <div id="controls"><!-- DO NOT EDIT --></div>
    <div id="currentSlide"><!-- DO NOT EDIT --></div>
    <div id="header"></div>
    <div id="footer"></div>
    
    </div>
    <div class="presentation">' . $g['of'] . '</div>
    </body>
    </html>';
    
    echo $g['of'];
}

function parse_presentation()
{ // open presentation.txt and parse it
    global $g;
    
    $g['if'] = file_get_contents("{$g['base_path']}/presentation.txt");
    
    // split slides and main page!
    $g['if'] = explode('---slide---', $g['if']);
    $g['main'] = true;
    
    foreach($g['if'] as $sl){
    
        $sl = preg_split('#\[-(.+?)\-]#', trim($sl), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $g['slide'] = '';
        
        while(count($sl)){
    
            $head = trim(array_shift($sl));
            $body = trim(array_shift($sl));
    
            $head = explode('|', $head);
            
            switch($head[0]){
                case 'BGCOLOR':
                    if($g['main']){
                        $g['main'] = $head[1];
                        $g['mainbg'] = $head[1];
                    }else{
                        $g['slidebg'] = $head[1];
                    }
                    break;
    
                case 'TEXT':
                    $g['slide'] .= gen_text(nl2br($body));
                    break;
    
                case 'LIST':
                    $g['slide'] .= gen_list($body);
                    break;
    
                case 'CODE':
                    $g['slide'] .= gen_code($body, $head[1], $head[2]);
                    break;
    
                case 'TITLE':
                    if($g['main']){
                        $g['title'] = $body;
                    }else{
                        $g['slide'] .= gen_title(nl2br($body));
                    }
                    break;
    
                case 'IMAGE':
                    $g['slide'] .= gen_image($body);
                    break;
                
                case 'PATH':
                    $g['slide'] .= gen_path(nl2br($body));
                    break;
                
                case 'IFRAME':
                    $g['slide'] .= gen_iframe($head[1], $head[2]);
                    break;
                
                case 'BR':
                    $g['slide'] .= gen_br();
                    break;
                
                case 'HTML':
                    $g['slide'] .= gen_html($body);
                    break;
            }
        }
    
        // if bg is not set, use default
        if(!isset($g['slidebg'])){
            $g['slidebg'] = $g['mainbg'];
        }
    
        if($g['main']){
            $g['main'] = false;
        }else{
            $g['of'] .= gen_slide($g['slide'], $g['slidebg']);
            unset($g['slidebg']);
        }
    }
}

// generate functions
function gen_list($body)
{
    return '<ul><li>' . str_replace("\n", '</li><li>', $body) . '</li></ul>';
}

function gen_text($body)
{
    return '<p>' . $body . '</p>';
}

function gen_title($body)
{
    return '<h1>' . $body . '</h1>';
}

function gen_image($body)
{
    global $g;
    
    $ext = substr($body, strrpos($body, '.') + 1);
    
    if ( $ext == 'svg'){
        return '<div class="img"><embed src="' . "{$g['weburl']}/{$g['base_path']}/images/{$body}" . '" type="image/svg+xml"/></div>';
    } else {
        return '<div class="img"><img src="' . "{$g['weburl']}/{$g['base_path']}/images/{$body}" . '"/></div>';
    }
}

function gen_slide($content, $color)
{
    return '<div class="slide" style="background-color: #' . $color . '">' . $content . '</div>' . "\n\n";
}

function gen_code($body, $code='php-brief', $lines)
{
    require_once('geshi/geshi.php');
    global $g;
    
    $geshi = new GeSHi(trim(file_get_contents("{$g['base_path']}/codes/{$body}")), $code);
    
    if(count($lines)){
        $geshi->highlight_lines_extra(explode(',', $lines));
        $geshi->set_highlight_lines_extra_style('background: #220');
    }
    
    $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
    $geshi->set_line_style('margin: 3px 0;');
    
    $geshi->set_header_content_style('font-size: 0.8em; color: #333');
    $geshi->set_header_type(GESHI_HEADER_DIV);
    $geshi->set_header_content('Filename: ' . $body);
    
    return '<div class="code">' . $geshi->parse_code() . '</div>';
}

function gen_path($body)
{
    return '<div class="path">' . $body . '</div>';
}

function gen_iframe($body, $style)
{
    global $g;
    if($style){
        $style = 'style="' . $style . '"';
    }
    return '<div class="iframe"><iframe scrolling="no" ' . $style . ' src="' . "{$g['weburl']}/{$g['base_path']}/codes/{$body}" . '"></iframe></div>';
}

function gen_br()
{
    return '<br/>';
}

function gen_html($body)
{
    return $body;
}



// other functions
function print_html($title, $body)
{
    global $g;
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="' . $g['weburl'] . '/ui/list.css" type="text/css" media="all" />
<title>' . $title . '</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<h1>' . $title . '</h1>
<div class="body">' . $body . '<div></body>
</html>';
    exit();
}