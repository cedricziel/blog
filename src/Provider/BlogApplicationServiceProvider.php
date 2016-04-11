<?php

namespace CedricZiel\Blog\Provider;

use Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapBadgeExtension;
use Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapFormExtension;
use Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapIconExtension;
use Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapLabelExtension;
use CedricZiel\Blog\Controller\Admin\PostController as AdminPostController;
use CedricZiel\Blog\Controller\AdminController;
use CedricZiel\Blog\Controller\HomepageController;
use CedricZiel\Blog\Service\PostService;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\ServiceProviderInterface;

/**
 * @package CedricZiel\Blog\Provider
 */
class BlogApplicationServiceProvider implements ServiceProviderInterface
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
        $this->registerLogger($app);
        $this->registerTemplating($app);

        $app->register(new UrlGeneratorServiceProvider());
        $app->register(new FormServiceProvider());
        $app->register(new TranslationServiceProvider(), ['locale_fallbacks' => ['en']]);
        $app->register(new ValidatorServiceProvider());

        $app->register(new DatastoreServiceProvider());
        $app->register(new ServiceControllerServiceProvider());

        // Register complimentary services
        $this->buildContainer($app);

        $this->addRoutes($app);
    }

    /**
     * Uses Monolog for logging.
     * Registers a syslog log handler
     *
     * @param Application $app
     */
    protected function registerLogger(Application $app)
    {
        $app->register(
            new MonologServiceProvider(),
            [
                'monolog.logfile' => __DIR__.'/../var/logs/development.log',
            ]
        );

        /**
         * Override all logging handlers
         */
        $app['monolog'] = $app->share(
            $app->extend(
                'monolog',
                function ($monolog, $app) {
                    /** @var Logger $monolog */
                    $monolog->setHandlers([new SyslogHandler('app')]);

                    return $monolog;
                }
            )
        );
    }

    /**
     * @param Application $app
     */
    protected function registerTemplating(Application $app)
    {
        $app->register(
            new TwigServiceProvider(),
            [
                'twig.path' => [
                    __DIR__.'/../../views',
                    __DIR__.'/../../vendor/braincrafted/bootstrap-bundle/Braincrafted/Bundle/BootstrapBundle/Resources/views/Form',
                ],
            ]
        );

        $app['twig'] = $app->share(
            $app->extend(
                'twig',
                function ($twig) {
                    /** @var \Twig_Environment $twig */
                    $twig->addExtension(new BootstrapIconExtension('fa'));
                    $twig->addExtension(new BootstrapLabelExtension);
                    $twig->addExtension(new BootstrapBadgeExtension);
                    $twig->addExtension(new BootstrapFormExtension);

                    return $twig;
                }
            )
        );

        $app['twig.loader.filesystem']->prependPath(
            __DIR__.'/../../vendor/braincrafted/bootstrap-bundle/Braincrafted/Bundle/BootstrapBundle/Resources/views/Form'
        );
    }

    /**
     * @param Application $app
     */
    protected function buildContainer(Application $app)
    {
        $app['post.service'] = $app->share(
            function () use ($app) {
                return new PostService($app['store.post']);
            }
        );
        $app['admin.controller'] = $app->share(
            function () use ($app) {
                $controller = new AdminController($app['twig'], $app['post.service']);
                $controller->setApplication($app);
                $controller->setTwig($app['twig']);

                return $controller;
            }
        );
        $app['admin.post.controller'] = $app->share(
            function () use ($app) {
                $controller = new AdminPostController($app['post.service']);
                $controller->setApplication($app);
                $controller->setTwig($app['twig']);

                return $controller;
            }
        );
        $app['homepage.controller'] = $app->share(
            function () use ($app) {
                return new HomepageController($app['twig'], $app['post.service']);
            }
        );
    }

    /**
     * Attaches the routes to the application router.
     *
     * @param Application $app
     */
    protected function addRoutes(Application $app)
    {
        $app->post('/admin', 'admin.controller:indexAction')->bind('admin.index');
        $app->get('/admin', 'admin.controller:indexAction')->bind('admin.index');
        $app->get('/admin/env', 'admin.controller:envAction')->bind('admin.env');
        $app->post('/admin/post', 'admin.post.controller:indexAction')->bind('admin.post.index');
        $app->get('/admin/post', 'admin.post.controller:indexAction')->bind('admin.post.index');
        $app->post('/admin/post/new', 'admin.post.controller:newAction')->bind('admin.post.new');
        $app->get('/admin/post/new', 'admin.post.controller:newAction')->bind('admin.post.new');
        $app->get('/', 'homepage.controller:indexAction')->bind('homepage');
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        // noop
    }
}
