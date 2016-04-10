<?php

namespace CedricZiel\Blog\Controller;

use CedricZiel\Blog\Service\PostService;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class HomepageController
{
    /**
     * @var Twig_Environment
     */
    protected $view;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * @param Twig_Environment $twig
     * @param PostService $postService
     */
    public function __construct(Twig_Environment $twig, PostService $postService)
    {
        $this->view = $twig;
        $this->postService = $postService;
    }

    /**
     * Displays the Homepage
     *
     * @return Response
     */
    public function indexAction()
    {
        $posts = $this->postService->findLatestPosts();

        return new Response(
            $this->view->render(
                'Homepage/index.html.twig',
                [
                    'posts' => $posts,
                ]
            )
        );
    }
}
