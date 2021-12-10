<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\VendorRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartService $cartService, CategoryRepository $categoryRepository, VendorRepository $vendorRepository): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
            'categories'=>$categoryRepository->findAll(),
            'vendors'=>$vendorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/cart/{product}/delete", name="cart_delete")
     */
    public function delete(CartService $cartService, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, Product $product): Response
    {
        $cartService->delete($product);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/empty", name="cart_empty")
     */
    public function empty(CartService $cartService, CategoryRepository $categoryRepository, VendorRepository $vendorRepository): Response
    {
        $cartService->empty();
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/{product}/update", name="cart_update")
     */
    public function update(CartService $cartService, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, Product $product, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        $cartService->update($product, $request->request->get('quantity'));
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/{product}/add", name="cart_add")
     */
    public function add(CartService $cartService, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, Product $product): Response
    {
        $cartService->add($product);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/{cart}/checkout", name="cart_checkout")
     */
    public function checkout(CartService $cartService, CategoryRepository $categoryRepository, VendorRepository $vendorRepository, Order $order, Address $address): Response
    {
        $cartService->checkout($order, $address);
        return $this->redirectToRoute('cart_checkout');
    }
}
