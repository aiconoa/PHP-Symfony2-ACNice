<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\Type\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ArticleController extends Controller
{
    /**
     * @Route("/show/{id}", requirements={"id" = "\d+"})
     */
    public function showAction($id)
    {
        $repository = $this->getDoctrine()->getRepository("AppBundle:Article");

        $article = $repository->find($id);

        if($article == null) {
            throw $this->createNotFoundException("L'article demandé n'existe pas");
        }

        return $this->render('article/show.html.twig', ["article" => $article]);
    }

    /**
     * @Route("/create")
     */
    public function createAction(Request $request) {
        $article = new Article();

        $form = $this->createForm(new ArticleType(), $article);

        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManagerForClass("AppBundle:Article");
            $article->setAuthor($this->getUser());
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_default_index');
        }

        return $this->render('article/create.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function editAction(Request $request, $id) {

        $article = $this->getDoctrine()->getRepository("AppBundle:Article")->find($id);

        if($article == null) {
            return $this->createNotFoundException("L'article demandé n'existe pas");
        }

        if (false === $this->get('security.authorization_checker')->isGranted('edit', $article)) {
            throw $this->createAccessDeniedException('Unauthorised access!');
            //throw new AccessDeniedException('Unauthorised access!');
        }

        $form = $this->createForm(new ArticleType(), $article);

        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManagerForClass("AppBundle:Article");
            $em->merge($article);
            $em->flush();

            return $this->redirectToRoute('app_default_index');
        }

        return $this->render('article/edit.html.twig', ["form" => $form->createView()]);
    }

}