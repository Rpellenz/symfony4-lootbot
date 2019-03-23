<?php


namespace App\Controller\Api;

use App\Entity\Config;
use App\Entity\Guild;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/guild")
 */
class GuildController extends AbstractController
{
    /**
     * @Route("/{id}", name="set_guild",  methods={"PUT"})
     * @param $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function setGuild($id, Request $request, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user) {
            $data = json_decode(
                $request->getContent(),
                true
            );

            $entityManager = $this->getDoctrine()->getManager();
            $guild = $entityManager->getRepository(Guild::class)->findByGuildId($id);

            if (!$guild) {
                // set a new config
                $guild = new Guild();
                $guild->setGuildId($id);
                $guild->setGuildName($data['guild_name']);
                $entityManager->persist($guild);
                $entityManager->flush();
            }
            else
            {
                $guild[0]->setConfigValue($data['guild_name']);
                $entityManager->flush();
            }
            $json = $serializer->serialize($guild, 'json');

            return new JsonResponse(json_decode($json), 200);
        } else {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }

    /**
     * @Route("/{id}", name="get_guild",  methods={"GET"})
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getGuild($id, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $guild = $entityManager->getRepository(Guild::class)->findByGuildId($id);
            if ($guild) {
                $json = $serializer->serialize($guild, 'json');
                return new JsonResponse(json_decode($json), 200);
            } else {
                return new JsonResponse(["error" => 'no guild found'], 404);
            }
        } else {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }
}
