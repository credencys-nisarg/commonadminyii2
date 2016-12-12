<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class TimeZone {

    public static function getTimeZoneFromIpAddress() {
        $clientsIpAddress = $this->get_client_ip();
//        $clientsIpAddress = "192.88.99.1";
//        $clientsIpAddress = "192.88.99.1ZZ";

        $clientInformation = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $clientsIpAddress));

        $clientsLatitude = $clientInformation['geoplugin_latitude'];
        $clientsLongitude = $clientInformation['geoplugin_longitude'];
        $clientsCountryCode = $clientInformation['geoplugin_countryCode'];

        $timeZone = $this->get_nearest_timezone($clientsLatitude, $clientsLongitude, $clientsCountryCode);

        return $timeZone;
    }

    public static function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') {
        $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code) : DateTimeZone::listIdentifiers();

        if ($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

            $time_zone = '';
            $tz_distance = 0;

            //only one identifier?
            if (count($timezone_ids) == 1) {
                $time_zone = $timezone_ids[0];
            } else {

                foreach ($timezone_ids as $timezone_id) {
                    $timezone = new DateTimeZone($timezone_id);
                    $location = $timezone->getLocation();
                    $tz_lat = $location['latitude'];
                    $tz_long = $location['longitude'];

                    $theta = $cur_long - $tz_long;
                    $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat))) + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                    $distance = acos($distance);
                    $distance = abs(rad2deg($distance));
                    // echo '<br />'.$timezone_id.' '.$distance;

                    if (!$time_zone || $tz_distance > $distance) {
                        $time_zone = $timezone_id;
                        $tz_distance = $distance;
                    }
                }
            }
            return $time_zone;
        }
        return 'unknown';
    }

    public static function get_client_ip() {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if ($_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if ($_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

}
