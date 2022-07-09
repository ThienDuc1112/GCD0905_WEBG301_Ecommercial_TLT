<?php
namespace App\Manager;

use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\CartController;

class CartManager{

    private $cartSessionStorage;
    private $cartFactory;
    private $entityManager;
    private $cartController;
    public function __construct(CartSessionStorage $cartSessionStorage, OrderFactory $cartFactory, EntityManagerInterface $entityManager,
    CartController $cartController){
        $this->cartSessionStorage=$cartSessionStorage;
        $this->cartFactory = $cartFactory;
        $this->entityManager = $entityManager;
        $this->cartController = $cartController;
    }

    public function getCurrentCart():Order{
        $cart=$this->cartSessionStorage->getCart();
        if(!$cart){
            $cart=$this->cartController->createOrder();
        }
        return $cart;
    }

    public function save(Order $cart):void{
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        $this->cartSessionStorage->setCart($cart);
    }
}