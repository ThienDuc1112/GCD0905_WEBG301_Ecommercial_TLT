<?php

namespace App\Controller;


use App\Entity\Image;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\User;
use App\Form\AddToCartType;
use App\Form\InformationOrderType;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;


/**
 * @Route("/api/admin")
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
            //------------------Images Upload--------------//
            $images = $form['images']->getData();
            foreach ($images as $image) {
                $fileImage = $this->generateUniqueFileName() . '.jpg';
                $image->move(
                    $this->getParameter('images_directory'),
                    $fileImage
                );
                $img = new Image();
                $img->setImage($fileImage);
                $product->addImage($img);
            }



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
     * @Route("/orderdelete/{id}", name="admin_order_delete", methods={"POST"})
     */
    public function orderDelete(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            $orderRepository->remove($order, true);
        }

        return $this->redirectToRoute('admin_order_list', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/productdelete/{id}", name="admin_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('admin_product_action', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/action", name="admin_product_action", methods={"GET"})
     */
    public function actionProduct(ProductRepository $productRepository): Response
    {
        return $this->render('admin/Product/action.html.twig', [
            'products' => $productRepository->findAll(),
        ]);

//        $products = $this->getDoctrine()
//            ->getRepository(Product::class)
//            ->findAll();
//
//        $data = [];
//
//        foreach ($products as $product) {
//            $data[] = [
//                'id' => $product->getId(),
//                'name' => $product->getName(),
//                'description' => $product->getDescription(),
//            ];
//        }
//
//
//        return $this->json($data);

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
     * @Route("/user/{id}", name="admin_user_delete", methods={"POST"})
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
     * @Route("/orderdetails/{id}", name="admin_orderdetails", methods={"GET"})
     * Entity("Order", expr="repository.find(id)")
     */
    public function OrderDetail(OrderRepository $orderRepository, Order $order): Response
    {
//        $form = $this->createForm(InformationOrderType::class, $order);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $orderRepository->add($order, true);
//
//            return $this->redirectToRoute('admin_orderdetails', [], Response::HTTP_SEE_OTHER);
//        }
        $id = $order->getId();
        $product=$orderRepository->findDetail($id);
        $customer=$orderRepository->findCustomer($id);
        return $this->render('admin/order_details.html.twig', [
            'order'=>$order,
            'details'=>$product,
            'customer'=>$customer,
//            'form' => $form,
        ]);
    }


//    Rest Api

    /**
     * @Route("/productapi", name="admin_product_indexapi", methods={"GET"})
     */
    public function Apiindex(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'brand' => $product->getBrand(),
                'category' => $product->getCategory(),
                'picture' => $product-> getPicture(),
            ];
        }


        return $this->json($data);
    }

    /**
     * @Route("/productapi", name="admin_product_newapi", methods={"POST"})
     */
    public function Apinew(Request $request, ProductRepository $productRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $product->setDescription($request->request->get('description'));
        $product->setBrand($request->request->get('brand'));
        $product->setCategory($request->request->get('category'));
        $product->setPicture($request->request->get('picture'));

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json('Created new project successfully with id ' . $product->getId());
    }

    /**
     * @Route("/productapi/{id}", name="admin_product_showapi", methods={"GET"})
     */
    public function Apishow(int $id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {

            return $this->json('No project found for id ' . $id, 404);
        }

        $data =  [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'brand' => $product->getBrand(),
            'category' => $product->getCategory(),
            'picture' => $product-> getPicture(),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/productapi/{id}", name="admin_product_editapi", methods={"PUT"})
     */
    public function Apiedit(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No project found for id' . $id, 404);
        }

        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $product->setDescription($request->request->get('description'));
        $product->setBrand($request->request->get('brand'));
        $product->setCategory($request->request->get('category'));
        $product->setPicture($request->request->get('picture'));
        $entityManager->flush();


        $data =  [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'brand' => $product->getBrand(),
            'category' => $product->getCategory(),
            'picture' => $product-> getPicture(),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/productapi/{id}", name="admin_product_deleteapi", methods={"DELETE"})
     */
    public function Apidelete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No project found for id' . $id, 404);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json('Deleted a project successfully with id ' . $id);
    }





}
