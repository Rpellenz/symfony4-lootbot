<?php

namespace App\Controller\Api;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

/**
 * @Route("/auth")
 */
class ApiAuthController extends AbstractController
{
    /**
     * @Route("/register", name="api_auth_register",  methods={"POST"})
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function register(Request $request, UserManagerInterface $userManager)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $validator = Validation::createValidator();

        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            'username' => new Assert\Length(array('min' => 1)),
            'password' => new Assert\Length(array('min' => 1)),
            'email' => new Assert\Email(),
        ));

        $violations = $validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        $user = new User();
        $user
            ->setUsername($data['username'])
            ->setPlainPassword($data['password'])
            ->setEmail($data['email'])
            ->setEnabled(true)
            ->setRoles(['ROLE_USER'])
            ->setSuperAdmin(false)
        ;

        try {
            $userManager->updateUser($user, true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'could not create the user'], 500);

        }

        # Code 307 preserves the request method, while redirectToRoute() is a shortcut method.
        return $this->redirectToRoute('api_auth_login', [
            'username' => $data['username'],
            'password' => $data['password']
        ], 307);
    }


    /**
     * @Route("/discord/token/check", name="api_auth_dicord_check_token",  methods={"POST"})
     */
    public function discordCheck(Request $request, UserManagerInterface $userManager)
    {
        $post_data = json_decode(
            $request->getContent(),
            true
        );


        $discord_endpoint = getenv('DISCORD_ENDPOINT');
        $client_id = getenv('DISCORD_CLIENT_ID');
        $client_secret = getenv('DISCORD_CLIENT_SECRET');
        $redirect_uri = getenv('DISCORD_REDIRECT_URI');
        $data =  'client_id=' . $client_id
            . '&client_secret=' . $client_secret
            . '&grant_type=authorization_code'
            . '&code=' . $post_data['code']
            . '&redirect_uri=' . $redirect_uri
            . '&scope=' . 'identify guilds';

        $client = new \GuzzleHttp\Client();

        $uri = $discord_endpoint . '/oauth2/token';


        try {
            // $uri = 'http://localhost/symfony4-api-jwt-master/test.php';
            $response = $client->request('POST', $uri, [
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'body' => $data
            ]);
        } catch (RequestException $e) {
            return new JsonResponse(['error' => 'could not authorize discord user'], 401);
        }
        if ($response->getStatusCode() === 200) {
            return new JsonResponse(json_decode($response->getBody()), 200);
        }
        return new JsonResponse(['error' => 'could not authorize discord user'], 401);
    }

    /**
     * @Route("/discord/token/refresh", name="api_auth_dicord_refresh_token",  methods={"POST"})
     */
    public function discordRefreshToken(Request $request, UserManagerInterface $userManager) {
        $post_data = json_decode(
            $request->getContent(),
            true
        );

        $discord_endpoint = getenv('DISCORD_ENDPOINT');
        $client_id = getenv('DISCORD_CLIENT_ID');
        $client_secret = getenv('DISCORD_CLIENT_SECRET');
        $redirect_uri = getenv('DISCORD_REDIRECT_URI');
        $data =  'client_id=' . $client_id
            . '&client_secret=' . $client_secret
            . '&grant_type=refresh_token'
            . '&refresh_token=' . $post_data['refresh_token']
            . '&redirect_uri=' . $redirect_uri
            . '&scope=' . 'identify guilds';

        $client = new \GuzzleHttp\Client();

        $uri = $discord_endpoint . '/oauth2/token';


        try {
            // $uri = 'http://localhost/symfony4-api-jwt-master/test.php';
            $response = $client->request('POST', $uri, [
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'body' => $data
            ]);
        } catch (RequestException $e) {
            return new JsonResponse(['error' => 'could not authorize discord user'], 401);
        }
        if ($response->getStatusCode() === 200) {
            return new JsonResponse(json_decode($response->getBody()), 200);
        }
        return new JsonResponse(['error' => 'could not authorize discord user'], 401);
    }
}
