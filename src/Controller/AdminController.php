<?php

namespace App\Controller;


use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\ProductType;
use App\Manager\CartManager;
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
}
