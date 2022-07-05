<?php
namespace App\Factory;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderDetail;
use DateTime;

/**
 * class OrderFactory
 */
class OrderFactory{
    public function create():Order{
        $order = new Order();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $order ->setStatus(Order::STATUS_CART)
                ->setCreatedAt(new DateTime())
                 ->setUpdatedAt(new DateTime());

        return $order;
    }
    public function createOrderDetail(Product $product):OrderDetail
    {
        $item = new OrderDetail();
        $item ->setProduct($product);
        $item->setQuantity(1);

        return $item;
    }
}