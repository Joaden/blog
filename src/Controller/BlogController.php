<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     * 
     */
    public function index(ArticleRepository $repo)
    {
        //Grace au systeme de dinjection de dependance le repo das articles plus besoin de la ligne ci-dessous
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
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Blog CSL Training',
            'age' => '30',
            'browser' => 'nameBrowser'
        ]);
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
        //dump($request);
        //$article = new Article();

        // $article->setTitle("Titre de l'article")
        //         ->setContent("Le contenu de l'article");

        /*$form = $this->createFormBuilder($article)
                     ->add('title')
                     ->add('content')
                     ->add('image')
                     ->getForm();
        */
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

    //    dump($article);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());

            }
            //$article->setCreatedAt(new \DateTime());

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
    public function show(Article $article)
    {
        //public function show(ArticleRepository $repo, $id){}
        ///blog/article/{id} route parametre avec id et repo de Article
        //$repo = $this->getDoctrine()->getRepository(Article::class);

        // trouve les articles avec leur id
        //$article = $repo->find($id);

        return $this->render('blog/show.html.twig', [
            'article' => $article
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


}
