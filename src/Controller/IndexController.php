<?php

namespace App\Controller;

use App\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class IndexController extends Controller
{
    /**
     * @Route("/nearby-shops", name="nearby-shops")
     * @IsGranted("ROLE_USER")
     */
    public function nearbyShops()
    {

        $shopsJson = array();
        $user = $this->getUser();
        $likedUnlikedShops = $user->getLikedUnlikedShops();
        forEach ($likedUnlikedShops as $likedUnlikedShop) {


            $shop = $likedUnlikedShop->getShop();
            if ($likedUnlikedShop->getLikedUnliked())
            {
                $shopsJson[] = array(
                    "shopId" => $shop->getId()
                );
            }

        }
        return $this->render('index/nearby-shops.html.twig', [
            'shopsJson' => json_encode($shopsJson)
        ]);

    }


    /**
     * @Route("/preferred-shops", name="preferred-shops")
     * @IsGranted("ROLE_USER")
     */
    public function preferredShops()
    {
        $shopsJson = array();
        $shops = array();
//        $repository = $this->getDoctrine()->getRepository(Shop::class);
        $user = $this->getUser();
        $likedUnlikedShops = $user->getLikedUnlikedShops();
        forEach ($likedUnlikedShops as $likedUnlikedShop) {
            if ($likedUnlikedShop->getLikedUnliked())
            {
                $shop = $likedUnlikedShop->getShop();
                $shops[] = $shop;
                $shopsJson[] = array(
                    "shopId" => $shop->getId()
                );
            }
        }

        return $this->render('index/preferred-shops.html.twig', [
            'shops' => $shops,
            'shopsJson' => json_encode($shopsJson)
        ]);
    }


}