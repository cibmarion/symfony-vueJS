<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SessionControlleur extends AbstractController
{
    /**
     * @Route("/", name="article-index")
     */
    public function index(ArticleRepository $ar)
    {
        return $this->render('article/index.html.twig',[
            'articles' => $ar->findBy([], ['publishedAt' => 'ASC'])
            ]);
    }

    /**
     * @Route("/article/{id}", name="article-detail")
     * @ParamConverter("article", class="App\Entity\Article")
     */
    public function detail(Article $article)
    {
        return $this->render('article/detail.html.twig', [
            'article' => $article
        ]);
    }
}
