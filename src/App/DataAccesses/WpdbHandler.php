<?php
namespace App\DataAccesses;

use Doctrine\DBAL\Connection;

class WpdbHandler
{
    protected $doctrine;
    protected $javo_doctrine;

    public function __construct(Connection $doctrine,Connection $javo_doctrine)
    {
        $this->doctrine = $doctrine;
        $this->javo_doctrine = $javo_doctrine;
    }

    public function initAutoIncrement(){
        $maxID = $this->doctrine->fetchColumn('SELECT MAX(ID) FROM wp_posts');
        $maxID = (int)$maxID + 1;
        $query = 'ALTER TABLE `wp_posts` AUTO_INCREMENT = '. $maxID;
        $statement = $this->doctrine->prepare($query);
        $statement->execute();

        $maxID = $this->doctrine->fetchColumn('SELECT MAX(meta_id) FROM wp_postmeta');
        $maxID = (int)$maxID + 1;
        $query = 'ALTER TABLE `wp_postmeta` AUTO_INCREMENT = '. $maxID;
        $statement = $this->doctrine->prepare($query);
        $statement->execute();

        $maxID = $this->doctrine->fetchColumn('SELECT MAX(term_id) FROM wp_terms');
        $maxID = (int)$maxID + 1;
        $query = 'ALTER TABLE `wp_terms` AUTO_INCREMENT = '. $maxID;
        $statement = $this->doctrine->prepare($query);
        $statement->execute();
    }

    public function initJavoAutoIncrement(){
        $maxID = $this->javo_doctrine->fetchColumn('SELECT MAX(ID) FROM wp_posts');
        $maxID = (int)$maxID + 1;
        $query = 'ALTER TABLE `wp_posts` AUTO_INCREMENT = '. $maxID;
        $statement = $this->javo_doctrine->prepare($query);
        $statement->execute();

        $maxID = $this->javo_doctrine->fetchColumn('SELECT MAX(meta_id) FROM wp_postmeta');
        $maxID = (int)$maxID + 1;
        $query = 'ALTER TABLE `wp_postmeta` AUTO_INCREMENT = '. $maxID;
        $statement = $this->javo_doctrine->prepare($query);
        $statement->execute();

        $maxID = $this->javo_doctrine->fetchColumn('SELECT MAX(term_id) FROM wp_terms');
        $maxID = (int)$maxID + 1;
        $query = 'ALTER TABLE `wp_terms` AUTO_INCREMENT = '. $maxID;
        $statement = $this->javo_doctrine->prepare($query);
        $statement->execute();
    }

