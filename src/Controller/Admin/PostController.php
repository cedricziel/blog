<?php

namespace CedricZiel\Blog\Controller\Admin;

use CedricZiel\Blog\Api\ApplicationAwareInterface;
use CedricZiel\Blog\Entity\Post;
use CedricZiel\Blog\Service\PostService;
use CedricZiel\Blog\Traits\ApplicationAwareTrait;
use CedricZiel\Blog\Traits\TwigAwareTrait;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Manages Posts in the store
 *
 * @package CedricZiel\Blog\Controller\Admin
 */
class PostController implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;
    use TwigAwareTrait;

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
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $posts = $this->postService->findLatestPosts();

        return new Response(
            $this->view->render(
                'Admin/Post/index.html.twig',
                [
                    'posts' => $posts,
                ]
            )
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $post = new Post;
        $postForm = $this->createPostForm($post);

        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post = $postForm->getData();
            $this->postService->save($post);

            return new RedirectResponse($this->application->path('admin.post.index'));
        }

        return new Response(
            $this->view->render(
                'Admin/Post/new.html.twig',
                [
                    'postForm' => $postForm->createView(),
                ]
            )
        );
    }


    /**
     * @param Post $post
     * @return Form
     */
    protected function createPostForm($post)
    {
        /** @var FormFactory $formFactory */
        $formFactory = $this->application['form.factory'];
        /** @var Form $postForm */
        $postForm = $formFactory
            ->createBuilder(FormType::class, $post)
            ->add('title')
            ->add('draft', CheckboxType::class)
            ->add('created_at', DateTimeType::class)
            ->add('updated_at', DateTimeType::class)
            ->add('published_at', DateTimeType::class)
            ->add('body', TextareaType::class)
            ->add('submit', SubmitType::class)
            ->setAction($this->application->path('admin.index'))
            ->getForm();

        return $postForm;
    }
}
