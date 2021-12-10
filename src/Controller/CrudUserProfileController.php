<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/crud/userProfile")
 */
class CrudUserProfileController extends AbstractController
{
    /**
     * @Route("/", name="crud_user_profile_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, ProductRepository $productRepository): Response
    {
        return $this->render('crud_user_profile/index.html.twig', [
            'users' => $userRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'vendors'=>$vendorRepository->findAll(),
            'products'=>$productRepository->findAll()
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="crud_user_profile_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, ProductRepository $productRepository): Response
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('crud_user_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_user_profile/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'categories' => $categoryRepository->findAll(),
            'vendors'=>$vendorRepository->findAll(),
            'products'=>$productRepository->findAll()
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="crud_user_profile_show", methods={"GET"})
     */
    public function show(User $user, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, ProductRepository $productRepository): Response
    {
        return $this->render('crud_user_profile/show.html.twig', [
            'user' => $user,
            'categories' => $categoryRepository->findAll(),
            'vendors'=>$vendorRepository->findAll(),
            'products'=>$productRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_user_profile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, VendorRepository $vendorRepository): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_user_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_user_profile/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'categories' => $categoryRepository->findAll(),
            'vendors'=>$vendorRepository->findAll(),

        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="crud_user_profile_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_user_profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
