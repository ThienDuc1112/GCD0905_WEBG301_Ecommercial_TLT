<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/shop")
 */
class ShopController extends AbstractController
{
    /**
     * @Route("/page/{pageId}/", name="app_shop", methods={"GET"})")
     */
    public function index(ProductRepository $productRepository, Request $request, $pageId = 1): Response
    {
        $selectBrand = $request->query->get('brand');
        $selectCategory = $request->query->get('cat');
        $minPrice = $request->query->get('minPrice');
        $maxPrice = $request->query->get('maxPrice');
        $sortBy = $request->query->get('sort');
        $orderBy = $request->query->get('order');
        $search = $request->query->get('search');

        $expressionBuilder = Criteria::expr();
        $criteria = new Criteria();
        if (is_null($minPrice) || empty($minPrice)) {
            $minPrice = 0;
        }
        $criteria->where($expressionBuilder->gte('Price', $minPrice));
        if (!is_null($maxPrice) && !empty($maxPrice)) {
            $criteria->andWhere($expressionBuilder->lte('Price', $maxPrice));
        }
        if (!is_null($selectCategory)) {
            $criteria->andWhere($expressionBuilder->eq('Category', $selectCategory));
        }
        if (!is_null($selectBrand)) {
            $criteria->andWhere($expressionBuilder->eq('Brand', $selectBrand));
        }
        if (!empty($sortBy)) {
            $criteria->orderBy([$sortBy => ($orderBy == 'asc') ? Criteria::ASC : Criteria::DESC]);
        }
        if (!empty($search)) {
            $criteria->andwhere($expressionBuilder->contains('Name', $search));
        }
        $filteredList = $productRepository->matching($criteria);
        $numOfItems = $filteredList->count();   // total number of items satisfied above query
        $itemsPerPage = 4; // number of items shown each page
        $filteredList = $filteredList->slice($itemsPerPage * ($pageId - 1), $itemsPerPage);

        return $this->render('front/shop.html.twig', [
            'products' => $filteredList,
            'selectedCat' => $selectCategory ?: '',
            'selectedBrand' => $selectBrand ?: '',
            'pageNumber' => ceil($numOfItems / $itemsPerPage),
        ]);
    }
//

    /**
     * @Route("/productDetail/{productID}", name="product_detail", methods={"GET"})
     */
    public function productDetail(Product $product): Response
    {
        return $this->render('product/detail_product.html.twig', [
            'product' => $product,
        ]);

    }

    /**
     * @Route("/adidas", name="adidas")
     */
    public function getBrandAdidas(ProductRepository $productRepository): Response
    {

        $products = $productRepository->findBy(['Brand' => 'Adidas']);
        return $this->render('product/adidas.html.twig', [
            'products' => $products
        ]);
    }
//
////    Tìm ra sản phẩm có brand là Nike
////
    /**
     * @Route("/nike", name="nike")
     */
    public function getBrandNike(ProductRepository $productRepository): Response
    {

        $products = $productRepository->findBy(['Brand' => 'Nike']);
        return $this->render('product/nike.html.twig', [
            'products' => $products
        ]);
    }


}
