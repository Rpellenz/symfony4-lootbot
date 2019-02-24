<?php


namespace App\Controller\Api;

use App\Entity\Config;
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
 * @Route("/config")
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/guild/{id}/key/{config_key}", name="config_set",  methods={"PUT"})
     * @param $id
     * @param $config_key
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function setConfig($id, $config_key, Request $request, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user) {
            $data = json_decode(
                $request->getContent(),
                true
            );

            $entityManager = $this->getDoctrine()->getManager();
            $config = $entityManager->getRepository(Config::class)->findByKey($id, $config_key);

            if (!$config) {
                // set a new config
                $config = new Config();
                $config->setGuildId($id);
                $config->setConfigKey($config_key);
                $config->setConfigValue($data['config_value']);
                $entityManager->persist($config);
                $entityManager->flush();
            }
            else
            {
                $config[0]->setConfigValue($data['config_value']);
                $entityManager->flush();
            }

            $json = $serializer->serialize($config, 'json');

            return new JsonResponse(json_decode($json), 200);
        } else {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }


    /**
     * @Route("/guild/{id}/key/{config_key}", name="config_get",  methods={"GET"})
     * @param $id
     * @param $config_key
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getConfig($id, $config_key, SerializerInterface $serializer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $config = $entityManager->getRepository(Config::class)->findByKey($id, $config_key);
            if ($config) {
                $json = $serializer->serialize($config, 'json');
                return new JsonResponse(json_decode($json), 200);
            } else {
                return new JsonResponse(["error" => 'no config found'], 404);
            }
        } else {
            return new JsonResponse(["error" => 'not a user or not logged in'], 401);
        }
    }
}
