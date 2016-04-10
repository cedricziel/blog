<?php

namespace CedricZiel\Blog\Provider;

use CedricZiel\Blog\Entity\Post;
use GDS\Schema;
use GDS\Store;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * @package CedricZiel\Blog\Provider
 */
class DatastoreServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['store.post'] = $app->share(
            function () {
                $postSchema = (new Schema('Post'))
                    ->addString('title')
                    ->addString('body')
                    ->addBoolean('draft')
                    ->addDatetime('created_at')
                    ->addDatetime('updated_at')
                    ->addDatetime('published_at');

                $store = new Store($postSchema);
                $store->setEntityClass(Post::class);

                return $store;
            }
        );
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     * @param Application $app
     */
    public function boot(Application $app)
    {
        // noop
    }
}
