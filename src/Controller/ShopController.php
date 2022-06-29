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

/**
 * @Route("/shop")
 */
class ShopController extends AbstractController
{
    /**
     * @Route("/page/{pageId}", name="app_shop", methods={"GET"})")
     */
    public function index(ProductRepository $productRepository, Request $request, $pageId = 1): Response
    {
        $selectCategory = $request->query->get('category');
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
        if (!empty($sortBy)) {
            $criteria->orderBy([$sortBy => ($orderBy == 'asc') ? Criteria::ASC : Criteria::DESC]);
        }
        if(!empty($search)){
            $criteria->andwhere($expressionBuilder->contains('Name',$search));
        }
        $filteredList = $productRepository->matching($criteria);
        $numOfItems = $filteredList->count();   // total number of items satisfied above query
        $itemsPerPage = 4; // number of items shown each page
        $filteredList = $filteredList->slice($itemsPerPage * ($pageId - 1), $itemsPerPage);

        return $this->render('front/shop.html.twig', [
            'products'=>$filteredList,
            'selectedCat' => $selectCategory ?: '',
            'pageNumber' => ceil($numOfItems/$itemsPerPage)

        ]);
    }
}
