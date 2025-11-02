<?php

namespace App\Twig;

use App\Repository\CartItemRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private Security $security,
        private CartItemRepository $cartItemRepository
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_cart_item_count', [$this, 'getCartItemCount']),
        ];
    }

    public function getCartItemCount(): int
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            return 0;
        }

        return $this->cartItemRepository->getCartItemCount($user);
    }
}