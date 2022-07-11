<?php
namespace App\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

/**
 * class OrderFactory
 */
class OrderFactory{

    public function createOrderDetail(Product $product):OrderDetail
    {
        $item = new OrderDetail();
        $item ->setProduct($product);
        $item->setQuantity(1);

        return $item;
    }

//    public function create():Order{
//        $order = new Order();
//        $user = $this->getUser();
//        $order->setUser($user);
//        date_default_timezone_set('Asia/Ho_Chi_Minh');
//        $order ->setStatus(Order::STATUS_CART)
//
//            ->setCreatedAt(new DateTime())
//            ->setUpdatedAt(new DateTime());
//
//        return $order;
//    }

}