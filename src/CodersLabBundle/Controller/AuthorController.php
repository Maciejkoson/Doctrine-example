<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/author")
 */
class AuthorController extends Controller
{
    /**
     * @Route("/new")
     * @Template()
     */
    public function newAction()
    {
        return [];
    }

    /**
     * @Route("/create")
     */
    public function createAction(Request $request)
    {
        $author = new Author();

        $author->setFirstName($request->request->get('firstName'));
        $author->setLastName($request->request->get('lastName'));
        $author->setDescription($request->request->get('description'));

        $em = $this->getDoctrine()->getManager();

        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('coderslab_author_showall');
    }

    /**
     * @Route("/")
     * @Template()
     */
    public function showAllAction()
    {
        $authors = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Author')
            ->findAll();

        return [
            'authors' => $authors
        ];
    }
}
