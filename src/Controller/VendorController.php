<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VendorType;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Vendor;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/crud/vendor")
 */
class VendorController extends AbstractController
{
    /**
     * @Route("/", name="vendor_index", methods={"GET"})
     */
    public function index(VendorRepository $vendorRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('vendor/index.html.twig', [
            'vendors' => $vendorRepository->findAll(),
            'categories'=>$categoryRepository->findAll()

        ]);
    }

    /**
     * @Route("/new", name="vendor_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, VendorRepository $vendorRepository): Response
    {
        $vendor = new Vendor();
        $form = $this->createForm(VendorType::class, $vendor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vendor);
            $entityManager->flush();

            return $this->redirectToRoute('vendor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vendor/new.html.twig', [
            'vendor' => $vendor,
            'form' => $form->createView(),
            'categories'=>$categoryRepository->findAll(),
            'vendors' => $vendorRepository->findAll()
        ]);
    }


    /**
     * @Route("/{id}/edit", name="vendor_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Vendor $vendor, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, VendorRepository $vendorRepository): Response
    {
        $form = $this->createForm(VendorType::class, $vendor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('vendor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vendor/edit.html.twig', [
            'vendor' => $vendor,
            'form' => $form->createView(),
            'categories'=>$categoryRepository->findAll(),
            'vendors' => $vendorRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="vendor_delete", methods={"POST"})
     */
    public function delete(Request $request, Vendor $vendor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vendor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vendor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vendor_index', [], Response::HTTP_SEE_OTHER);
    }
}