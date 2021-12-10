<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use App\Repository\VendorRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{

    /**
     * @Route("/search", name="handleSearch")
     * @param Request $request
     */
    public function handleSearch(\Symfony\Component\HttpFoundation\Request $request, VendorRepository $vendorRepository, CategoryRepository $categoryRepository)
    {
        $requestData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from(Product::class, 'p')
            ->where('p.name like :search')
            ->orderBy('p.name', 'ASC')
            ->setParameter('search', '%' . $requestData['form']['search'] . '%');
        $query = $qb->getQuery();
        $products = $query->getResult();


        return $this->render('search/index.html.twig',
            ['categories' => $categoryRepository->findAll(),
                'vendors' => $vendorRepository->findAll(),
                'products' => $products,
                'search' => $request->request
            ]);
    }

    public function searchBar()
    {
        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            ->add('cauta', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success'
                ]
            ])
            ->getForm();

        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
