<?php


namespace App\Controller\Api;

use App\Entity\ItemTemplate;
use App\Entity\User;
use App\Entity\LootList;
use App\Entity\Items;
use DOMDocument;
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
use voku\helper\HtmlDomParser;

/**
 * @Route("/guild")
 */
class LootListController extends AbstractController
{
    /**
     * @Route("/{id}/member/{member_id}/items", name="item_add",  methods={"POST"})
     * @param $id
     * @param $member_id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function addEntry($id, $member_id, Request $request, SerializerInterface $serializer)
    {

        $data = json_decode(
            $request->getContent(),
            true
        );

        $item= $this->getItemForItem($data['item_name']);

        $entityManager = $this->getDoctrine()->getManager();
        $lootList = new LootList();

        $lootList->setGuildId($id);
        $lootList->setMemberId($member_id);
        $lootList->setItemName($item['name']);
        $lootList->setItemId($item['item_id']);
        $lootList->setQuality($item['quality']);
        $lootList->setInsertTs();

        $entityManager->persist($lootList);
        $entityManager->flush();

        $json = $serializer->serialize($lootList, 'json');

        return new JsonResponse($json, 200);
    }

    /**
     * rturn item_id for item_name if possible (experimental)
     * @param $item_name
     * @return array
     */
    private function getItemForItem($item_name) {
        $default_item = array();
        $default_item['item_id'] = 0;
        $default_item['quality'] = 2;
        $default_item['name'] = $item_name;

        // getItemByName from lookup table
        $item = $this->getDoctrine()
            ->getRepository(Items::class)
            ->getItemByName($item_name);

        if ($item) {
            $default_item['item_id'] = $item[0]->getItemId();
            $default_item['quality'] = $item[0]->getQuality();
            $default_item['name'] = $item[0]->getItemName();
        } else {
            // from buffed
            $content = file_get_contents('http://wowdata.buffed.de/?f=' . urlencode($item_name));
            $matches = [];
            preg_match('/Btabs?(.*?\}]\))/', $content, $matches, PREG_OFFSET_CAPTURE);

            $possible_items = array();
            if ($matches && count($matches) > 1)
            {
                $str = trim($matches[1][0], '(');
                $json = json_decode(trim($str, ')'));
                foreach ($json[0]->rows as $row) {
                    if ((int)$row->level < 100) {
                        $possible_items[] = $row;
                    }
                }
            }

            if (count($possible_items) > 0){
                $default_item['item_id'] = $possible_items[0]->id;
                $default_item['name'] = substr($possible_items[0]->n, 1);
                // replace quality from item_templates
                $item_tpl = $this->getDoctrine()
                    ->getRepository(ItemTemplate::class)
                    ->findByItemId($default_item['item_id']);
                if ($item_tpl) {
                    $default_item['quality'] = (int)$item_tpl[0]->getQuality();
                }
                // save new item to our lookup table
                $entityManager = $this->getDoctrine()->getManager();
                $item_lookup = new Items();
                $item_lookup->setItemId($default_item['item_id']);
                $item_lookup->setItemName($default_item['name']);
                $item_lookup->setQuality($default_item['quality']);
                $entityManager->persist($item_lookup);
                $entityManager->flush();
            }
        }
        return $default_item;
    }

    /**
     * @Route("/{id}/items/days/{days}", name="guild_loot",  methods={"GET"})
     * @param $id
     * @param $days
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getLootForGuiild($id, $days, Request $request, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user)
        {
            // in days
            $lootList = $this->getDoctrine()
                ->getRepository(LootList::class)
                ->findByGuild((string)$id, (int)$days);

            // over all days
            $loot_count = $this->getDoctrine()
                ->getRepository(LootList::class)
                ->getLootCountForGuild((string)$id);

            $json = $serializer->serialize($lootList, 'json');
            return new JsonResponse(['loot' => json_decode($json), 'over_all' => (int)$loot_count], 200);
        }
        else
        {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }

    /**
     * @Route("/{id}/member/{member_id}/items/days/{days}", name="member_id_loot",  methods={"GET"})
     * @param $id
     * @param $member_id
     * @param $days
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getLootForMember($id, $member_id, $days, Request $request, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user)
        {
            // in days
            $lootList = $this->getDoctrine()
                ->getRepository(LootList::class)
                ->findByMember((string)$id, (string)$member_id, (int)$days);

            // over all days
            $loot_count = $this->getDoctrine()
                ->getRepository(LootList::class)
                ->getLootCountForMember((string)$id, (string)$member_id);

            $json = $serializer->serialize($lootList, 'json');
            return new JsonResponse(['loot' => json_decode($json), 'over_all' => (int)$loot_count], 200);
        }
        else
        {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }

    /**
     * @Route("/{id}/member/{member_id}/items/days/{days}/count", name="member_id_days_loot_count",  methods={"GET"})
     * @param $id
     * @param $member_id
     * @param $days
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getLootCountForMemberAndDays($id, $member_id, $days, Request $request, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user)
        {
            // over all days
            $loot_count = $this->getDoctrine()
                ->getRepository(LootList::class)
                ->getLootCountForUserAndDays((string)$id, (string)$member_id, (int)$days);

            return new JsonResponse(['over_all' => (int)$loot_count], 200);
        }
        else
        {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }

    /**
     * @Route("/{id}/items/{item_name}", name="loot_by_item_name",  methods={"GET"})
     * @param $id
     * @param $item_name
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getLootForItemName($id, $item_name, Request $request, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user)
        {
            $lootList = $this->getDoctrine()
                ->getRepository(LootList::class)
                ->findByItem($id, $item_name);

            $json = $serializer->serialize($lootList, 'json');

            return new JsonResponse($json, 200);
        }
        else
        {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }

    /**
     * @Route("/{id}/items", name="delete_loot_by_guild",  methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function deleteByGuild($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user)
        {
            // deleteByGuild
            $this->getDoctrine()
                ->getRepository(LootList::class)
                ->deleteByGuild($id);
            return new JsonResponse(["success" => 'delete done'], 200);
        }
        else
        {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }

    /**
     * @Route("/{id}/member/{member_id}/items", name="delete_loot_by_guild_user",  methods={"DELETE"})
     * @param $id
     * @param $member_id
     * @return JsonResponse
     */
    public function deleteByGuildAndMemberId($id, $member_id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user)
        {
            // deleteByGuild
            $this->getDoctrine()
                ->getRepository(LootList::class)
                ->deleteByGuildAndMemberId($id, $member_id);
            return new JsonResponse(["success" => 'delete done'], 200);
        }
        else
        {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }

    /**
     * @Route("/{id}/member/{member_id}/item/{item_name}", name="delete_loot_by_guild_user_item",  methods={"DELETE"})
     * @param $id
     * @param $member_id
     * @param $item_name
     * @return JsonResponse
     */
    public function deleteByGuildAndMemberIdAndItemName($id, $member_id, $item_name)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user)
        {
            $loot = $this->getDoctrine()
                ->getRepository(LootList::class)
                ->getItemByGuildAndMemberIdAndItemName($id, $member_id, $item_name);

            if ($loot)
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($loot[0]);
                $entityManager->flush();
            }
            return new JsonResponse(["success" => 'delete done'], 200);
        }
        else
        {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }
}
