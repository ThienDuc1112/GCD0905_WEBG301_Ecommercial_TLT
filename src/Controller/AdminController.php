<?php

namespace App\Controller;


use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\User;
use App\Form\AddToCartType;
use App\Form\ProductType;
use App\Manager\CartManager;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use DateTime;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/new", name="admin_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //------------------Image Upload--------------//

            $file = $form['Picture']->getData();
            if ($file) {
                $fileName = $this->generateUniqueFileName(). '.jpg';
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    print($e);
                }
                $product->setPicture($fileName);
            }
            //------------------Image Upload--------------//

            $productRepository->add($product, true);

            return $this->redirectToRoute('admin_product_action', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/Product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
    private function generateUniqueFileName(): string
    {
        return md5(uniqid());
    }

    /**
     * @Route("/action", name="admin_product_action", methods={"GET"})
     */
    public function actionProduct(ProductRepository $productRepository): Response
    {
        return $this->render('admin/Product/action.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/listproduct", name="admin_product_list", methods={"GET"})
     */
    public function listProduct(ProductRepository $productRepository): Response
    {
        return $this->render('admin/Product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/listuser", name="admin_user_list", methods={"GET"})
     */
    public function listUser(UserRepository $userRepository): Response
    {
        return $this->render('admin/list_user.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"POST"})
     */
    public function userDelete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('admin_user_list', [], Response::HTTP_SEE_OTHER);
    }



    /**
     * @Route("/{id}/edit", name="admin_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('admin_product_action', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/Product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('admin_product_action', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/product/{id}", name="admin_product_show", methods={"GET","POST"})
     */
    public function DetailProduct(Product $product, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($product);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addItem($item)
                ->setUpdatedAt(new DateTime());

            $cartManager->save($cart);

            return $this->redirectToRoute('admin_product_show', ['id' => $product->getId()]);
        }

        return $this->render('admin/Product/show.html.twig', [
            'product' => $product,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/listorder", name="admin_order_list", methods={"GET"})
     */
    public function listOrder(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/list_order.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_order_delete", methods={"POST"})
     */
    public function orderDelete(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            $orderRepository->remove($order, true);
        }

        return $this->redirectToRoute('admin_order_list', [], Response::HTTP_SEE_OTHER);
    }

    /**
    //     * @Route("/orderdetails/{id}", name="admin_orderdetails", methods={"GET"})
    //     */
    public function OrderDetail(OrderRepository $orderRepository, Order $order, OrderDetail $orderDetail): Response
    {
        $id = $order->getId();
        $product=$orderRepository->findDetail($id);
        return $this->render('admin/order_details.html.twig', [
            'order'=>$order,
            'details'=>$product
        ]);
    }

}
