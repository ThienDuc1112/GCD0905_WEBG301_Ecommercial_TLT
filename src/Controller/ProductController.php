<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\ProductType;
use App\Manager\CartManager;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="app_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
<<<<<<< Updated upstream
     * @Route("/action", name="app_product_action", methods={"GET"})
     */
    public function actionProduct(ProductRepository $productRepository): Response
    {
        return $this->render('product/action.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_product_new", methods={"GET", "POST"})
=======
     * @Route("/product/new", name="app_product_new", methods={"GET", "POST"})
>>>>>>> Stashed changes
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
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $form->get('Brand')->getData() . '.jpg'
                    );
                } catch (FileException $e) {
                    print($e);
                }
                $product->setPicture($form->get('Brand')->getData() . '.jpg');
            }
            //------------------Image Upload--------------//

            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

//    Tìm ra sản phẩm có brand là adidas

    /**
     * @Route("/product/adidas", name="adidas")
     */
    public function getBrandAdidas(ProductRepository $productRepository)
    {

        $products = $productRepository->findBy(['Brand' => 'Adidas']);
        return $this->render('product/adidas.html.twig', [
            'products' => $products
        ]);
    }

//    Tìm ra sản phẩm có brand là Nike

    /**
     * @Route("/product/nike", name="nike")
     */
    public function getBrandNike(ProductRepository $productRepository)
    {

        $products = $productRepository->findBy(['Brand' => 'Nike']);
        return $this->render('product/nike.html.twig', [
            'products' => $products
        ]);
    }

//    /**
//     * @Route("/product/{id}", name="app_product_show", methods={"GET"})
//     */
//    public function show(Product $product, Request $request, CartManager $cartManager): Response
//    {
//        $form = $this->createForm(AddToCartType::class);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $item = $form->getData();
//            $item->setProduct($product);
//            $cart = $cartManager->getCurrentCart();
//            $cart->addItem($item);
//            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
//        }
//
//        return $this->render('product/show.html.twig', [
//            'product' => $product,
//            'form'=>$form->createView()
//        ]);
//    }

    /**
     * @Route("/product/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/product/{id}", name="app_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

}
