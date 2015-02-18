<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    const DEFAULT_PAGE_NUMBER = "1"; // because we're using ctype_digit() to check the value
    const NB_ARTICLES_PER_PAGE = 3;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get("page", DefaultController::DEFAULT_PAGE_NUMBER);

        if (! ctype_digit($page)) {
            throw $this->createNotFoundException("La page demandée n'existe pas"); // 404 - la page n'existe pas
        }

        if($page < 1) {
            throw $this->createNotFoundException("La page demandée n'existe pas");
        }

        $repository = $this->getDoctrine()->getRepository("AppBundle:Article"); //ou alors $this->getDoctrine()->getRepository("AppBundle\Entity\Article");

        // Pagination
        $articlesCount = $repository->countAll();
        $pageMax = ceil($articlesCount / DefaultController::NB_ARTICLES_PER_PAGE);

        if($page > $pageMax) {
            throw $this->createNotFoundException("La page demandée n'existe pas");
        }

        $offset = ($page - 1) * DefaultController::NB_ARTICLES_PER_PAGE;
        $articles = $repository->findByOffsetAndLimitWithAuthor($offset, DefaultController::NB_ARTICLES_PER_PAGE);

        return $this->render('default/index.html.twig',
            [
                "articles" => $articles,
                "page"  => $page,
                "pageMax" => $pageMax
            ]);
    }

    /**
     * @Route("/about")
     */
    public function aboutAction()
    {
        return $this->render('default/about.html.twig');
//      return new Response('<html><header></header></html></hml><h1>Coucou</h1>', 200, ['Content-Type' => 'text/html']);
//      return new Response('{ "key": "value"', 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/contact")
     */
    public function contactAction()
    {
        return $this->render('default/contact.html.twig');
    }
}