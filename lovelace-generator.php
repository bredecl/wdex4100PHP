<?php
/**
 *
 * This library allows you to generate the Lovelace widget file for Home Assistant platform [https://www.home-assistant.io/]
 * Refer to the Readme.md file for more info
 *
 * @author: Brede Basualdo Serraino <hola@brede.cl>
 * 
 */

 echo "WIP";
die();
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

/*

type: entities
entities:

  - entity: binary_sensor.nas_fan
    name: Fan
  - entity: sensor.nas_system_temperature
    name: System Temperature
  - entity: sensor.nas_disk_1_temperature
    name: Disk 1 Temperature
  - entity: sensor.nas_disk_2_temperature
    name: Disk 2 Temperature
  - entity: sensor.nas_disk_3_temperature
    name: Disk 3 Temperature
  - entity: sensor.nas_disk_4_temperature
    name: Disk 4 Temperature


*/

    $return = "type: entities\n";
    $return = "title: WD EX4100 NAS";
    $return = "show_header_toggle: false";
    $return = "state_color: true";
    $return = "entities:\n";
    foreach ($data as $node => $detail) {




        entity: sensor.ble_temperature_exterior
name: Exterior
type: custom:multiple-entity-row
show_state: false
entities:
  - entity: sensor.ble_voltage_exterior
    name: Voltaje
    format: precision1
  - entity: sensor.ble_humidity_exterior
    name: Humedad
    format: precision1
  - entity: sensor.ble_temperature_exterior
    name: Temperatura
    format: precision1



       /* foreach ($detail as $key => $value) {
            $return .= "  - entity: sensor." . strtolower($node) . "_" . strtolower($key) . ":\n";
            if ($node == "temperatures") {
                $return .= "    name: '" . dictionary($key) . " Temperature'\n";
            } else if ($node == "smartStatus") {
                $return .= "    name: 'Smart Status'\n";
            } else if ($node == "newFirmwareAvailable") {
                $return .= "    name: 'Firmware Available'\n";
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
                $return .= "        friendly_name: '" . dictionary($key) . "'\n";
            } else if ($node == "memory") {
                $return .= "        device_class: data_size\n";
                $return .= "        unit_of_measurement: 'KiB'\n";

                $return .= "        friendly_name: '" . dictionary($key) . "'\n";
            }
        }*/
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