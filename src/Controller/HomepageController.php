<?php

namespace CedricZiel\Blog\Controller;

use CedricZiel\Blog\Service\PostService;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package CedricZiel\Blog\Controller
 */
class HomepageController extends AbstractController
{
    /**
     * @var PostService
     */
    private $postService;

    /**
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
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

        return $this->render(
            'Homepage/index.html.twig',
            [
                'posts' => $posts,
            ]
        );
    }
}
