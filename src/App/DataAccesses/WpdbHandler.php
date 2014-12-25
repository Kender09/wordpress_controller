<?php
namespace App\DataAccesses;

use Doctrine\DBAL\Connection;

class WpdbHandler
{
    protected $doctrine;

    public function __construct(Connection $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function pushArticles()
    {
        $date = date(DATE_ATOM);

        //テストデータ
        // $shop_name = 'AEON池袋校';
        // $slug_name = $this->getRubi($shop_name);
        $shop_data = array(
                'shop_name' => 'AEON池袋校',
                'slug_name' => 'aeon_ikebukuro',
                // 'slug_name' => $slug_name,
                'phone_number' => '0800-111-1111',
                'address' => '東京都豊島区東池袋'
            );

        preg_match('/(東京都|北海道|(?:京都|大阪)府|.{6,9}県)((?:四日市|廿日市|野々市|かすみがうら|つくばみらい|いちき串木野)市|(?:杵島郡大町|余市郡余市|高市郡高取)町|.{3,12}市.{3,12}区|.{3,9}区|.{3,15}市(?=.*市)|.{3,15}市|.{6,27}町(?=.*町)|.{6,27}町|.{9,24}村(?=.*村)|.{9,24}村)(.*)/', $shop_data['address'], $adress_data);
        $parent_category = $this->doctrine->fetchAll('SELECT * FROM wp_terms WHERE name = ?', array($adress_data[1]));
        $child_category_ids = $this->doctrine->fetchAll('SELECT term_id FROM wp_term_taxonomy WHERE parent = ?', array($parent_category[0]['term_id']));

        $search_flag = 0;
        $set_term_id = 0;
        if($child_category_ids){
            foreach ($child_category_ids as $key => $value) {
                $child_category_names = $this->doctrine->fetchArray('SELECT name FROM wp_terms WHERE term_id = ?', array($value['term_id']));

                if($child_category_names[0] === $adress_data[2]){
                    $set_term_id = (int)$value['term_id'];
                    $search_flag = 1;
                    echo 'match'. PHP_EOL;
                    break;
                }
            }
        }

        //カテゴリが存在しないときは作成
        if($search_flag === 0){
            //実際は$adress_data[2]のルビフリで
            // $rubi = $this->getRubi($adress_data[2]);
            // $create_slug = $rubi. '-'. $parent_category[0]['slug'];
            $create_slug = 'toyoshimaku-'. $parent_category[0]['slug'];

            $this->doctrine->insert('wp_terms', array(
                    'name' => $adress_data[2],
                    'slug' => $create_slug
                ));
            $this_id = $this->doctrine->fetchArray('SELECT term_id FROM wp_terms WHERE name = ?', array($adress_data[2]));
            $this->doctrine->insert('wp_term_taxonomy', array(
                    'term_taxonomy_id' => $this_id[0],
                    'term_id' => $this_id[0],
                    'taxonomy' => 'category',
                    'parent' => $parent_category[0]['term_id'],
                    'description' => ''
                ));
            $set_term_id = (int)$this_id[0];
         }


        $id = 3;
        //記事データ
        $post_url = 'http://160.16.81.237/map/?p='. $id;
        $this->doctrine->insert('wp_posts',array(
                'ID' => $id,
                'post_author' => '1',
                'post_date' => $date,
                'post_date_gmt' => $date,
                'post_content' => '',
                'post_title' =>  $shop_data['shop_name'],
                'post_excerpt' => '',
                'post_status' => 'publish',
                'post_password' => '',
                'post_name' => $shop_data['slug_name'],
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

        //レイアウトの設定
        $this->doctrine->insert('wp_postmeta', array(
                'post_id' => $id,
                'meta_key' => '_responsive_layout',
                'meta_value' => 'default'
            ));
        //カスタムフィールド
        $this->doctrine->insert('wp_postmeta', array(
                'post_id' => $id,
                'meta_key' => 'shop_name',
                'meta_value' => $shop_data['shop_name']
            ));
        $this->doctrine->insert('wp_postmeta', array(
                'post_id' => $id,
                'meta_key' => 'phone_number',
                'meta_value' => $shop_data['phone_number']
            ));
            $this->doctrine->insert('wp_postmeta', array(
                'post_id' => $id,
                'meta_key' => 'address',
                'meta_value' => $shop_data['address']
            ));

        //記事に親カテゴリを設定
        $this->doctrine->insert('wp_term_relationships', array(
                'object_id' => $id,
                'term_taxonomy_id' => $parent_category[0]['term_id']
            ));
        //親カテゴリのカウント数をプラス１
        $now_count = $this->doctrine->fetchArray('SELECT count FROM wp_term_taxonomy WHERE term_id = ?', array($parent_category[0]['term_id']));
        $now_count[0] = (int)$now_count[0] + 1;
        $this->doctrine->update('wp_term_taxonomy', array('count' => $now_count[0]), array('term_id' => $parent_category[0]['term_id']));
        //子カテゴリを設定
        $this->doctrine->insert('wp_term_relationships', array(
                'object_id' => $id,
                'term_taxonomy_id' => $set_term_id
            ));
        //子カテゴリのカウント数をプラス１
        $now_count = $this->doctrine->fetchArray('SELECT count FROM wp_term_taxonomy WHERE term_id = ?', array($set_term_id));
        $now_count[0] = (int)$now_count[0] + 1;
        $this->doctrine->update('wp_term_taxonomy', array('count' => $now_count[0]), array('term_id' => $set_term_id));
    }

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
                    $str .= $rubi_word[1];
                }
        }
        return $str;
    }

}
