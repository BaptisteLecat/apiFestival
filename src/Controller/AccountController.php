<?php

namespace App\Controller;

use App\Entity\User;
use Firebase\JWT\JWT;
use App\Repository\UserRepository;
use App\Security\JwtAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    private $security;
    private $em;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->em = $entityManager;
    }

    /**
     * Récupération d'un user depuis son token
     * @Route("/api/account/whoami", name="api_account_whoami", methods={"GET"})
     */
    public function whoAmI(Request $request, SerializerInterface $serializer)
    {
        $user = $this->security->getUser();
        if (is_null($user)) {
            return
                new Response("'Error : Invalid Token.'", 401, ['Content-Type' => 'application/json+ld']);
        } else {
            return
                new Response($serializer->serialize($user, 'json', ['groups' => 'user:get']), 201, ['Content-Type' => 'application/json+ld']);
        }
    }
}
