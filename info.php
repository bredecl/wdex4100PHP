<?php
/**
 * 
 * This library allows you to collect and display data from WD EX4100 NAS as a JSON Response
 * The main target is to be used inside Home Assistant platform [https://www.home-assistant.io/] 
 * Refer to the Readme.md file for more info
 * 
 * @author: Brede Basualdo Serraino <hola@brede.cl>
 * 
 * Based on the work of EventuallyFixed in https://community.home-assistant.io/t/western-digital-my-cloud/40610/6
 * SLUGIFY Provided By https://gist.github.com/lucasmezencio/15d23207834a3eade40c5aeec7c1fc5e
 * 
 * 
 * 
 * By default the Temperature is diplayed in Celcius Degrees:
 * https://community.wd.com/t/change-temp-reading-to-fahrenheit/269929/2
 * 
 */



$data =[];
$data["temperatures"] = parseLineBreak(shell_exec("fan_control -g 0"),["temperature"]);

echo p($data);
//echo json_encode($data);


function parseLineBreak($content,$removeWords=[]){
    $return = [];
    $lines = preg_split("/\n|\r/",$content);
    foreach($lines as $line){
        $lineinfo = preg_split("/is|=/",$line);
        if(count($lineinfo)==2){
            $varName = $lineinfo[0];
            foreach($removeWords as $rw){
                $varName = trim(str_replace($rw,"",$varName));
            }
            $return[slugify($varName)] = trim($lineinfo[1]);
        }
    }  
    return $return;
}

function slugify($text){
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', trim($text));

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicated - symbols
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
}
function p($a){
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}