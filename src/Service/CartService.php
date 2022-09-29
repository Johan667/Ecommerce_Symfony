<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function CartQuantity()
    {
        $totalItems = 0;
        if ($this->session->get('cart', 'quantity') >= 1) {
            $tableau = $this->session->get('cart', 'quantity');
            if ($this->session->get('cart', 'quantity') >= 1) {
                foreach ($tableau as $key => $quantity) {
                    $totalItems += $quantity;
                }
            }
            echo $totalItems;
        }
    }
}
