<?php
class create
{
    private $var;       // an array to store variables of plugins
    private $slides;    // a string to store slides
    
    function __construct()
    {
        $this->find_all_plugins();
        $this->slides = '';
    }
    
    function parse_presentation()
    { // open presentation.txt and parse it
        global $g;
        
        $this->path = 'presentation/example/first/';
        $if = file_get_contents("{$this->path}presentation.txt");
        
        // split slides and main page!
        $if = explode('---slide---', $if);
        $g['main'] = true;
        
        $slide_no = 0;
    
        foreach($if as $sl){
            
            $sl = preg_split('#\[-(.+?)\-]#', trim($sl), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            
            $this->slides .= '<fieldset>Slide ' . $slide_no++;
            $this->slides .= '<hr/>';
            while(count($sl)){
    
                $head = trim(array_shift($sl));
                $body = trim(array_shift($sl));
                
                $head = explode('|', $head);
                $cmd = strtolower(array_shift($head));
                $this->slides .= "<b>{$cmd}</b><br/>";
                foreach($head as $pa){
                    $dummy_p = explode(':', $pa, 2);
                    
                    // find this variable's array in that plugin!
                    $var_array = $this->find_var_by_name($cmd, $dummy_p[0]);
                    $var_array['value'] = $dummy_p[1];
                    $var_array['command'] = $cmd;
                    
                    $this->slides .= "{$var_array['name']}: " . $this->generate_var_type($var_array) . '<br/>';
                }
                if($body) $this->slides .= "content: <textarea>{$body}</textarea> ";
                $this->slides .= '<br/><br/>';
            }
            
            // if bg is not set, use default
            if(!isset($g['slidebg'])){
                $g['slidebg'] = $g['mainbg'];
            }
            $this->slides .= '</fieldset>';
            $this->slides .= '<hr/>';
        }
        
        echo $this->slides;
    }
    
    function find_all_plugins()
    {
        $dir = dir('plugins');
        
        while (false !== ($entry = $dir->read())) {
            // if I didn't get it's variables
            if(!isset($this->var[$cmd])){
                if($entry == '.' || $entry == '..') continue;
                
                $plugin_name = substr($entry, 0, strpos($entry, '.'));
                
                $plugin = file_get_contents(strtolower("plugins/{$entry}"));
                preg_match('#\/\*\*((.|\s)+?)\*\*\/#', $plugin, $plugin);
                eval('$this->var[$plugin_name] = ' . trim($plugin[1]));
            }
        }
        $dir->close();
    }
    
    function find_var_by_name($plugin, $name){
        foreach($this->var[$plugin] as $var){
            if($var['name'] == $name){
                return $var;
            }
        }
    }
    
    function generate_var_type($var_array)
    {
        $gen = new generate();
        switch($var_array['type']){
            case 'text':
                $ret = $gen->text($var_array);
                break;
            case 'file':
                if($var_array['command'] == 'image'){
                    $ret = $gen->image($var_array, $this->path);
                } elseif($var_array['command'] == 'iframe'){
                    $ret = $gen->iframe($var_array, $this->path);
                }
                break;
            case 'select':
                $ret = $gen->select($var_array);
                break;
            default:
                $ret = '';
        }
        unset($gen);
        return $ret;
    }

    function save()
    {
        
    }
}
?>