<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Silex\Provider\DoctrineServiceProvider;

$console = new Application('My Silex Application', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('setTodouhukenCate')
    ->setDefinition(array(
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('都道府県をカテゴリに設定')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $file = file_get_contents('/home/t_o/works/silex/resource/todouhukenn.txt', FILE_USE_INCLUDE_PATH);
        $file = preg_replace('/　/', ' ', $file);//全角スペースを半角スペースへ
        $file = preg_replace('/\s+/', ' ', $file);//連続する半角スペースを1つの半角スペースへ
        $file = explode(" ", $file);
        array_pop($file);
        $push_arr = array();
        foreach($file as $key => $value){
            if($key%3 === 0){
                $push_arr[(int)$key/3]['id'] = $value;
            }
            if($key%3 === 1){
                $push_arr[(int)$key/3]['name'] = $value;
            }
            if($key%3 === 2){
                $push_arr[(int)$key/3]['slug'] = $value;
            }
        }
        //$dbsql = $app['dbs']['mysql_read']->fetchAll('SELECT * FROM wp_terms');
        //var_dump($dbsql);
        foreach($push_arr as $key => $val){
            $id = 20 + (int)$val['id'];
            $app['dbs']['mysql_write']->insert('wp_terms', array(
                    'term_id' => $id,
                    'name' => $val['name'],
                    'slug' => $val['slug']
                ));
            $app['dbs']['mysql_write']->insert('wp_term_taxonomy', array(
                    'term_taxonomy_id' => $id,
                    'term_id' => $id,
                    'taxonomy' => 'item_location',
                    'description' => ''
                ));
        }
    });

$console
    ->register('pushCustomArticles')
    ->setDefinition(array(
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('カスタムフィールドを利用した記事の追加')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $app['dataaccess.wpdb']->pushArticles();
    });

$console
    ->register('pushJavo')
    ->setDefinition(array(
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('カスタムフィールドを利用した記事の追加')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $app['dataaccess.wpdb']->pushJavo();
    });

$console
    ->register('test')
    ->setDefinition(array(
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('test')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $str = "長野県諏訪郡下諏訪町西四王5006-3 SKSビル2階";
        $ll = $app['dataaccess.wpdb']->getLocate($str);
        var_dump($ll);

        // $return = $app['dataaccess.wpdb']->getResouce();
        // var_dump($return);
        // $app['dataaccess.wpdb']->initAutoIncrement();
        // $nearStation = $app['dataaccess.wpdb']->getNearStation($ll->lat, $ll->lng);
        // var_dump($nearStation);
        // $return = $app['dataaccess.wpdb']->getRubi('Hello!s岐阜本部校');
    });

return $console;
