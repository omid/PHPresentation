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
                $body .= "<li><a href=\"{$g['weburl']}/{$_GET['dir']}/{$filename}\">{$filename}</a></li>";
            }
        }
        $body .= '</ul>';

        if(!$has_dir && !is_file("{$g['base_path']}/presentation.txt")){
            print_html('ERROR', 'There is not any presentation file here!');
        }

        print_html('Choose', $body);
    }
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
            
            unset($p);
            $head = explode('|', $head);
            $cmd = array_shift($head);
            foreach($head as $pa){
                $dummy_p = explode(':', $pa, 2);
                $p[$dummy_p[0]] = $dummy_p[1];
            }

            require(strtolower("plugins/{$cmd}.php"));
        }
		
        // if bg is not set, use default
        if(!isset($g['slidebg'])){
            $g['slidebg'] = $g['mainbg'];
        }

        if($g['main']){
            $g['main'] = false;
        }else{
            @$g['of'] .= gen_slide($g['slide'], $g['slidebg']);
            unset($g['slidebg']);
        }
    }
}

// generate functions
function gen_slide($content, $color)
{
    return '<div class="slide" style="background-color: #' . $color . '">' . $content . '</div>' . "\n\n";
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
	<link rel="stylesheet" href="' . $g['weburl'] . '/ui/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="' . $g['weburl'] . '/ui/slides.css" type="text/css" media="projection" id="slideProj" />
    <link rel="stylesheet" href="' . $g['weburl'] . '/ui/outline.css" type="text/css" media="screen" id="outlineStyle" />
    <link rel="stylesheet" href="' . $g['weburl'] . '/ui/print.css" type="text/css" media="print" id="slidePrint" />
    <link rel="stylesheet" href="' . $g['weburl'] . '/ui/opera.css" type="text/css" media="projection" id="operaFix" />
    <script src="' . $g['weburl'] . '/ui/slides.js" type="text/javascript"></script>
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
