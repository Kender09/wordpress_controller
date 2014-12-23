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
            $app['dbs']['mysql_read']->insert('wp_terms', array(
                    'term_id' => $val['id'],
                    'name' => $val['name'],
                    'slug' => $val['slug']
                ));
            $app['dbs']['mysql_read']->insert('wp_term_taxonomy', array(
                    'term_taxonomy_id' => $val['id'],
                    'term_id' => $val['id'],
                    'taxonomy' => 'category',
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

return $console;
