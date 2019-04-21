<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Entity\ShopsUser;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LikeDislikeController extends FOSRestController
{
    /**
     * * @Route(
     *     "/like",
     *     name="like",
     *     methods={"POST"}
     * )
     */
    public function like(Request $request)
    {

        $jsonResponse = new JsonResponse();
        try {
            $shopId = $request->get("shopId");
            $entityManager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Shop::class);
            $shop = $repository->find($shopId);
            if (!$shop) {
                $shopName = $request->get("shopName");

                $photoUrl = $request->get("photoUrl");
                $latitude = $request->get("latitude");
                $longitude = $request->get("longitude");
                $icon = $request->get("icon");
                $reference = $request->get("reference");
                $address = $request->get("address");

                $shop = new Shop();

                $shop->setId($shopId);
                $shop->setName($shopName);
                $shop->setPhotourl($photoUrl);
                $shop->setLatitude($latitude);
                $shop->setLongitude($longitude);
                $shop->setIcon($icon);
                $shop->setReference($reference);
                $shop->setAddress($address);

                $entityManager->persist($shop);
                $entityManager->flush();
            }

            $user = $this->getUser();

            $likedShop = new ShopsUser();
            $likedShop->setShop($shop);
            $likedShop->setUser($user);
            $likedShop->setLikedUnliked(true);
            $likedShop->setTimestamp(new \DateTime());


            $entityManager->persist($likedShop);
            $entityManager->flush();


            $jsonResponse->setStatusCode(Response::HTTP_OK);
            $jsonResponse->setData($shopId);

            return $jsonResponse;
        } catch (\Exception $e) {
            $jsonResponse->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $jsonResponse->setData($e->getMessage());
            return $jsonResponse;
        }

    }


    /**
     * * @Route(
     *     "/dislike",
     *     name="dislike",
     *     methods={"POST"}
     * )
     */

    public function dislike(Request $request)
    {

        /*
         * {
                            shopId: place.place_id,
                            photoUrl: shopPhoto,
                            latitude : "",
                            longitude : "",
                            icon : "",
                            reference :""
                        },
         */


        $jsonResponse = new JsonResponse();
        try {
            $shopId = $request->get("shopId");
            $entityManager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Shop::class);
            $shop = $repository->find($shopId);
            if (!$shop) {
                $shopName = $request->get("shopName");

                $photoUrl = $request->get("photoUrl");
                $latitude = $request->get("latitude");
                $longitude = $request->get("longitude");
                $icon = $request->get("icon");
                $reference = $request->get("reference");
                $address = $request->get("address");

                $shop = new Shop();

                $shop->setId($shopId);
                $shop->setName($shopName);
                $shop->setPhotourl($photoUrl);
                $shop->setLatitude($latitude);
                $shop->setLongitude($longitude);
                $shop->setIcon($icon);
                $shop->setReference($reference);
                $shop->setAddress($address);

                $entityManager->persist($shop);
                $entityManager->flush();
            }

            $user = $this->getUser();

            $likedShop = new ShopsUser();
            $likedShop->setShop($shop);
            $likedShop->setUser($user);
            $likedShop->setLikedUnliked(false);
            $likedShop->setTimestamp(new \DateTime());


            $entityManager->persist($likedShop);
            $entityManager->flush();


            $jsonResponse->setStatusCode(Response::HTTP_OK);
            $jsonResponse->setData($shopId);

            return $jsonResponse;
        } catch (\Exception $e) {
            $jsonResponse->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $jsonResponse->setData($e->getMessage());
            return $jsonResponse;
        }
    }


    /**
     * * @Route(
     *     "/remove",
     *     name="remove",
     *     methods={"POST"}
     * )
     */

    public function remove(Request $request)
    {
        $user = $this->getUser();


        $shopId = $request->get("shopId");
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(ShopsUser::class);
        $shopsUser = $repository->findOneBy([
            "user" => $user->getId(),
            "shop" => $shopId
        ]);

        //$user->removeLikedUnlikedShop($shopsUser);

        $entityManager->remove($shopsUser);
        $entityManager->flush();

        return new JsonResponse(array([
            "status" => "ok"
        ]));
    }
}
