<?php

namespace common\helper;

use Yii;



/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AppHelper {

    public static function getTest() {
        echo "App helper class";
        die;
    }

    public static function pr($obj, $ex = 0) {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
        if ($ex)
            exit;
    }

    /**
     * @desc : Print Array in format with exit
     * @Created by : Piyush Sutariya
     */
    public static function prex($arr) {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        exit;
    }

    public static function path($dir = '') {
        return \Yii::getAlias('@webroot') . '/' . $dir;
    }

    public static function url($dir = '') {
        return 'http://' . $_SERVER['HTTP_HOST'] . Url::base() . '/' . $dir;
    }

    ## get upload path

    public static function getImageUploadPath($path = '', $secure = '') {
//        $basePath = Yii::app()->basePath;
        $basePath = $_SERVER['DOCUMENT_ROOT'] . Url::base() . '/';
        if (!empty($secure)) {
            $basePath = $_SERVER['DOCUMENT_ROOT'] . Url::base() . '/';
        }

        if (!empty($path)) {
            $basePath = $basePath;
        }
        return $basePath;
    }

    ## get upload path

    public static function getImageUrl($path = '', $secure = '') {
//        $basePath = Yii::app()->basePath;
        $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . Url::base() . '/';
        if (!empty($secure)) {
            $baseUrl = 'https://' . $_SERVER['HTTP_HOST'] . Url::base() . '/';
        }

        if (!empty($path)) {
            $baseUrl = $baseUrl . $path;
        }
        return $baseUrl;
    }

    ## create newimage name with image_time().ext.

    public static function getNewImageName($imageName) {
        $imageName = strtolower($imageName);
        $paramArr = array();
        $extArr = explode('.', $imageName);
        $ext = array_pop($extArr);
        $tmpImgName = str_replace('.' . $ext, '', $imageName);
        $tmpImgName = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $tmpImgName);
        $newImageName = $tmpImgName . '_' . time() . '.' . $ext;
        return $newImageName;
    }

    ##get system parameters form main/config.php->params();

    public static function param($name, $foldername = '', $default = null) {
        if (isset(Yii::$app->params[$name])) {
            if (!empty($foldername)) {
                $path = Yii::$app->params[$name];
                return $path . $foldername . '/';
            }
            return Yii::$app->params[$name];
        } else {
            return $default;
        }
    }

    public static function objtoarray($object, $flag = '0') {

        return json_decode(json_encode($object), $flag);

//        return CJSON::decode(CJSON::encode($object));
        //return json_decode(json_encode($object));
    }

    public static function checkRequiredField($requestPara = array(), $require = array()) {
        $errorFlag = 0;
        $msg = array();
        foreach ($require as $key => $val) {

            if (!isset($_POST[$val]) || $requestPara[$val] == '') {
                $errorFlag++;
                $msg[] = "$val is required!";
            }
        }
        return array('errors' => $errorFlag, 'msg' => $msg);
    }

    public static function deleteFile($filename) {
        if (file_exists($filename)) {
            @unlink($filename);
            return true;
        } else {
            return false;
        }
    }

    //
    public static function manageDirPath($path) {
        if ($path != '') {
            if (!file_exists($path)) {
                mkdir($path, 0777);
                return "1";
            }
            return "1";
        }
        return "0";
    }

    public static function sendmail($content, $subject, $to, $from, $to_name, $attachment = '', $from_name = 'Test', $type = 'cc', $bc_arr = array()) {

        Yii::import("application.extensions.mailer.*", true);
        $mail = new PHPMailer;
        $mail->isSendmail();

        //Config mandrillapp SMTP Port
        $mail->IsSMTP();
        $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        if (App::isDevDomain()) {
            $mail->Host = "vps1.credencys.com";
            $mail->Port = 465;
            $mail->Username = "devcred";
            $mail->Password = "Cred098";
        } else {
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465;
            $mail->Username = "sanket.virani@credencys.com";
            $mail->Password = "Jd@virani";
        }

        $mail->setFrom($from, $from_name);
        $mail->addReplyTo($from, $from_name);
        $mail->addAddress($to);

        // Add in to the cc
        if (count($bc_arr) > 0) {
            if ($type == 'cc') {
                foreach ($bc_arr as $k => $v) {
                    $mail->AddCC($v);
                }
            } else {
                foreach ($bc_arr as $k => $v) {
                    $mail->AddBCC($v);
                }
            }
        }

        $mail->Subject = $subject;
        $mail->msgHTML($content, dirname(__FILE__));
        $mail->AltBody = 'This is a plain-text message body';
        if (!empty($attachment)) {
            $mail->addAttachment($attachment);
        }

        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }

    public static function deleterow($model, $id) {
        $model = $model::model()->findByPk($id);
        $model->is_active = 0;
        $model->is_delete = 1;
        if ($model->update())
            return true;
        else
            return false;
    }

    ## UI of cancel button to manage redirection

    public static function cancelButton($obj) {
        $lastUrl = str_replace("index.php/", "", Yii::$app->request->referrer);
        $currentUrl = str_replace("index.php/", "", "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

        if (strtolower($lastUrl) == strtolower($currentUrl)) {
            $url = App::param("siteurl") . $obj->id . "/admin";
        } else {
            $url = $lastUrl;
        }
        $return = '<a class="btn btn-danger btn-sm" id="btncancel" href="' . $url . '">Cancel</a>';
        return $return;
    }

    /**
    * @used GetCurrent GMT/UTC time in specificformate if passed else defualt take formate from config params
    * @return string current gmt datetime with specific formate
    */
    public static function GetDateTime($ymd = '') {
        $indiatimezone = new \DateTimeZone("UTC");
        $date = new \DateTime();
        $date->setTimezone($indiatimezone);
        if ($ymd) {
            return $date->format($ymd);
        } else {
            return $date->format(\Yii::$app->params['saveDateFormat']);
        }
    }

    /**
    * @used Convert date from one formate to another default is Y-m-d H:i:s
    * @return string converted date in specific formate
    */
    public static function convertDateFormate($inputdate,$formate="Y-m-d H:i:s"){
        return date($formate,strtotime($inputdate));
    }

    /**
    * @used Display datetime based on clienttimezone convert from gmt to localtimezone
    * @return string converted datetime in clienttimezone
    * @param date in Y-m-d H:i:s formate always to get perfect result
    */
    public static function displayDateTime($inputdate) {
        $timeZone = \Yii::$app->session->get('clientTimeZone');
        if(isset($timeZone) && !empty($timeZone)){
            return self::ConvertGMTToLocalTimezone($inputdate,$timeZone,\Yii::$app->params['displayDateFormat']);    
        }else{
            return date(\Yii::$app->params['displayDateFormat'],strtotime($inputdate));
        }
    }

    /**
    * @used Display datetime based on clienttimezone convert from gmt to localtimezone
    * @return string converted datetime in clienttimezone
    */
    public static function ConvertGMTToLocalTimezone($gmttime,$timezoneRequired="",$isTime=true,$formate="")
    {
        $system_timezone = date_default_timezone_get();

        date_default_timezone_set("GMT");
        $gmt = date("Y-m-d h:i:s A");
        if(empty($timezoneRequired)){
            $timezoneRequired = \Yii::$app->session->get('clientTimeZone');
        }
        $local_timezone = $timezoneRequired;
        date_default_timezone_set($local_timezone);
        $local = date("Y-m-d h:i:s A");

        date_default_timezone_set($system_timezone);
        $diff = (strtotime($local) - strtotime($gmt));
        
        if($gmttime != "-- 00:00:00")
        {
            $date = new \DateTime($gmttime);
            $date->modify("+$diff seconds");
            if($isTime){
              if(!empty($formate)){
                    $timestamp = $date->format($formate);    
              }else{
                    $timestamp = $date->format("m-d-Y H:i:s");    
              }
            }else{
                if(!empty($formate)){
                    $timestamp = $date->format($formate);                    
                }else{
                    $timestamp = $date->format("m-d-Y");    
                }
            }
       }
        else
        {
            $timestamp = $gmttime;
        }        
        return $timestamp;
    }

    /**
    * @used To convert localtimezone to GMT/UTC time for store datetime in database
    * @return string converted datetime in GMT/UTC
    */
    public static function ConvertLocalTimezoneToGMT($gmttime,$timezoneRequired,$isTime=true,$formate="")
    {
        
        $system_timezone = date_default_timezone_get();
     
        $local_timezone = $timezoneRequired;
        date_default_timezone_set($local_timezone);
        $local = date("Y-m-d h:i:s A");
     
        date_default_timezone_set("GMT");
        $gmt = date("Y-m-d h:i:s A");
     
        date_default_timezone_set($system_timezone);
        $diff = (strtotime($gmt) - strtotime($local));
     
        $date = new \DateTime($gmttime);
        $date->modify("+$diff seconds");
        if($isTime){
            if(!empty($formate)){
                $timestamp = $date->format($formate);    
            }else{
                $timestamp = $date->format("m-d-Y H:i:s");    
            }
            
        }else{

            if(!empty($formate)){
                $timestamp = $date->format($formate);
            }else{
                $timestamp = $date->format("m-d-Y");    
            }
            
        }

        return $timestamp;
    }

    /**
     * @desc Remove Directory Recursivly
     * @param type $dir
     * @return type
     */
    function recurseRmdir($dir) {
        if (substr($dir, strlen($dir) - 1, 1) != '/') {
            $dir .= '/';
        }
        $files = glob($dir . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::recurseRmdir($file);
            } else {
                unlink($file);
            }
        }
        return rmdir($dir);
    }

    /**

     * 
     * @param type $files : array of files
     * @param type $path : path where to upload image
     * @param type $max_file_size : max allowed size
     * @return string     /
     */
    public static function ImageUpload($files = '', $path = '', $max_file_size = '') {
        $allfiles = array();
        if (!empty($files) && !empty($path)) {
            $errors = array();
            $root = $files;
            foreach ($root['tmp_name'] as $key => $tmp_name) {
                $file_name = $root['name'][$key];
                $file_size = $root['size'][$key];
                $file_tmp = $root['tmp_name'][$key];
                $file_type = $root['type'][$key];
                if (!empty($max_file_size)) {
                    if ($file_size > $max_file_size) {
                        $errors[] = "File size must be less than $max_file_size";
                    }
                }
                if (!is_dir($path)) {
                    App::makeDirectory($path);
                }
                if (empty($errors) == true) {
                    if (is_dir($path . $file_name) == false) {
                        $file_name = time() . '_' . $file_name;
                        $allfiles[] = $file_name;
                        $testname = $path . $file_name;
                        $b = move_uploaded_file($file_tmp, $testname);
//                        echo $b;
//                        echo '<br>';
                    }
                } else {
                    print_r($errors);
                }
            }
        }
        return $allfiles;
    }

    public static function makeDirectory($path, $permission = 0777) {
        $path = $path;
        if (!is_dir($path)) {
            if (!file_exists($path)) {

                $old = umask(0);
                $isCreated = mkdir($path, $permission);
                umask($old);

                if ($isCreated) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public static function getSession($key) {
        return Yii::$app->session[$key];
    }

    public static function country_to_continent($country) {
        $continent = '';
        if ($country == 'AF')
            $continent = 'Asia';
        if ($country == 'AX')
            $continent = 'Europe';
        if ($country == 'AL')
            $continent = 'Europe';
        if ($country == 'DZ')
            $continent = 'Africa';
        if ($country == 'AS')
            $continent = 'Oceania';
        if ($country == 'AD')
            $continent = 'Europe';
        if ($country == 'AO')
            $continent = 'Africa';
        if ($country == 'AI')
            $continent = 'North America';
        if ($country == 'AQ')
            $continent = 'Antarctica';
        if ($country == 'AG')
            $continent = 'North America';
        if ($country == 'AR')
            $continent = 'South America';
        if ($country == 'AM')
            $continent = 'Asia';
        if ($country == 'AW')
            $continent = 'North America';
        if ($country == 'AU')
            $continent = 'Oceania';
        if ($country == 'AT')
            $continent = 'Europe';
        if ($country == 'AZ')
            $continent = 'Asia';
        if ($country == 'BS')
            $continent = 'North America';
        if ($country == 'BH')
            $continent = 'Asia';
        if ($country == 'BD')
            $continent = 'Asia';
        if ($country == 'BB')
            $continent = 'North America';
        if ($country == 'BY')
            $continent = 'Europe';
        if ($country == 'BE')
            $continent = 'Europe';
        if ($country == 'BZ')
            $continent = 'North America';
        if ($country == 'BJ')
            $continent = 'Africa';
        if ($country == 'BM')
            $continent = 'North America';
        if ($country == 'BT')
            $continent = 'Asia';
        if ($country == 'BO')
            $continent = 'South America';
        if ($country == 'BA')
            $continent = 'Europe';
        if ($country == 'BW')
            $continent = 'Africa';
        if ($country == 'BV')
            $continent = 'Antarctica';
        if ($country == 'BR')
            $continent = 'South America';
        if ($country == 'IO')
            $continent = 'Asia';
        if ($country == 'VG')
            $continent = 'North America';
        if ($country == 'BN')
            $continent = 'Asia';
        if ($country == 'BG')
            $continent = 'Europe';
        if ($country == 'BF')
            $continent = 'Africa';
        if ($country == 'BI')
            $continent = 'Africa';
        if ($country == 'KH')
            $continent = 'Asia';
        if ($country == 'CM')
            $continent = 'Africa';
        if ($country == 'CA')
            $continent = 'North America';
        if ($country == 'CV')
            $continent = 'Africa';
        if ($country == 'KY')
            $continent = 'North America';
        if ($country == 'CF')
            $continent = 'Africa';
        if ($country == 'TD')
            $continent = 'Africa';
        if ($country == 'CL')
            $continent = 'South America';
        if ($country == 'CN')
            $continent = 'Asia';
        if ($country == 'CX')
            $continent = 'Asia';
        if ($country == 'CC')
            $continent = 'Asia';
        if ($country == 'CO')
            $continent = 'South America';
        if ($country == 'KM')
            $continent = 'Africa';
        if ($country == 'CD')
            $continent = 'Africa';
        if ($country == 'CG')
            $continent = 'Africa';
        if ($country == 'CK')
            $continent = 'Oceania';
        if ($country == 'CR')
            $continent = 'North America';
        if ($country == 'CI')
            $continent = 'Africa';
        if ($country == 'HR')
            $continent = 'Europe';
        if ($country == 'CU')
            $continent = 'North America';
        if ($country == 'CY')
            $continent = 'Asia';
        if ($country == 'CZ')
            $continent = 'Europe';
        if ($country == 'DK')
            $continent = 'Europe';
        if ($country == 'DJ')
            $continent = 'Africa';
        if ($country == 'DM')
            $continent = 'North America';
        if ($country == 'DO')
            $continent = 'North America';
        if ($country == 'EC')
            $continent = 'South America';
        if ($country == 'EG')
            $continent = 'Africa';
        if ($country == 'SV')
            $continent = 'North America';
        if ($country == 'GQ')
            $continent = 'Africa';
        if ($country == 'ER')
            $continent = 'Africa';
        if ($country == 'EE')
            $continent = 'Europe';
        if ($country == 'ET')
            $continent = 'Africa';
        if ($country == 'FO')
            $continent = 'Europe';
        if ($country == 'FK')
            $continent = 'South America';
        if ($country == 'FJ')
            $continent = 'Oceania';
        if ($country == 'FI')
            $continent = 'Europe';
        if ($country == 'FR')
            $continent = 'Europe';
        if ($country == 'GF')
            $continent = 'South America';
        if ($country == 'PF')
            $continent = 'Oceania';
        if ($country == 'TF')
            $continent = 'Antarctica';
        if ($country == 'GA')
            $continent = 'Africa';
        if ($country == 'GM')
            $continent = 'Africa';
        if ($country == 'GE')
            $continent = 'Asia';
        if ($country == 'DE')
            $continent = 'Europe';
        if ($country == 'GH')
            $continent = 'Africa';
        if ($country == 'GI')
            $continent = 'Europe';
        if ($country == 'GR')
            $continent = 'Europe';
        if ($country == 'GL')
            $continent = 'North America';
        if ($country == 'GD')
            $continent = 'North America';
        if ($country == 'GP')
            $continent = 'North America';
        if ($country == 'GU')
            $continent = 'Oceania';
        if ($country == 'GT')
            $continent = 'North America';
        if ($country == 'GG')
            $continent = 'Europe';
        if ($country == 'GN')
            $continent = 'Africa';
        if ($country == 'GW')
            $continent = 'Africa';
        if ($country == 'GY')
            $continent = 'South America';
        if ($country == 'HT')
            $continent = 'North America';
        if ($country == 'HM')
            $continent = 'Antarctica';
        if ($country == 'VA')
            $continent = 'Europe';
        if ($country == 'HN')
            $continent = 'North America';
        if ($country == 'HK')
            $continent = 'Asia';
        if ($country == 'HU')
            $continent = 'Europe';
        if ($country == 'IS')
            $continent = 'Europe';
        if ($country == 'IN')
            $continent = 'Asia';
        if ($country == 'ID')
            $continent = 'Asia';
        if ($country == 'IR')
            $continent = 'Asia';
        if ($country == 'IQ')
            $continent = 'Asia';
        if ($country == 'IE')
            $continent = 'Europe';
        if ($country == 'IM')
            $continent = 'Europe';
        if ($country == 'IL')
            $continent = 'Asia';
        if ($country == 'IT')
            $continent = 'Europe';
        if ($country == 'JM')
            $continent = 'North America';
        if ($country == 'JP')
            $continent = 'Asia';
        if ($country == 'JE')
            $continent = 'Europe';
        if ($country == 'JO')
            $continent = 'Asia';
        if ($country == 'KZ')
            $continent = 'Asia';
        if ($country == 'KE')
            $continent = 'Africa';
        if ($country == 'KI')
            $continent = 'Oceania';
        if ($country == 'KP')
            $continent = 'Asia';
        if ($country == 'KR')
            $continent = 'Asia';
        if ($country == 'KW')
            $continent = 'Asia';
        if ($country == 'KG')
            $continent = 'Asia';
        if ($country == 'LA')
            $continent = 'Asia';
        if ($country == 'LV')
            $continent = 'Europe';
        if ($country == 'LB')
            $continent = 'Asia';
        if ($country == 'LS')
            $continent = 'Africa';
        if ($country == 'LR')
            $continent = 'Africa';
        if ($country == 'LY')
            $continent = 'Africa';
        if ($country == 'LI')
            $continent = 'Europe';
        if ($country == 'LT')
            $continent = 'Europe';
        if ($country == 'LU')
            $continent = 'Europe';
        if ($country == 'MO')
            $continent = 'Asia';
        if ($country == 'MK')
            $continent = 'Europe';
        if ($country == 'MG')
            $continent = 'Africa';
        if ($country == 'MW')
            $continent = 'Africa';
        if ($country == 'MY')
            $continent = 'Asia';
        if ($country == 'MV')
            $continent = 'Asia';
        if ($country == 'ML')
            $continent = 'Africa';
        if ($country == 'MT')
            $continent = 'Europe';
        if ($country == 'MH')
            $continent = 'Oceania';
        if ($country == 'MQ')
            $continent = 'North America';
        if ($country == 'MR')
            $continent = 'Africa';
        if ($country == 'MU')
            $continent = 'Africa';
        if ($country == 'YT')
            $continent = 'Africa';
        if ($country == 'MX')
            $continent = 'North America';
        if ($country == 'FM')
            $continent = 'Oceania';
        if ($country == 'MD')
            $continent = 'Europe';
        if ($country == 'MC')
            $continent = 'Europe';
        if ($country == 'MN')
            $continent = 'Asia';
        if ($country == 'ME')
            $continent = 'Europe';
        if ($country == 'MS')
            $continent = 'North America';
        if ($country == 'MA')
            $continent = 'Africa';
        if ($country == 'MZ')
            $continent = 'Africa';
        if ($country == 'MM')
            $continent = 'Asia';
        if ($country == 'NA')
            $continent = 'Africa';
        if ($country == 'NR')
            $continent = 'Oceania';
        if ($country == 'NP')
            $continent = 'Asia';
        if ($country == 'AN')
            $continent = 'North America';
        if ($country == 'NL')
            $continent = 'Europe';
        if ($country == 'NC')
            $continent = 'Oceania';
        if ($country == 'NZ')
            $continent = 'Oceania';
        if ($country == 'NI')
            $continent = 'North America';
        if ($country == 'NE')
            $continent = 'Africa';
        if ($country == 'NG')
            $continent = 'Africa';
        if ($country == 'NU')
            $continent = 'Oceania';
        if ($country == 'NF')
            $continent = 'Oceania';
        if ($country == 'MP')
            $continent = 'Oceania';
        if ($country == 'NO')
            $continent = 'Europe';
        if ($country == 'OM')
            $continent = 'Asia';
        if ($country == 'PK')
            $continent = 'Asia';
        if ($country == 'PW')
            $continent = 'Oceania';
        if ($country == 'PS')
            $continent = 'Asia';
        if ($country == 'PA')
            $continent = 'North America';
        if ($country == 'PG')
            $continent = 'Oceania';
        if ($country == 'PY')
            $continent = 'South America';
        if ($country == 'PE')
            $continent = 'South America';
        if ($country == 'PH')
            $continent = 'Asia';
        if ($country == 'PN')
            $continent = 'Oceania';
        if ($country == 'PL')
            $continent = 'Europe';
        if ($country == 'PT')
            $continent = 'Europe';
        if ($country == 'PR')
            $continent = 'North America';
        if ($country == 'QA')
            $continent = 'Asia';
        if ($country == 'RE')
            $continent = 'Africa';
        if ($country == 'RO')
            $continent = 'Europe';
        if ($country == 'RU')
            $continent = 'Europe';
        if ($country == 'RW')
            $continent = 'Africa';
        if ($country == 'BL')
            $continent = 'North America';
        if ($country == 'SH')
            $continent = 'Africa';
        if ($country == 'KN')
            $continent = 'North America';
        if ($country == 'LC')
            $continent = 'North America';
        if ($country == 'MF')
            $continent = 'North America';
        if ($country == 'PM')
            $continent = 'North America';
        if ($country == 'VC')
            $continent = 'North America';
        if ($country == 'WS')
            $continent = 'Oceania';
        if ($country == 'SM')
            $continent = 'Europe';
        if ($country == 'ST')
            $continent = 'Africa';
        if ($country == 'SA')
            $continent = 'Asia';
        if ($country == 'SN')
            $continent = 'Africa';
        if ($country == 'RS')
            $continent = 'Europe';
        if ($country == 'SC')
            $continent = 'Africa';
        if ($country == 'SL')
            $continent = 'Africa';
        if ($country == 'SG')
            $continent = 'Asia';
        if ($country == 'SK')
            $continent = 'Europe';
        if ($country == 'SI')
            $continent = 'Europe';
        if ($country == 'SB')
            $continent = 'Oceania';
        if ($country == 'SO')
            $continent = 'Africa';
        if ($country == 'ZA')
            $continent = 'Africa';
        if ($country == 'GS')
            $continent = 'Antarctica';
        if ($country == 'ES')
            $continent = 'Europe';
        if ($country == 'LK')
            $continent = 'Asia';
        if ($country == 'SD')
            $continent = 'Africa';
        if ($country == 'SR')
            $continent = 'South America';
        if ($country == 'SJ')
            $continent = 'Europe';
        if ($country == 'SZ')
            $continent = 'Africa';
        if ($country == 'SE')
            $continent = 'Europe';
        if ($country == 'CH')
            $continent = 'Europe';
        if ($country == 'SY')
            $continent = 'Asia';
        if ($country == 'TW')
            $continent = 'Asia';
        if ($country == 'TJ')
            $continent = 'Asia';
        if ($country == 'TZ')
            $continent = 'Africa';
        if ($country == 'TH')
            $continent = 'Asia';
        if ($country == 'TL')
            $continent = 'Asia';
        if ($country == 'TG')
            $continent = 'Africa';
        if ($country == 'TK')
            $continent = 'Oceania';
        if ($country == 'TO')
            $continent = 'Oceania';
        if ($country == 'TT')
            $continent = 'North America';
        if ($country == 'TN')
            $continent = 'Africa';
        if ($country == 'TR')
            $continent = 'Asia';
        if ($country == 'TM')
            $continent = 'Asia';
        if ($country == 'TC')
            $continent = 'North America';
        if ($country == 'TV')
            $continent = 'Oceania';
        if ($country == 'UG')
            $continent = 'Africa';
        if ($country == 'UA')
            $continent = 'Europe';
        if ($country == 'AE')
            $continent = 'Asia';
        if ($country == 'GB')
            $continent = 'Europe';
        if ($country == 'US')
            $continent = 'North America';
        if ($country == 'UM')
            $continent = 'Oceania';
        if ($country == 'VI')
            $continent = 'North America';
        if ($country == 'UY')
            $continent = 'South America';
        if ($country == 'UZ')
            $continent = 'Asia';
        if ($country == 'VU')
            $continent = 'Oceania';
        if ($country == 'VE')
            $continent = 'South America';
        if ($country == 'VN')
            $continent = 'Asia';
        if ($country == 'WF')
            $continent = 'Oceania';
        if ($country == 'EH')
            $continent = 'Africa';
        if ($country == 'YE')
            $continent = 'Asia';
        if ($country == 'ZM')
            $continent = 'Africa';
        if ($country == 'ZW')
            $continent = 'Africa';
        return $continent;
    }

    /**
     * @desc Rajesh on 13/10/2016 for image cropping
     * @param $data containg image related data
     * @return string
     */
    public static function CropImage($data) {
        $tempName = @explode('.', $data['image']);
        $imageName = $tempName[count($tempName) - 2];
        $ext = $tempName[count($tempName) - 1];
        $newFullPath = $data['newpath']."/".$imageName . '.jpg';
        switch ($ext) {
            case 'jpg':
                $img_r = imagecreatefromjpeg($data['imgpath']);
                break;
            case 'jpeg':
                $img_r = imagecreatefromjpeg($data['imgpath']);
                break;
            case 'png':
                $img_r = imagecreatefrompng($data['imgpath']);
                break;
            case 'JPG':
                $img_r = imagecreatefromjpeg($data['imgpath']);
                break;
            case 'JPEG':
                $img_r = imagecreatefromjpeg($data['imgpath']);
                break;
            case 'PNG':
                $img_r = imagecreatefrompng($data['imgpath']);
                break;
            default :
                $img_r = imagecreatefromjpeg($data['imgpath']);
                break;
        }
        $dst_r = ImageCreateTrueColor($data['width'], $data['height']);
        imagecopyresampled($dst_r, $img_r, 0, 0, $data['x'], $data['y'], $data['width'], $data['height'], $data['w'], $data['h']);
        imagejpeg($dst_r, $newFullPath, 87);
        return $newFullPath;
    }
}
