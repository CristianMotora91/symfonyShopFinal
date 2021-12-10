<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Vendor;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ProductRepository $productRepository,CategoryRepository $categoryRepository, VendorRepository $vendorRepository): Response
    {
        //1.$categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        //2.$categories = $categoryRepository->findAll();


        return $this->render('default/index.html.twig',
            [
                'categories'=>$categoryRepository->findAll(),
                'vendors'=>$vendorRepository->findAll(),
                'productsNewest'=>$productRepository->findBy([],['id'=>'DESC'],4),
                'productsReels'=>$productRepository->findBy(['category'=>2], ['id'=>'DESC'], 4),
                'productsRods'=>$productRepository->findBy(['category'=>8], ['id'=>'DESC'], 4)
            ]);
    }

    /**
     * @Route("/category/{category}", name="category")
     */
    public function category(ProductRepository $productRepository,CategoryRepository $categoryRepository,VendorRepository $vendorRepository, Category $category): Response
    {

        return $this->render('default/category.html.twig',
            [
                'category'=>$category,
                'products'=>$productRepository->findAll(),
                'categories'=>$categoryRepository->findAll(),
                'vendors'=>$vendorRepository->findAll()

            ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(CategoryRepository $categoryRepository, VendorRepository $vendorRepository, ProductRepository $productRepository): Response
    {
        return $this->render('default/contact.html.twig',
            [
                'categories'=>$categoryRepository->findAll(),
                'vendors'=>$vendorRepository->findAll(),
                'products'=>$productRepository->findAll()
            ]);
    }

    /**
     * @Route("/vendor/{vendor}", name="vendor")
     */
    public function vendor(Vendor $vendor, VendorRepository $vendorRepository, CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        return $this->render('default/vendor.html.twig',
            [   'vendor'=>$vendor,
                'vendors'=>$vendorRepository->findAll(),
                'categories'=> $categoryRepository->findAll(),
                'products'=>$productRepository->findAll()
            ]);
    }

    /**
     * @Route("/discount", name="discount")
     */
    public function discount(ProductRepository $productRepository,CategoryRepository $categoryRepository, VendorRepository $vendorRepository): Response
    {
        return $this->render('default/discount.html.twig',
            [
                'categories'=>$categoryRepository->findAll(),
                'vendors'=>$vendorRepository->findAll(),
                'products'=>$productRepository->findBy([],['discount'=>'DESC'],12)
            ]);
    }

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

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin", name="admin")
     */
    public function admin(CategoryRepository $categoryRepository, VendorRepository $vendorRepository, ProductRepository $productRepository): Response
    {
        return $this->render('default/adminPage.html.twig',
            [
                'categories'=>$categoryRepository->findAll(),
                'vendors'=>$vendorRepository->findAll(),
                'products'=>$productRepository->findAll()
            ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/user", name="user")
     */
    public function userPannel(User $user, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, ProductRepository $productRepository): Response
    {
        return $this->render('default/userPage.html.twig',
            [
                'user'=>$user,
                'categories'=>$categoryRepository->findAll(),
                'vendors'=>$vendorRepository->findAll(),
                'products'=>$productRepository->findAll()
            ]);
    }
}
