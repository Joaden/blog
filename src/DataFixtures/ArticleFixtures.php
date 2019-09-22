<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        //Créer 3 categories fakées
        // pour chaque categories je donne les infos, et article puis commentaire
        for($i = 1;$i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
                     
            $manager->persist($category);

            // Créer en 4 et 6 articles
            for($j = 1; $j <= mt_rand(4, 6); $j++){
                $article = new Article();

                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);

                for($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                    //$now= new \DateTime();
                    //$interval = $now->diff($article->getCreatedAt());
                    //$days = $interval->days;
                    $days= (new \DateTime())->diff($article->getCreatedAt())->days;

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreateAtd($faker->dateTimeBetween('-' . $days . 'days'))
                            ->setArticle($article);

                    $manager->persist($comment);

                }
            }
        }
        // $product = new Product();
        // $manager->persist($product);
        //Créer 10 articles
        /*for($i = 1; $i <=10; $i++){
            $article = new Article();
            $article->setTitle("Titre de l'article n°$i")
                    ->setContent("<p>Contenu de l'article n°$i</p>")
                    ->setImage("http://placeholder.it/350x150")
                    ->setCreatedAt(new \DateTime());
                    // Propriété de type DateTime donc Objet Datetime, antislash car namespace
            $manager->persist($article);
        } */
        $manager->flush();
    }
}