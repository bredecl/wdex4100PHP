<?php
/**
 *
 * This library allows you to generate the configuration.yaml file for Home Assistant platform [https://www.home-assistant.io/]
 * Refer to the Readme.md file for more info
 *
 * @author: Brede Basualdo Serraino <hola@brede.cl>
 *
 */

$dataIPTemp = shell_exec("/usr/local/sbin/getNetworkConfig.sh");
$dataIP = [];

$lines = preg_split("/\n|\r/", $dataIPTemp);
foreach ($lines as $line) {
    if (!empty($line)) {
        $elements = preg_split('/\s+/', $line);
        $dataIP[trim($elements[0])] = trim($elements[1]);
    }
}

if (isset($dataIP["address"])) {
    $data = json_decode(shell_exec("php -f info.php true"), true);

    $return = "sensor:\n";
    $return .= "  - platform: rest\n";
    $return .= "    resource: 'http://" . $dataIP["address"] . "/info.php'\n";
    $return .= "    name: Monitor NAS\n";
    $return .= "    method: GET\n";
    $return .= "    scan_interval: 30\n";
    $return .= "    timeout: 15\n";
    $return .= "    json_attributes:\n";
    foreach ($data as $node => $detail) {
        $return .= "      - " . $node . "\n";
    }

    $return .= "    value_template: \"OK\"\n";
    $return .= "  - platform: template\n";
    $return .= "    sensors:\n";
    foreach ($data as $node => $detail) {
        foreach ($detail as $key => $value) {
            $return .= "      nas_" . strtolower($node) . "_" . strtolower($key) . ":\n";
            $return .= "        value_template: '{{ states.sensor.monitor_nas.attributes[\"" . $node . "\"][\"" . $key . "\"] }}'\n";
            if ($node == "temperatures") {
                $return .= "        device_class: temperature\n";
                $return .= "        unit_of_measurement: '°C'\n";
                $return .= "        friendly_name: 'NAS " . dictionary($key) . " Temperature'\n";
            } else if ($node == "smartStatus") {

            } else if ($node == "newFirmwareAvailable") {
            } else if ($node == "diskUsage") {
                if (strpos($key, 'percent') !== false) {
                    $return .= "        device_class: battery\n";
                    $return .= "        unit_of_measurement: '%'\n";
                }
                elseif (endsWith($key, 'gb') !== false) {
                    $return .= "        device_class: data_size\n";
                    $return .= "        unit_of_measurement: 'GiB'\n";
                }
                elseif (endsWith($key, 'tb') !== false) {
                    $return .= "        device_class: data_size\n";
                    $return .= "        unit_of_measurement: 'TiB'\n";
                }
                $return .= "        friendly_name: 'NAS " . dictionary($key) . "'\n";
            } else if ($node == "memory") {
                $return .= "        device_class: data_size\n";
                $return .= "        unit_of_measurement: 'KiB'\n";

                $return .= "        friendly_name: 'NAS " . dictionary($key) . "'\n";
            }
        }
    }

    header('Content-Type:text/plain');
    echo $return;
}

die();

function p($a)
{
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}
function dictionary($text){
    if ($text == "created_at") {
        return "Creado el";
    } elseif ($text == "updated_at") {
        return "Actualizado el";
    } elseif ($text == "deleted_at") {
        return "Borrado el";
    }
    $palabras = [
        "hd"=>"HD",
        "usage"=>"Usage",
        "disk"=>"Disk",
        "used"=>"Used",
        "gb"=>"GB",
        "tb"=>"TB",
        "current"=>"Current",
        "mem"=>"Memory",
    ];
    $text = preg_replace("/_/"," ", $text);
    
    foreach ($palabras as $palabra=>$reemplazo) {
        $text = str_replace($palabra, " " . ($reemplazo) . " ", $text);
    }
    return trim(preg_replace("/\s{2,}/", " ", $text));
}
function endsWith($haystack, $needle)
{
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}