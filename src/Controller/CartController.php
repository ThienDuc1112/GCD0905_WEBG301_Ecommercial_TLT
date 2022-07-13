<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\User;
use App\Form\AddToCartType;
use App\Form\CartItemType;
use App\Form\CartType;
use App\Form\EditUserType;
use App\Manager\CartManager;
use App\Repository\OrderDetailRepository;
use App\Repository\UserRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api")
 */

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartManager $cartManager, Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $cart = $cartManager->getCurrentCart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setUpdatedAt(new DateTime());
            $cartManager->save($cart);
            return $this->redirectToRoute('cart');
        }
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
    /**
     * @Route("/Product/{id}", name="app_product_show", methods={"GET","POST"})
     */
    public function DetailProduct(Product $product, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();//take quantity
            $item->setProduct($product);//take id product

            $cart = $cartManager->getCurrentCart();//check if order existed or not, if not create an order, else take id
            //of current order
            $this->denyAccessUnlessGranted('ROLE_USER');
            $cart
                ->addItem($item)//if product existed, add more quantity, else add id of order into detailorder
                ->setUpdatedAt(new DateTime());

            $cartManager->save($cart);//insert into database

            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }


        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form'=>$form->createView()
        ]);
    }

    public function createOrder():Order{
        $order = new Order();
        /** @var \App\Entity\User $user */
            $user = $this->getUser();
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $order ->setStatus(Order::STATUS_CART)
                ->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime())
                ->setUser($user);
        return $order;
    }


}
