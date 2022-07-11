<?php
namespace App\Storage;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartSessionStorage{

    private $requestStack;
    private $cartRepository;
    const CART_KEY = 'cart_id';

    /**
     * CartSessionStorage constructor.
     */
    public function __construct(OrderRepository $cartRepository, RequestStack $requestStack){
        $this->requestStack = $requestStack;
        $this->cartRepository = $cartRepository;
    }
    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
    private function getCartID():?int{
        return $this->getSession()->get(self::CART_KEY);
    }
    public function getCart():?Order{
        return $this->cartRepository->findOneBy([
            'id'=>$this->getCartID(),
            'status'=>Order::STATUS_CART]);
    }

    public function setCart(Order $cart):void{

        $this->getSession()->set(self::CART_KEY,$cart->getId());
    }

}