<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\FormTypeInterface;

use App\Entity\Upload;
use App\Form\UploadType;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;

use App\Repository\UserRepository;
use App\Repository\ArticleRepository;



class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     * 
     */
    public function index(ArticleRepository $repo)
    {
        //Grace au systeme d'injection de dépendance le repo des articles n'a plus besoin de la ligne ci-dessous
        // Repo qui sert à attraper les articles
        //$repo = $this->getDoctrine()->getRepository(Article::class);

        //
        // trouve tout les articles
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
            
        ]);

        
//        dump($articles); die;

    }

    /**
     * @Route("/", name="home")
     */
    public function home(Request $request)

    {
        // return $this->render('blog/home.html.twig', [
        //     'title' => 'Blog CSL Training',
        //     'age' => '30',
        //     'browser' => 'nameBrowser'
        // ]);
       
        $upload = new Upload();
        $form = $this->createForm(UploadType::class, $upload);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $upload->getName();
            
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move($this->getParameter('upload_directory'), $fileName);

            $upload->setName($fileName);

            return $this->redirectToRoute('home');
        }
        // renvoie le fchier twig home.html.twig
        return $this->render('blog/home.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Blog CSL Training',
            'age' => '30',
            'browser' => 'nameBrowser'
        ));
    
    }

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager)
    {
        if(!$article) {
            $article = new Article();
        }
        dump($request);
        //$article = new Article();
        // $article->setTitle("Titre de l'article")
        //         ->setContent("Le contenu de l'article");
        //On creer un form avec l'entity article
        /*$form = $this->createFormBuilder($article)
                     ->add('title', TextType::class, [
                         'attr' => [
                             'placeholder' => "Ttre de l'article"]])
                     ->add('content', TextareaType::class, [
                         'attr' => [
                             'placeholder' => "Contenu de l'article"]])
                     ->add('image', TextType::class, [
                         'attr' => [
                             'placeholder' => "Image de l'article"]])
                     ->add('save', SubmitType::class, [
                         'label' => 'enregistrer'])
                     ->getForm();
        */
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
    //    dump($article);
        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());

            }
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
       /* if($request->request->count() > 0){
            $article = new Article();
            $article->setTitle($request->request->get('title'))
                ->setContent($request->request->get('content'))
                ->setImage($request->request->get('image'))
                ->setCreatedAt(new \DateTime());

            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show', ['id' =>$article->getId()]);
        }*/
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article, Request $request, ObjectManager $manager)
    {
        //Grace au ParamConverter il va chercher l'article grace à l'id
        //public function show(ArticleRepository $repo, $id){}
        ///blog/article/{id} route parametre avec id et repo de Article
        //$repo = $this->getDoctrine()->getRepository(Article::class);

        // trouve les articles avec leur id
        //$article = $repo->find($id);
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm'=> $form->createView()
        ]);
    }

    /**
     * @Route("/blog/produit", name="produit")
     */
    public function produit()
    {
        return $this->render('blog/produit.html.twig', [
            'title' => 'Blog CSL Training'
        ]);
    }

    /**
     * @Route("/account", name="account")
     * 
     */
    public function account(UserRepository $repo)
    {
        $user = $repo->findAll();

        return $this->render('/account.html.twig', [
            'users' => $user
        ]);
    }

    /**
     * @Route("/about", name="about")
     * @return Response
     */
    public function about(): Response
    {
        return $this->render('pages/about.html.twig');
    }

    /**
     * @Route("/infos", name="infos")
     * @return Response
     */
    public function infos(): Response
    {
        return $this->render('pages/infos.html.twig');
      //return new Response('infos');
    }

     /**
     * @Route("/contact", name="contact")
     * @return Response
     */
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
      //return new Response('contact');
    }
    
    /**
     * @Route("/faq", name="faq")
     * @return Response
     */
    public function faq(): Response
    {
        return $this->render('pages/faq.html.twig');
      //return new Response('faq');
    }

    /**
     * @Route("/privacy", name="privacy")
     * @return Response
     */
    public function privacy(): Response
    {
        return $this->render('pages/privacy.html.twig');
      //return new Response('privacy');
    }

        /**
     * @Route("/terms", name="terms")
     * @return Response
     */
    public function terms(): Response
    {
        return $this->render('pages/terms.html.twig');
      //return new Response('terms');
    }

}
