<?php

namespace CedricZiel\Blog\Controller\Admin;

use CedricZiel\Blog\Api\ApplicationAwareInterface;
use CedricZiel\Blog\Entity\Post;
use CedricZiel\Blog\Service\PostService;
use CedricZiel\Blog\Traits\ApplicationAwareTrait;
use CedricZiel\Blog\Traits\TwigAwareTrait;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
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
     *
     * @return Form
     */
    protected function createPostForm($post)
    {
        /** @var FormFactory $formFactory */
        $formFactory = $this->application['form.factory'];

        /** @var FormBuilder $formBuilder */
        $formBuilder = $formFactory
            ->createBuilder(FormType::class, $post)
            ->add('title')
            ->add(
                'draft',
                ChoiceType::class,
                [
                    'choices'  => array(
                        'Yes' => true,
                        'No' => false,
                    ),
                ]
            )
            ->add(
                'created_at',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'html5' => true,
                ]
            )
            ->add(
                'updated_at',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'html5' => true,
                ]
            )
            ->add(
                'published_at',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'html5' => true,
                ]
            )
            ->add('body', TextareaType::class)
            ->add('submit', SubmitType::class);

        $formBuilder->get('created_at')->addModelTransformer(
            new CallbackTransformer(
                function ($originalCreatedAt) {

                    return \DateTime::createFromFormat('Y-m-d H:i:s', $originalCreatedAt);
                },
                function ($submittedCreatedAt) {
                    return $submittedCreatedAt;
                }
            )
        );
        $formBuilder->get('updated_at')->addModelTransformer(
            new CallbackTransformer(
                function ($originalCreatedAt) {

                    return \DateTime::createFromFormat('Y-m-d H:i:s', $originalCreatedAt);
                },
                function ($submittedCreatedAt) {
                    return $submittedCreatedAt;
                }
            )
        );
        $formBuilder->get('published_at')->addModelTransformer(
            new CallbackTransformer(
                function ($originalCreatedAt) {

                    return \DateTime::createFromFormat('Y-m-d H:i:s', $originalCreatedAt);
                },
                function ($submittedCreatedAt) {
                    return $submittedCreatedAt;
                }
            )
        );

        /** @var Form $postForm */
        $postForm = $formBuilder->getForm();

        return $postForm;
    }

    /**
     * @param Request $request
     * @param int $postId
     *
     * @return Response
     */
    public function editAction(Request $request, $postId)
    {
        $post = $this->postService->findOneById($postId);
        $form = $this->createPostForm($post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);

            return new RedirectResponse($this->application->path('admin.post.index'));
        }

        return new Response(
            $this->view->render(
                'Admin/Post/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'post' => $post,
                ]
            )
        );
    }
}
