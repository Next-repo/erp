<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/google')]
class GoogleController extends AbstractController
{
    #[Route(path: '/connect', name: 'google_connect')]
    public function googleConnect(ClientRegistry $clientRegistry)
    {

        /** @var GoogleClient $client */
        $client = $clientRegistry->getClient('google');
        return $client->redirect(
            [
                'email',
                'profile'
            ],
            [
                'service_google' => 'google'
            ]
        );
    }

    #[Route('/oauth/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var GoogleClient $client */
        $client = $clientRegistry->getClient('google');

        try {
            $user = $client->fetchUser();

            // Récupérer le paramètre ajouté
            $serviceGoogle = $request->query->get('service_google');

            dd([
                'user_google' => $user->toArray(),
                'param_recu' => $serviceGoogle
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
