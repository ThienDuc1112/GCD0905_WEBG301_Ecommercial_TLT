<?php

namespace App\Controller;

use App\Entity\Image;
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

/**
 * @Route("api/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

//    /**
//     * @Route("/new", name="app_product_new", methods={"GET", "POST"})
//     */
//    public function new(Request $request, ProductRepository $productRepository): Response
//    {
//        $product = new Product();
//        $form = $this->createForm(ProductType::class, $product);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            //------------------Image Upload--------------//
//
//            $file = $form['Picture']->getData();
//            if ($file) {
//                $fileName = $this->generateUniqueFileName() . '.jpg';
//                try {
//                    $file->move(
//                        $this->getParameter('images_directory'),
//                        $fileName
//                    );
//                } catch (FileException $e) {
//                    print($e);
//                }
//                $product->setPicture($fileName);
//            }
//
//            $images = $form['images']->getData();
//            foreach ($images as $image) {
//                $fileImage = $this->generateUniqueFileName() . '.jpg';
//                $image->move(
//                    $this->getParameter('images_directory'),
//                    $fileImage
//                );
//                $img = new Image();
//                $img->setImage($fileImage);
//                $product->addImage($img);
//
//            }
//            //------------------Image Upload--------------//
//
////            $productRepository->add($product, true);
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($product);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('admin/Product/new.html.twig', [
//            'product' => $product,
//            'form' => $form,
//        ]);
//    }

//    /**
//     * @Route("/action", name="app_product_action", methods={"GET"})
//     */
//    public function actionProduct(ProductRepository $productRepository): Response
//    {
//        return $this->render('product/action.html.twig', [
//            'products' => $productRepository->findAll(),
//        ]);
//    }

//    T??m ra s???n ph???m c?? brand l?? adidas


//    /**
//     * @Route("/{id}", name="app_product_show", methods={"GET"})
//     */
//    public function show(Product $product): Response
//    {
//        return $this->render('product/show.html.twig', [
//            'product' => $product,
//        ]);
//    }

//    /**
//     * @Route("/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
//     */
//    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
//    {
//        $form = $this->createForm(ProductType::class, $product);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $productRepository->add($product, true);
//
//            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('product/edit.html.twig', [
//            'product' => $product,
//            'form' => $form,
//        ]);
//    }


//    /**
//     * @Route("/{id}", name="app_product_delete", methods={"POST"})
//     */
//    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
//    {
//        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
//            $productRepository->remove($product, true);
//        }
//
//        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
//    }
//
//    private function generateUniqueFileName(): string
//    {
//        return md5(uniqid());
//    }



}
