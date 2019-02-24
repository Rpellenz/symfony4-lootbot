<?php


namespace App\Controller\Api;


use App\Entity\DiscordMember;
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
 * @Route("/discord")
 */
class DiscordMemberController extends AbstractController
{
    /**
     * @Route("/guild/{id}/member/{member_id}", name="set_discrod_member_by_id",  methods={"PUT"})
     * @param $id
     * @param $member_id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function setMember($id, $member_id, Request $request, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user) {
            $data = json_decode(
                $request->getContent(),
                true
            );

            $entityManager = $this->getDoctrine()->getManager();
            $discord_member = $entityManager->getRepository(DiscordMember::class)->findByDiscordId($id, $member_id);

            if (!$discord_member) {
                // set a new config
                $discord_member = new DiscordMember();
                $discord_member->setGuildId($id);
                $discord_member->setDiscordId($member_id);
                $discord_member->setName($data['name']);
                $entityManager->persist($discord_member);
                $entityManager->flush();
            } else {
                $discord_member[0]->setName($data['name']);
                $entityManager->flush();
            }

            $json = $serializer->serialize($discord_member, 'json');

            return new JsonResponse(json_decode($json), 200);
        } else {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }


    /**
     * @Route("/guild/{id}/member/{member_id}", name="get_discrod_member_by_id",  methods={"GET"})
     * @param $id
     * @param $member_id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getMember($id, $member_id, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $discord_member = $entityManager->getRepository(DiscordMember::class)->findByDiscordId($id, $member_id);
            if ($discord_member) {
                $json = $serializer->serialize($discord_member, 'json');
                return new JsonResponse(json_decode($json), 200);
            } else {
                return new JsonResponse(["error" => 'no member found'], 404);
            }
        } else {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }

    /**
     * @Route("/guild/{id}/member", name="get_discrod_member_by_id",  methods={"GET"})
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getMemberList($id, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $discord_member = $entityManager->getRepository(DiscordMember::class)->findByGuild($id);
            if ($discord_member) {
                $json = $serializer->serialize($discord_member, 'json');
                return new JsonResponse(json_decode($json), 200);
            } else {
                return new JsonResponse(["error" => 'no member found'], 404);
            }
        } else {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }
}