    public function getResouceAEON(){
        $fp = fopen('/home/t_o/works/silex/resource/content_AEON_kai.txt', 'r');
        $count = 1;
        $num = 0;
        $shop_data = array();
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while (!feof($fp)) {
                    $buffer = fgets($fp);
                    $buffer = preg_replace('/　/', ' ', $buffer);
                    $buffer = preg_replace('/\s+/', ' ', $buffer);
                    $now_line = $count % 9;
                    $num = floor($count/9);
                    switch ($now_line) {
                        case 1:
                            $shop_data[$num] = array('url' => trim($buffer));
                            break;
                        case 2:
                            $shop_data[$num] += array('shop_name' => 'AEON '. trim($buffer));
                            break;
                        case 3:
                            $shop_data[$num] += array('phone_number' => trim($buffer));
                            break;
                        case 4:
                            $pos_day = strrpos($buffer, '21:00');
                            $pos_sat = strrpos($buffer, '土');
                            $weekday = substr($buffer, 0, $pos_day+5);
                            $weekend = substr($buffer, $pos_sat);
                            $shop_data[$num] += array('weekday_business_hours' => trim($weekday));
                            $shop_data[$num] += array('weekend_business_hours' => trim($weekend));
                            break;
                        case 5:
                            $shop_data[$num] += array('closing_day' => trim($buffer));
                            break;
                        case 6:
                            $shop_data[$num] += array('address' => trim($buffer));
                            break;
                        default:
                            break;
                    }
                    $count++;
                }
                flock($fp, LOCK_UN);
            }else{
                echo 'ファイルロックに失敗しました' .PHP_EOL;
            }
        }
        return $shop_data;
    }

        public function getResouceBER(){
        $fp = fopen('/home/t_o/works/silex/resource/content_BER.txt', 'r');
        $count = 1;
        $num = 0;
        $shop_data = array();
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while (!feof($fp)) {
                    $buffer = fgets($fp);
                    $buffer = preg_replace('/　/', ' ', $buffer);
                    $buffer = preg_replace('/\s+/', ' ', $buffer);
                    $now_line = $count % 6;
                    $num = floor($count/6);
                    switch ($now_line) {
                        case 1:
                            $shop_data[$num] = array('url' => trim($buffer));
                            break;
                        case 2:
                            $shop_data[$num] += array('shop_name' => 'Berlitz '. trim($buffer));
                            break;
                        case 3:
                            $shop_data[$num] += array('address' => trim($buffer));
                            break;
                        case 4:
                            $pos_asta = mb_strrpos($buffer, '※');
                            if($pos_asta){
                                $closing_day = mb_substr($buffer, $pos_asta + 4);
                                $shop_data[$num] += array('closing_day' => trim($closing_day));
                                $buffer = mb_substr($buffer, 0, $pos_asta);
                            }
                            $pos_day = mb_strpos($buffer, '（');
                            $pos_sat = mb_strpos($buffer, '）');
                            $weekday = mb_substr($buffer, 0, $pos_day);
                            $weekend = mb_substr($buffer, $pos_sat + 4);
                            $shop_data[$num] += array('weekday_business_hours' => trim($weekday));
                            $shop_data[$num] += array('weekend_business_hours' => trim($weekend));
                            break;
                        default:
                            break;
                    }
                    $count++;
                }
                flock($fp, LOCK_UN);
            }else{
                echo 'ファイルロックに失敗しました' .PHP_EOL;
            }
        }
        return $shop_data;
    }

    public function getResouceGABA(){
        $fp = fopen('/home/t_o/works/silex/resource/content_GABA.txt', 'r');
        $count = 1;
        $num = 0;
        $shop_data = array();
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while (!feof($fp)) {
                    $buffer = fgets($fp);
                    $buffer = preg_replace('/　/', ' ', $buffer);
                    $buffer = preg_replace('/\s+/', ' ', $buffer);
                    $now_line = $count % 6;
                    $num = floor($count/6);
                    switch ($now_line) {
                        case 1:
                            $shop_data[$num] = array('url' => trim($buffer));
                            break;
                        case 2:
                            $shop_data[$num] += array('shop_name' => 'Gaba '. trim($buffer));
                            break;
                        case 3:
                            $shop_data[$num] += array('address' => trim($buffer));
                            break;
                        case 4:
                            $pos_day = strpos($buffer, '/');
                            $pos_sat = strrpos($buffer, ':25');
                            $weekday = substr($buffer, 0, $pos_day);
                            $weekend = substr($buffer, $pos_day+1, ($pos_sat+3) - ($pos_day +1) );
                            $shop_data[$num] += array('weekday_business_hours' => trim($weekday));
                            $shop_data[$num] += array('weekend_business_hours' => trim($weekend));
                            $closing_day = substr($buffer, $pos_sat+4);
                            if($closing_day){
                                $shop_data[$num] += array('closing_day' => trim($closing_day));
                            }
                            break;
                        default:
                            break;
                    }
                    $count++;
                }
                flock($fp, LOCK_UN);
            }else{
                echo 'ファイルロックに失敗しました' .PHP_EOL;
            }
        }
        return $shop_data;
    }

    public function getResouceECC(){
        $fp = fopen('/home/t_o/works/silex/resource/content_ecc.txt', 'r');
        $count = 1;
        $num = 0;
        $shop_data = array();
        $closing_day = '';
        $weekday = '';
        $weekend = '';
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while (!feof($fp)) {
                    $buffer = fgets($fp);
                    $buffer = preg_replace('/　/', ' ', $buffer);
                    $buffer = preg_replace('/\s+/', ' ', $buffer);
                    $now_line = $count % 15;
                    $num = floor($count/15);
                    switch ($now_line) {
                        case 1:
                            $shop_data[$num] = array('url' => trim($buffer));
                            break;
                        case 2:
                            $shop_data[$num] += array('shop_name' => 'ecc '. trim($buffer));
                            break;
                        case 3:
                            $shop_data[$num] += array('phone_number' => trim($buffer));
                            break;
                        case 4:
                            $shop_data[$num] += array('address' => trim($buffer));
                            break;
                        case 5:
                            if(strstr($buffer, 'お休み')){
                                $closing_day .= '月 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 6:
                            if(strstr($buffer, 'お休み')){
                                $closing_day .= '火 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 7:
                            if(strstr($buffer, 'お休み')){
                                $closing_day .= '水 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 8:
                            if(strstr($buffer, 'お休み')){
                                $closing_day .= '木 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 9:
                            if(strstr($buffer, 'お休み')){
                                $closing_day .= '金 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 10:
                            if(strstr($buffer, 'お休み')){
                                $closing_day .= '土 ';
                                break;
                            }
                            $weekend .= $buffer;
                            break;
                        case 11:
                            if(strstr($buffer, 'お休み')){
                                $closing_day .= '日 ';
                                break;
                            }
                            $weekend .= $buffer;
                            break;
                        case 12:
                            if(strstr($buffer, 'お休み')){
                                $closing_day .= '祝';
                                break;
                            }
                            $weekend .= $buffer;
                            break;
                        case 13:
                            $shop_data[$num] += array('closing_day' => trim($closing_day));
                            $closing_day = '';
                            $shop_data[$num] += array('weekday_business_hours' => trim($weekday));
                            $shop_data[$num] += array('weekend_business_hours' => trim($weekend));
                            $weekday = '';
                            $weekend = '';
                            break;
                        default:
                            break;
                    }
                    $count++;
                }
                flock($fp, LOCK_UN);
            }else{
                echo 'ファイルロックに失敗しました' .PHP_EOL;
            }
        }
        return $shop_data;
    }

    public function getResouceSHANE(){
        $fp = fopen('/home/t_o/works/silex/resource/content_shane.txt', 'r');
        $count = 1;
        $num = 0;
        $shop_data = array();
        $closing_day = '';
        $weekday = '';
        $weekend = '';
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while (!feof($fp)) {
                    $buffer = fgets($fp);
                    $buffer = preg_replace('/　/', ' ', $buffer);
                    $buffer = preg_replace('/\s+/', ' ', $buffer);
                    $now_line = $count % 15;
                    $num = floor($count/15);
                    switch ($now_line) {
                        case 1:
                            $shop_data[$num] = array('url' => trim($buffer));
                            break;
                        case 2:
                            $shop_data[$num] += array('shop_name' => 'シェーン英会話 '. trim($buffer));
                            break;
                        case 3:
                            $shop_data[$num] += array('address' => trim($buffer));
                            break;
                        case 4:
                            $shop_data[$num] += array('phone_number' => trim($buffer));
                            break;
                        case 5:
                            if(strstr($buffer, '休校')){
                                $closing_day .= '月 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 6:
                            if(strstr($buffer, '休校')){
                                $closing_day .= '火 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 7:
                            if(strstr($buffer, '休校')){
                                $closing_day .= '水 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 8:
                            if(strstr($buffer, '休校')){
                                $closing_day .= '木 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 9:
                            if(strstr($buffer, '休校')){
                                $closing_day .= '金 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 10:
                            if(strstr($buffer, '休校')){
                                $closing_day .= '土 ';
                                break;
                            }
                            $weekend .= $buffer;
                            break;
                        case 11:
                            if(strstr($buffer, '休校')){
                                $closing_day .= '日 ';
                                break;
                            }
                            $weekend .= $buffer;
                            break;
                        case 12:
                            $shop_data[$num] += array('closing_day' => trim($closing_day));
                            $closing_day = '';
                            $shop_data[$num] += array('weekday_business_hours' => trim($weekday));
                            $shop_data[$num] += array('weekend_business_hours' => trim($weekend));
                            $weekday = '';
                            $weekend = '';
                            break;
                        default:
                            break;
                    }
                    $count++;
                }
                flock($fp, LOCK_UN);
            }else{
                echo 'ファイルロックに失敗しました' .PHP_EOL;
            }
        }
        return $shop_data;
    }

    public function getResouceNOVA(){
        $fp = fopen('/home/t_o/works/silex/resource/content_nova.txt', 'r');
        $count = 1;
        $num = 0;
        $shop_data = array();
        $closing_day = '';
        $weekday = '';
        $weekend = '';
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while (!feof($fp)) {
                    $buffer = fgets($fp);
                    $buffer = preg_replace('/　/', ' ', $buffer);
                    $buffer = preg_replace('/\s+/', ' ', $buffer);
                    $now_line = $count % 14;
                    $num = floor($count/14);
                    switch ($now_line) {
                        case 1:
                            $shop_data[$num] = array('url' => trim($buffer));
                            break;
                        case 2:
                            $shop_data[$num] += array('shop_name' => 'NOVA '. trim($buffer));
                            break;
                        case 3:
                            $shop_data[$num] += array('address' => trim($buffer));
                            break;
                        case 4:
                            $shop_data[$num] += array('phone_number' => trim($buffer));
                            break;
                        case 5:
                            if(strstr($buffer, '休')){
                                $closing_day .= '月 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 6:
                            if(strstr($buffer, '休')){
                                $closing_day .= '火 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 7:
                            if(strstr($buffer, '休')){
                                $closing_day .= '水 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 8:
                            if(strstr($buffer, '休')){
                                $closing_day .= '木 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 9:
                            if(strstr($buffer, '休')){
                                $closing_day .= '金 ';
                                break;
                            }
                            $weekday .= $buffer;
                            break;
                        case 10:
                            if(strstr($buffer, '休')){
                                $closing_day .= '土 ';
                                break;
                            }
                            $weekend .= $buffer;
                            break;
                        case 11:
                            if(strstr($buffer, '休')){
                                $closing_day .= '日 ';
                                break;
                            }
                            $weekend .= $buffer;
                            break;
                        case 12:
                            if(strstr($buffer, '休')){
                                $closing_day .= '祝 ';
                                break;
                            }
                            $weekend .= $buffer;
                            break;
                        case 13:
                            $shop_data[$num] += array('closing_day' => trim($closing_day));
                            $closing_day = '';
                            $shop_data[$num] += array('weekday_business_hours' => trim($weekday));
                            $shop_data[$num] += array('weekend_business_hours' => trim($weekend));
                            $weekday = '';
                            $weekend = '';
                            break;
                        default:
                            break;
                    }
                    $count++;
                }
                flock($fp, LOCK_UN);
            }else{
                echo 'ファイルロックに失敗しました' .PHP_EOL;
            }
        }
        return $shop_data;
    }

    public function getResouceCOCO(){
        $fp = fopen('/home/t_o/works/silex/resource/content_coco.txt', 'r');
        $count = 1;
        $num = 0;
        $shop_data = array();
        $closing_day = '';
        $weekday = '';
        $weekend = '';
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while (!feof($fp)) {
                    $buffer = fgets($fp);
                    $buffer = preg_replace('/　/', ' ', $buffer);
                    $buffer = preg_replace('/\s+/', ' ', $buffer);
                    $now_line = $count % 7;
                    $num = floor($count/7);
                    switch ($now_line) {
                        case 1:
                            $shop_data[$num] = array('url' => trim($buffer));
                            break;
                        case 2:
                            $shop_data[$num] += array('shop_name' => trim($buffer));
                            break;
                        case 3:
                            $shop_data[$num] += array('address' => trim($buffer));
                            break;
                        case 4:
                            $shop_data[$num] += array('phone_number' => trim($buffer));
                            break;
                        case 5:
                            $pos_asta = strrpos($buffer, '※');
                            $pos_teikyu = strrpos($buffer, '定休');
                            if($pos_asta){
                                $closing_day = substr($buffer, $pos_asta+3, $pos_teikyu-($pos_asta+3));
                                $shop_data[$num] += array('closing_day' => trim($closing_day));
                                $buffer = substr($buffer, 0, $pos_asta). substr($buffer, $pos_teikyu+6);
                            }
                            $pos_sat = mb_strrpos($buffer, '土');
                            $weekday = mb_substr($buffer, 0, $pos_sat);
                            $weekend = mb_substr($buffer, $pos_sat);
                            $shop_data[$num] += array('weekday_business_hours' => trim($weekday));
                            $shop_data[$num] += array('weekend_business_hours' => trim($weekend));
                            break;
                        default:
                            break;
                    }
                    $count++;
                }
                flock($fp, LOCK_UN);
            }else{
                echo 'ファイルロックに失敗しました' .PHP_EOL;
            }
        }
        return $shop_data;
    }

    public function getResouce(){
        $fp = fopen('/home/t_o/works/silex/resource/content_winbe.txt', 'r');
        $count = 1;
        $num = 0;
        $shop_data = array();
        $closing_day = '';
        $weekday = '';
        $weekend = '';
        if ($fp){
            if (flock($fp, LOCK_SH)){
                while (!feof($fp)) {
                    $buffer = fgets($fp);
                    $buffer = preg_replace('/　/', ' ', $buffer);
                    $buffer = preg_replace('/\s+/', ' ', $buffer);
                    $now_line = $count % 5;
                    $num = floor($count/5);
                    switch ($now_line) {
                        case 1:
                            $shop_data[$num] = array('url' => 'http://www.tact-net.jp/winbe/index.htm');
                            $shop_data[$num] += array('shop_name' => 'WinBe '. trim($buffer));
                            break;
                        case 2:
                            $shop_data[$num] += array('address' => trim($buffer));
                            break;
                        case 3:
                            $shop_data[$num] += array('phone_number' => trim($buffer));
                            break;
                        default:
                            break;
                    }
                    $count++;
                }
                flock($fp, LOCK_UN);
            }else{
                echo 'ファイルロックに失敗しました' .PHP_EOL;
            }
        }
        return $shop_data;
    }

    public function pushJavo(){
        $this->initJavoAutoIncrement();
        $date = date(DATE_ATOM);


        for ($i=975; $i <= 1020 ; $i++) {
            $id = $i;

            $post = $this->doctrine->fetchAll('SELECT * FROM wp_posts WHERE ID= ?', array($id));
            // var_dump($post);
            $shop_name = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'shop_name'));
            $shop_lat = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'lat'));
            $shop_lng = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'lng'));
            $shop_address = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'address'));
            $shop_phone_number = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'phone_number'));
            $shop_url = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'url'));
            $shop_weekend_business_hours = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'weekend_business_hours'));
            $shop_weekday_business_hours= $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'weekday_business_hours'));
            $shop_closing_day = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'closing_day'));
            $shop_slug_name = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'slug_name'));
            $shop_near_station_name = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'near_station_name'));
            $shop_near_station_distance = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'near_station_distance'));
            $shop_to_child = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'to_child'));
            if((int)$shop_to_child ===1){
                $shop_to_child = 'Yes';
            }else{
                $shop_to_child = 'No';
            }
            $shop_to_adult = $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'to_adult'));
            if((int)$shop_to_adult ===1){
                $shop_to_adult = 'Yes';
            }else{
                $shop_to_adult = 'No';
            }
            $shop_to_one= $this->doctrine->fetchColumn('SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = ?', array($id, 'to_one'));
            if((int)$shop_to_one ===1){
                $shop_to_one = 's:34:"idfb08221912114f45211fe88cecca42f7";a:2:{s:5:"value";a:2:{i:0;s:5:"adult";i:1;s:5:"child";}s:5:"label";s:13:"Private class";}}';
            }else{
                $shop_to_one = 's:34:"idfb08221912114f45211fe88cecca42f7";a:1:{s:5:"label";s:13:"Private class";}}';
            }

            // var_dump($shop_name);
            // return;
            preg_match('/(東京都|北海道|(?:京都|大阪)府|.{6,9}県)((?:四日市|廿日市|野々市|かすみがうら|つくばみらい|いちき串木野)市|(?:杵島郡大町|余市郡余市|高市郡高取)町|.{3,12}市.{3,12}区|.{3,9}区|.{3,15}市(?=.*市)|.{3,15}市|.{6,27}町(?=.*町)|.{6,27}町|.{9,24}村(?=.*村)|.{9,24}村)(.*)/', $shop_address, $address_data);
            $parent_category = $this->javo_doctrine->fetchAll('SELECT * FROM wp_terms WHERE name = ?', array($address_data[1]));
            // var_dump($parent_category);
            $cus_latlng = 'a:7:{s:3:"lat";s:'. strlen($shop_lat) .':"'. $shop_lat. '";s:3:"lng";s:'. strlen($shop_lng). ':"'. $shop_lng. '";s:10:"street_lat";s:'. strlen($shop_lat).':"'. $shop_lat. '";s:10:"street_lng";s:'.strlen($shop_lng).':"'. $shop_lng. '";s:14:"street_heading";s:1:"0";s:12:"street_pitch";s:1:"0";s:11:"street_zoom";s:1:"0";}';
            $cus_latlng_strlen = strlen($cus_latlng);
            $cus_latlng = 's:'. $cus_latlng_strlen. ':"'. $cus_latlng. '";';

            $cus_directory_meta = 'a:4:{s:7:"address";s:'.strlen($shop_address).':"'. $shop_address. '";s:5:"phone";s:'.strlen($shop_phone_number).':"'. $shop_phone_number . '";s:5:"email";s:0:"";s:7:"website";s:'.strlen($shop_url).':"'. $shop_url. '";}';
            $cus_directory_meta_strlen = strlen($cus_directory_meta);
            $cus_directory_meta = 's:'. $cus_directory_meta_strlen. ':"'. $cus_directory_meta. '";';

            $cus_custom_variables = 'a:7:{s:34:"id01ebbda0392593f472734cc46b780a13";a:2:{s:5:"value";s:'.strlen($shop_near_station_name. '駅 '. $shop_near_station_distance).':"'. $shop_near_station_name. '駅 '. $shop_near_station_distance. '";s:5:"label";s:15:"Nearest station";}s:34:"id1d29875a5820da081fca20c2863ede2c";a:2:{s:5:"value";s:'.strlen($shop_weekday_business_hours).':"'. $shop_weekday_business_hours.'";s:5:"label";s:22:"Weekday business hours";}s:34:"id1df358282b792138a9f6373a44bf4e11";a:2:{s:5:"value";s:'.strlen($shop_weekend_business_hours).':"'. $shop_weekend_business_hours.'";s:5:"label";s:22:"Weekend business hours";}s:34:"idaddcfa748f61a070019cd5c2c742897d";a:2:{s:5:"value";s:'.strlen($shop_closing_day).':"'. $shop_closing_day.'";s:5:"label";s:11:"Closing day";}s:34:"id21b2afa873c7e0cd744dfdaa0ab52682";a:2:{s:5:"value";s:'.strlen($shop_to_adult).':"'. $shop_to_adult. '";s:5:"label";s:8:"To adult";}s:34:"id184d44864ce283b00d81222c5c36df8a";a:2:{s:5:"value";s:'.strlen($shop_to_child).':"'. $shop_to_child.'";s:5:"label";s:8:"To child";}'. $shop_to_one;
            // return;
            $shop_data = array(
                'javo_control_options' => '',
                'javo_slider_type' => '',
                'javo_posts_per_page' => '',
                'javo_item_tax' => 's:17:"a:1:{i:0;s:0:"";}";',
                'javo_blog_tax' => '',
                'javo_item_terms' => 's:2:"N;";',
                'javo_blog_terms' => 's:2:"N;";',
                'rating_average' => '',
                'rating_count' => 0,
                '_edit_last' => 1,//検討
    //            '_edit_lock' => '1421071382:1',//検討
                'slide_template' => 'default',
                'javo_this_featured_item' => 'nouse',
                'video' => '',
                'latlng' => $cus_latlng,//地図情報
                'directory_meta' => $cus_directory_meta,//店舗情報
                'detail_images' => 's:2:"N;";',
                'item_map_positon' => 's:6:"a:0:{}";',
                'item_map_type' => 's:6:"a:0:{}";',
                'custom_variables'=>$cus_custom_variables,
            );
            // var_dump($shop_data);
        // return;
            $post_url = 'http://160.16.81.237/test/'. 'school'. '/' . $shop_slug_name. '/';
            $this->javo_doctrine->insert('wp_posts',array(
                    // 'ID' => $id,
                    'post_author' => '1',
                    'post_date' => $date,
                    'post_date_gmt' => $date,
                    'post_content' => '',
                    'post_title' =>  $shop_name,
                    'post_excerpt' => '',
                    'post_status' => 'publish',
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_password' => '',
                    'post_name' => $shop_slug_name,
                    'to_ping' => '',
                    'pinged' => '',
                    'post_modified' => $date,
                    'post_modified_gmt' => $date,
                    'post_content_filtered' => '',
                    'post_parent' => 0,
                    'guid' => $post_url,
                    'post_type' => 'item',
                    'post_mime_type' => ''
            ));

            $post_id = $this->javo_doctrine->fetchArray('SELECT ID FROM wp_posts WHERE post_name = ?', array($shop_slug_name));
            $post_id = $post_id[0];
            var_dump($post_id);
            //カスタムフィールド
            //shop_dataにあるものは全て登録
            foreach ($shop_data as $key => $value) {
                $this->insertJavoCustomField($post_id, $key, $value);
            }

            //location
            $this->javo_doctrine->insert('wp_term_relationships', array(
                    'object_id' => $post_id,
                    'term_taxonomy_id' => $parent_category[0]['term_id']
                ));
            //親カテゴリのカウント数をプラス１
            $now_count = $this->javo_doctrine->fetchArray('SELECT count FROM wp_term_taxonomy WHERE term_id = ?', array($parent_category[0]['term_id']));
            $now_count[0] = (int)$now_count[0] + 1;
            $this->javo_doctrine->update('wp_term_taxonomy', array('count' => $now_count[0]), array('term_id' => $parent_category[0]['term_id']));

            //会社名
            $taxonomy_id = 76;//変更!!
            $this->javo_doctrine->insert('wp_term_relationships', array(
                    'object_id' => $post_id,
                    'term_taxonomy_id' => $taxonomy_id
            ));
            //カウント数をプラス１
            $now_count = $this->javo_doctrine->fetchArray('SELECT count FROM wp_term_taxonomy WHERE term_id = ?', array($taxonomy_id));
            $now_count[0] = (int)$now_count[0] + 1;
            $this->javo_doctrine->update('wp_term_taxonomy', array('count' => $now_count[0]), array('term_id' => $taxonomy_id));
        }
    }

    public function pushArticles()
    {
        $this->initAutoIncrement();
        $date = date(DATE_ATOM);
        //テストデータ
        // $shop_name = 'AEON池袋校';
        // $slug_name = $this->getRubi($shop_name);
        $shop_data = $this->getResouce();
        foreach ($shop_data as $num => $shop) {
             usleep(500000);
            // $slug_name = $this->getRubi($shop_data[$num]['shop_name']);
            $ll = $this->getLocate($shop_data[$num]['address']);
            if($ll !== false){
                $nearStation = $this->getNearStation($ll->lat, $ll->lng);
                $shop_data[$num] += array(
                    'lat' => $ll->lat,
                    'lng' => $ll->lng,
                    'near_station_name' => $nearStation[0]['station_name'],
                    'near_station_distance' => $nearStation[0]['distance']
                    );
            }
            $shop_num = $num +1;
            //ここは入れるごとに変える
            $shop_data[$num] += array(
                    // 'slug_name' => $slug_name,
                    // 'phone_number' => '0120-286-815',
                    'slug_name' => 'winbe'. $shop_num,
                    'to_adult' =>true,
                    'to_child' => true,
                    'to_one' => false,
                    'to_multi' => true
                );
            // continue;
            preg_match('/(東京都|北海道|(?:京都|大阪)府|.{6,9}県)((?:四日市|廿日市|野々市|かすみがうら|つくばみらい|いちき串木野)市|(?:杵島郡大町|余市郡余市|高市郡高取)町|.{3,12}市.{3,12}区|.{3,9}区|.{3,15}市(?=.*市)|.{3,15}市|.{6,27}町(?=.*町)|.{6,27}町|.{9,24}村(?=.*村)|.{9,24}村)(.*)/', $shop_data[$num]['address'], $address_data);

            $parent_category = $this->doctrine->fetchAll('SELECT * FROM wp_terms WHERE name = ?', array($address_data[1]));

            /*子カテゴリは後から
            $child_category_ids = $this->doctrine->fetchAll('SELECT term_id FROM wp_term_taxonomy WHERE parent = ?', array($parent_category[0]['term_id']));

            $search_flag = 0;
            $set_term_id = 0;
            if($child_category_ids){
                foreach ($child_category_ids as $key => $value) {
                    $child_category_names = $this->doctrine->fetchArray('SELECT name FROM wp_terms WHERE term_id = ?', array($value['term_id']));

                    if($child_category_names[0] === $address_data[2]){
                        $set_term_id = (int)$value['term_id'];
                        $search_flag = 1;
                        break;
                    }
                }
            }

            //カテゴリが存在しないときは作成
            if($search_flag === 0){
                //実際は$address_data[2]のルビフリで
                $rubi = $this->getRubi($address_data[2]);
                $create_slug = $rubi. '-'. $parent_category[0]['slug'];

                $this->doctrine->insert('wp_terms', array(
                        'name' => $address_data[2],
                        'slug' => $create_slug
                    ));
                $this_id = $this->doctrine->fetchArray('SELECT term_id FROM wp_terms WHERE name = ?', array($address_data[2]));
                $this->doctrine->insert('wp_term_taxonomy', array(
                        'term_taxonomy_id' => $this_id[0],
                        'term_id' => $this_id[0],
                        'taxonomy' => 'category',
                        'parent' => $parent_category[0]['term_id'],
                        'description' => ''
                    ));
                $set_term_id = (int)$this_id[0];
             }
             */

            //記事データ
            // $post_url = 'http://160.16.81.237/map/?p='. $id;
            // $post_url = 'http://160.16.81.237/map/'. $parent_category[0]['slug']. '/' . $shop_data[$num]['slug_name']. '/';
            // $id = 7 + $num;
            $post_url = 'http://160.16.81.237/map/'. 'school'. '/' . $shop_data[$num]['slug_name']. '/';
            $this->doctrine->insert('wp_posts',array(
                    // 'ID' => $id,
                    'post_author' => '1',
                    'post_date' => $date,
                    'post_date_gmt' => $date,
                    'post_content' => '',
                    'post_title' =>  $shop_data[$num]['shop_name'],
                    'post_excerpt' => '',
                    'post_status' => 'publish',
                    'post_password' => '',
                    'post_name' => $shop_data[$num]['slug_name'],
                    'to_ping' => '',
                    'pinged' => '',
                    'post_modified' => $date,
                    'post_modified_gmt' => $date,
                    'post_content_filtered' => '',
                    'post_parent' => 0,
                    'guid' => $post_url,
                    'post_type' => 'post',
                    'post_mime_type' => ''
                ));

            $post_id = $this->doctrine->fetchArray('SELECT ID FROM wp_posts WHERE post_name = ?', array($shop_data[$num]['slug_name']));
            $id = $post_id[0];
            //レイアウトの設定
            $this->insertCustomField($id, '_responsive_layout', 'default');
            //カスタムフィールド
            //shop_dataにあるものは全て登録
            $shop_data[$num] = array_filter($shop_data[$num], 'strlen');
            foreach ($shop_data[$num] as $key => $value) {
                $this->insertCustomField($id, $key, $value);
            }
            //最寄駅情報
            foreach ($nearStation as $key => $value) {
                $this->insertCustomField($id, 'near_station_line', $value['line']);
            }
            $nearStation = array();

            //記事に親カテゴリを設定
            $this->doctrine->insert('wp_term_relationships', array(
                    'object_id' => $id,
                    'term_taxonomy_id' => $parent_category[0]['term_id']
                ));
            //親カテゴリのカウント数をプラス１
            $now_count = $this->doctrine->fetchArray('SELECT count FROM wp_term_taxonomy WHERE term_id = ?', array($parent_category[0]['term_id']));
            $now_count[0] = (int)$now_count[0] + 1;
            $this->doctrine->update('wp_term_taxonomy', array('count' => $now_count[0]), array('term_id' => $parent_category[0]['term_id']));
            /*
            //子カテゴリを設定
            $this->doctrine->insert('wp_term_relationships', array(
                    'object_id' => $id,
                    'term_taxonomy_id' => $set_term_id
                ));
            //子カテゴリのカウント数をプラス１
            $now_count = $this->doctrine->fetchArray('SELECT count FROM wp_term_taxonomy WHERE term_id = ?', array($set_term_id));
            $now_count[0] = (int)$now_count[0] + 1;
            $this->doctrine->update('wp_term_taxonomy', array('count' => $now_count[0]), array('term_id' => $set_term_id));
*/

/*
            //タグを追加
            $tag_id = 58;
            $this->doctrine->insert('wp_term_relationships', array(
                    'object_id' => $id,
                    'term_taxonomy_id' => $tag_id
                ));
            $now_count = $this->doctrine->fetchArray('SELECT count FROM wp_term_taxonomy WHERE term_id = ?', array($tag_id));
            $now_count[0] = (int)$now_count[0] + 1;
            $this->doctrine->update('wp_term_taxonomy', array('count' => $now_count[0]), array('term_id' => $tag_id));
            */
        }
    }

    //文字列を全てローマ字にして戻す
    public function getRubi($rawstr)
    {
        $appid = 'dj0zaiZpPU1HVW1PUFJCekpKSyZzPWNvbnN1bWVyc2VjcmV0Jng9MDk-';
        // $api = 'http://jlp.yahooapis.jp/JIMService/V1/conversion';
        $api = 'http://jlp.yahooapis.jp/FuriganaService/V1/furigana';
        $sentence = $rawstr;
        $args = array (
            'appid'=>$appid,
            'sentence'=>$sentence,
            );
        $url = $api . '?' . http_build_query($args);
        $ch = curl_init ($url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 900);
        $data =  curl_exec ($ch);
        curl_close ($ch);

        preg_match_all( "/<Word>((\n|.)*?)<\/Word>/i", $data, $words);
        $str = '';
        foreach ($words[1] as $key => $value) {
            preg_match("/<Surface>(.*?)<\/Surface>/i", $value, $raw_word);
                if (preg_match("/^[a-zA-Z]+$/",$raw_word[1])) {
                    $str .= $raw_word[1];
                } else {
                    preg_match( "/<Roman>(.*?)<\/Roman>/i", $value, $rubi_word);
                    if($rubi_word){
                        $str .= $rubi_word[1];
                    }
                }
        }
        return $str;
    }

    //住所からlat,lngを取得
     public function getLocate($address){
        // $appid = 'dj0zaiZpPXJJSDdtbmVOMnoybSZzPWNvbnN1bWVyc2VjcmV0Jng9NTM-';
        // $api = 'http://geo.search.olp.yahooapis.jp/OpenLocalPlatform/V1/geoCoder';
        // $args = array (
        //     'appid'=>$appid,
        //     'query'=>$address,
        //     // 'al' => 2,
        //     'output' => 'json'
        //     );
        // $url = $api . '?' . http_build_query($args);
        $api = 'http://maps.googleapis.com/maps/api/geocode/json';
        $url = $api . '?address='. urlencode($address). '&sensor=false';
        $ch = curl_init ($url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 1000);
        $data =  curl_exec ($ch);
        curl_close ($ch);
        $data = json_decode($data);
        if($data->results){
            $ll = $data->results[0]->geometry->location;
        }else {
            echo $address. ' not get ll !!'. PHP_EOL;
            return false;
        }
        // $ll = $data->Feature[0]->Geometry->Coordinates;
        // $ll = explode(',', $ll);
        return $ll;
     }

     //最寄駅情報を取得
     //引数 緯度,経度
     public function getNearStation($lat, $lng){
        $api = 'http://express.heartrails.com/api/json?method=getStations';
        $url = $api. '&x='. $lng. '&y='. $lat;
        $ch = curl_init ($url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 900);
        $data =  curl_exec ($ch);
        curl_close ($ch);
        $data = json_decode($data);
        $nearDistance = '';
        $nearName ='';
         $station_data = array();
        foreach ($data->response->station as $key => $value) {
            if($key === 0){
                $station_data[] = array(
                    'line' => $value->line,
                    'station_name' => $value->name,
                    'distance' => $value->distance
                );
                $nearDistance = $value->distance;
                $nearName = $value->name;
                continue;
            }
            if($value->distance === $nearDistance){
                if($value->name === $nearName){
                    $station_data[] = array(
                        'line' => $value->line,
                        'station_name' => $value->name,
                        'distance' => $value->distance
                    );
                }
            }
        }
        return $station_data;
     }

     protected function insertCustomField($id, $key, $value)
     {
            $this->doctrine->insert('wp_postmeta', array(
                'post_id' => $id,
                'meta_key' => $key,
                'meta_value' => $value
            ));
     }

    protected function insertJavoCustomField($id, $key, $value)
     {
            $this->javo_doctrine->insert('wp_postmeta', array(
                'post_id' => $id,
                'meta_key' => $key,
                'meta_value' => $value
            ));
     }

}
