<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/book")
 */
class BookController extends Controller
{
    /**
     * @Route("/new")
     * @Template("CodersLabBundle:Book:bookForm.html.twig")
     */
    public function newAction()
    {
        $authors = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Author')
            ->findAll();

        return [
            'book' => new Book(),
            'authors' => $authors,
            'url' => $this->generateUrl('coderslab_book_create')
        ];
    }

    /**
     * @Route("/create")
     * @Template("CodersLabBundle:Book:book.html.twig")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $author = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Author')
            ->find($request->request->get('author'));

        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }

        $book = new Book();

        $book->setName($request->request->get('name'));
        $book->setDescription($request->request->get('description'));
        $book->setRating($request->request->get('rating'));

        $book->setAuthor($author);
        $author->addBook($book);

        $em->persist($book);
        $em->flush();

        return ['book' => $book];
    }

    /**
     * @Route("/show/{id}")
     * @Template()
     */
    public function showAction($id)
    {
        $book = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Book')
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return ['book' => $book];
    }

    /**
     * @Route("/")
     * @Template()
     */
    public function showAllAction()
    {
        $books = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Book')
            ->findAll();

        return ['books' => $books];
    }

    /**
     * @Route("/edit/{id}")
     * @Template()
     */
    public function editAction($id)
    {
        $book = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Book')
            ->find($id);

        $authors = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Author')
            ->findAll();

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return [
            'book' => $book,
            'authors' => $authors,
            'url' => $this->generateUrl('coderslab_book_update', ['id' => $id])
        ];
    }

    /**
     * @Route("/update/{id}")
     * @Template()
     * @Method("POST")
     */
    public function updateAction(Request $request, $id)
    {
        $book = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Book')
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $author = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Author')
            ->find($request->request->get('author'));

        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }

        $book->setName($request->request->get('name'));
        $book->setDescription($request->request->get('description'));
        $book->setRating($request->request->get('rating'));

        $book->getAuthor()->removeBook($book);
        $book->setAuthor($author);
        $author->addBook($book);

        $this
            ->getDoctrine()
            ->getManager()
            ->flush();

        return [
            'book' => $book
        ];
    }

    /**
     * @Route("/delete/{id}")
     */
    public function deleteAction($id)
    {
        $book = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Book')
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('coderslab_book_showall');
    }

    /**
     * @Route("/idGreater/{id}")
     * @Template("CodersLabBundle:Book:showAll.html.twig")
     */
    public function idGreaterAction($id)
    {
        return [
            'books' => $this
                ->getDoctrine()
                ->getRepository('CodersLabBundle:Book')
                ->findByIdGreaterThan($id)
        ];
    }

    /**
     * @Route("/ratingGreater/{rating}")
     * @Template("CodersLabBundle:Book:showAll.html.twig")
     */
    public function ratingGreaterAction($rating)
    {
        return [
            'books' => $this
                ->getDoctrine()
                ->getRepository('CodersLabBundle:Book')
                ->findByRatingGreaterThan($rating)
        ];
    }

    /**
     * @Route("/title/{title}")
     * @Template("CodersLabBundle:Book:showAll.html.twig")
     */
    public function titleAction($title)
    {
        return [
            'books' => $this
                ->getDoctrine()
                ->getRepository('CodersLabBundle:Book')
                ->findByName($title)
        ];
    }
}
