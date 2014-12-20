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

    public function testdb(){
        $dbsql = $this->doctrine->fetchAll('SELECT * FROM wp_terms');
        var_dump($dbsql);
    }
}
