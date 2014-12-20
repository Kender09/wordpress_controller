<?php
namespace App\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;

use App\DataAccesses;

class DataAccessServiceProvider implements ServiceProviderInterface {
    public function register(Application $app)
    {
        $app['dataaccess.wpdb'] = $app->share(function() use($app) {
            return new DataAccesses\WpdbHandler(
                $app['dbs']['mysql_read']
            );
        });
    }

    public function boot(Application $app)
    {
    }
}
