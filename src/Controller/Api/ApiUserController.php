<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/user")
 */
class ApiUserController extends AbstractController
{
    /**
     * @Route("/{id}", name="api_user_detail", methods={"GET"})
     * @param User $user
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function detail(User $user, $id, SerializerInterface $serializer)
    {
        $this->denyAccessUnlessGranted('view', $user);
        // return new JsonResponse($this->serialize($user), 200);

        $json = $serializer->serialize($user, 'json');

        return new JsonResponse($json, 200);
    }

    protected function serialize(User $user)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizer = new GetSetMethodNormalizer();

        $serializer = new Serializer([$normalizer], [$encoders]);

        $json = $serializer->serialize($user, 'json');

        return $json;
    }
}
