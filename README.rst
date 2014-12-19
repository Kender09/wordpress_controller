wordpress controller
==============

silex console app

メモ

<?php
namespace App\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;

use App\DataAccesses;

use Predis\Client as Predis;

class DataAccessServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
                $app['predis.client'] = $app->share(function() use($app) {
                        return new Predis();
                });
                $app['dataaccess.predis'] = $app->share(function() use($app) {
                        return new DataAccesses\PredisHandler(
                                $app['predis.client'],
                                $app['dbs']['mysql_write']
                        );
                });
    }

    public function boot(Application $app)
    {
    }

}

app.php

use App\Providers\DataAccessServiceProvider;

$app->register(new DataAccessServiceProvider());


PredisHandler.php

<?php
namespace App\DataAccesses;

use Predis\Client as Predis;

class PredisHandler
{
        protected $ESKEY = 'latest_ES';
        protected $ESDEVKEY = 'dev_latest_ES';

        public function __construct(Predis $predis) {
                $this->predis = $predis;
        }

        public function getLatestES() {
                $ret = $this->predis->get($this->ESKEY);

                return $ret;
        }

        public function getLatestDevES() {
                $ret = $this->predis->get($this->ESDEVKEY);

                return $ret;
        }

}

