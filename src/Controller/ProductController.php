<?php

namespace App\Controller;

use App\Form\Product1Type;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Vendor;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Image;


class ProductController extends AbstractController
{
    /**
     * @Route("/product/{product}", name="product")
     */
    public function product(Product $product,ProductRepository $productRepository,CategoryRepository $categoryRepository, VendorRepository $vendorRepository): Response
    {
        return $this->render('default/product.html.twig',
            [
                'categories'=>$categoryRepository->findAll(),
                'vendors'=>$vendorRepository->findAll(),
                'product'=>$product

            ]);
    }
}
