<?php

function banner()
{
    echo "\n \033[34m
 ____  _                 _      ____                  
/ ___|(_)_ __ ___  _ __ | | ___/ ___|  ___ __ _ _ __  
\___ \| | '_ ` _ \| '_ \| |/ _ \___ \ / __/ _` | '_ \ 
 ___) | | | | | | | |_) | |  __/___) | (_| (_| | | | |
|____/|_|_| |_| |_| .__/|_|\___|____/ \___\__,_|_| |_|
\033[0m \n";
}


function scan(string $host, array $ports)
{
    banner();

    $start = date("H:i:s");
    echo "\n\033[35m[INFO] Starting at $start \033[0m\n\n";

    echo "\nPORT    STATE \n";

    foreach ($ports as $port) {
        $con = @fsockopen($host, $port, $errno, $errstr, 2);

        if (is_resource($con)) {
            echo "$port/tcp   \033[32mopen\033[0m \n";
            fclose($con);
        } else {
            //echo "\033[31mCLOSED:\033[0m $port \n"

            if (count($ports) < 12)
                echo "$port/tcp   \033[31mclosed\033[0m \n";
        }
    }

    $end = date("H:i:s");
    echo "\n\033[35m[INFO] Finished at $end \033[0m\n\n";
}



if (empty($argv[1])) {
    banner();
    $filename = basename(__FILE__);
    echo "\nUSAGE:  php $filename <host> <ports> \n\n";
    echo "php $filename localhost \n";
    echo "php $filename localhost 80 \n";
    echo "php $filename localhost 21,22,23,25,80 \n";
    die("php $filename localhost 20-100 \n\n");
}


if (empty($argv[2])) {
    //default ports scan
    $default_ports = array(20, 21, 22, 23, 25, 53, 67, 68, 69, 80, 110, 143, 443, 995, 3000, 3306, 3389, 5500, 5900, 6667, 8000, 8080, 8443);
    scan($argv[1], $default_ports);
} else {

    if (str_contains($argv[2], "-")) {
        //range scan
        $ports = explode("-", $argv[2]);
        $range_ports = range($ports[0], $ports[1]);
        scan($argv[1], $range_ports);
    } else {
        //custom scan
        $ports = explode(",", $argv[2]);
        scan($argv[1], $ports);
    }
}