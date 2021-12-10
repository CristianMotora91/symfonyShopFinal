<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Product1Type;
use App\Form\ProductType;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Vendor;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use App\Entity\Image;

/**
 * @Route("/crud/user")
 */
class CrudUserController extends AbstractController
{
    /**
     * @Route("/", name="crud_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, ProductRepository $productRepository): Response
    {
        return $this->render('crud_user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'vendors'=>$vendorRepository->findAll(),
            'products'=>$productRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="crud_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, ProductRepository $productRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('crud_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'categories' => $categoryRepository->findAll(),
            'vendors'=>$vendorRepository->findAll(),
            'products'=>$productRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="crud_user_show", methods={"GET"})
     */
    public function show(User $user, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, ProductRepository $productRepository): Response
    {
        return $this->render('crud_user/show.html.twig', [
            'user' => $user,
            'categories' => $categoryRepository->findAll(),
            'vendors'=>$vendorRepository->findAll(),
            'products'=>$productRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, VendorRepository $vendorRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'categories' => $categoryRepository->findAll(),
            'vendors'=>$vendorRepository->findAll()

        ]);
    }

    /**
     * @Route("/{id}", name="crud_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
