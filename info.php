<?php
/**
 *
 * This library allows you to collect and display data from WD EX4100 NAS as a JSON Response
 * The main target is to be used inside Home Assistant platform [https://www.home-assistant.io/]
 * Refer to the Readme.md file for more info
 *
 * @author: Brede Basualdo Serraino <hola@brede.cl>
 *
 */

$data = [];
$data["temperatures"] = parseLineBreak(shell_exec("fan_control -g 0"), ["temperature"]);
$data["smartStatus"] = preg_replace("/\n|\r/", "", shell_exec("/usr/local/sbin/getSmartStatus.sh"));
$data["newFirmwareAvailable"] = preg_replace("/\n|\r/", "", preg_replace("/\"/", "", (shell_exec("/usr/local/sbin/getNewFirmwareAvailable.sh"))));
$data["diskUsage"] = parseDiskFree(shell_exec("df"));
$data["memory"] = (parseLineBreak(shell_exec("cat /proc/meminfo")));

if (!isset($argv[1])) {
    header("Content-type: application/json; charset=utf-8");
}
echo json_encode($data);

function numberconvert($array){

    foreach($array as $i=>$v){
        $array[$i] = preg_replace("/\./",",",floatval($v));
    }
    return $array;
}
function parseDiskFree($content)
{

    $temp = array_map(function ($line) {
        $elements = preg_split('/\s+/', $line);
        return (array(
            'filesystem' => $elements[0],
            'inodes' => $elements[1],
            'used' => $elements[2]*1024,
            'usedGB' => bytes($elements[2]*1024, "GB"),
            'usedTB' => bytes($elements[2]*1024, "TB"),
            'free' => $elements[3]*1024,
            'freeGB' => bytes($elements[3]*1024, "GB"),
            'freeTB' => bytes($elements[3]*1024, "TB"),
            'use_percent' => preg_replace("/\%/","",$elements[4]),
            'mounted_on' => $elements[5],
        ));
    }, explode("\n", $content));
    $return = [];
    foreach ($temp as $t) {
        if (strpos($t["mounted_on"], "/mnt/HD/") !== false) {
            $name = preg_replace("|/mnt/HD/|", "", $t["mounted_on"]);
            foreach ($t as $param => $value) {
                $return[slugify($name . "_" . $param)] = $value;
            }
        }
    }
    return $return;
}
function parseLineBreak($content, $removeWords = [], $convertToGB = false)
{
    $return = [];
    $lines = preg_split("/\n|\r/", $content);
    foreach ($lines as $line) {
        $lineinfo = preg_split("/is|=|:/", $line);
        if (count($lineinfo) == 2) {
            $varName = $lineinfo[0];
            foreach ($removeWords as $rw) {
                $varName = trim(str_replace($rw, "", $varName));
            }
            $data = trim($lineinfo[1]);
            

                if (endsWith($data, "kB")) {
                    $data = (str_replace(" kB", "", $data)/1000000);
//                    $dataProc = bytes($data, "GB");
  //                  $return[slugify($varName) . "GB"] = $dataProc;
                }
                $return[slugify($varName)] = $data;


        }
    }
    return $return;
}

function endsWith($haystack, $needle)
{
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}
function slugify($text)
{
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
    $text = preg_replace('/-/', '_', $text);
    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

function bytes($bytes, $force_unit = null, $format = null, $si = true)
{
    global $unitsI, $unitsB;
    // Format string

    $format = ($format === null) ? '%01.2f' : (string) $format;

    // IEC prefixes (binary)
    if ($si == false or strpos($force_unit, 'i') !== false) {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];
        $mod = 1024;
    }
    // SI prefixes (decimal)
    else {
        $units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
        $mod = 1000;
    }

    // Determine unit to use
    if (($power = array_search((string) $force_unit, $units)) === false) {
        $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
    }

    return sprintf($format, round($bytes / pow($mod, $power), 2));
}
function p($a)
{
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}
