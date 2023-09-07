<?php  
// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Usuario;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class CustomAuthenticator extends AbstractAuthenticator
{

    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');
        if(!$apiToken){
            throw new CustomUserMessageAuthenticationException('Se necesita token');

        }
        try {
            //decodificamos el token
            $decode = JWT::decode(
                $request->headers->get('X-AUTH-TOKEN'), 
                new Key($_ENV['JWT_SECRET'], 'HS512')
            );
            $user = $this->em->getRepository(Usuario::class)->findOneBy(['id'=>$decode->aud]);
            if(!$user){
                return new Passport(new UserBadge(''), new PasswordCredentials(''));
            }else{
                return new SelfValidatingPassport(new UserBadge($user->getCorreo()));
            }

        } catch (\Throwable $th) {
            return new Passport(new UserBadge(''), new PasswordCredentials(''));
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
?>