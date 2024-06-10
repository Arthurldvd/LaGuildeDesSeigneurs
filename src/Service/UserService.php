<?php

// src/Service/UserService.php
namespace App\Service;

use App\Entity\User;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;
use DateTimeImmutable;
use App\Repository\UserRepository;

class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }
    // Gets the token
    public function getToken(User $user): string
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm = new Sha256();
        $signingKey = InMemory::plainText(random_bytes(32)); // voir note sur la sécurité
        $now = new DateTimeImmutable();
        $token = $tokenBuilder
            ->issuedBy('http://127.0.0.1:8001') // Configures the issuer (iss claim)
            ->permittedFor('http://127.0.0.1:8001') // Configures the audience (aud claim)
            ->identifiedBy(hash('sha1', $user->getEmail())) // Configures the id (jti claim)
            ->issuedAt($now) // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($now->modify('+1 minute')) // Configures the time that the token can be used (nbf claim)
            ->expiresAt($now->modify('+2 hour')) // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', $user->getId()) // Configures claims part
            ->withClaim('email', $user->getEmail()) // Configures claims part
            ->getToken($algorithm, $signingKey) // Builds a new token
        ;
        return $token->toString();
    }

    public function parseToken(string $token)
    {
        $parser = new Parser(new JoseEncoder());
        try {
            return $parser->parse($token);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            echo 'Oh no, an error: ' . $e->getMessage();
        }
        assert($token instanceof UnencryptedToken);
    }

    public function findOneByEmail(string $token)
{
$tokenParse = $this->parseToken($token);
if (null !== $tokenParse) {
// $tokenParse->claims()->get('email') récupére l'email depuis le token
$user = $this->userRepository->findOneByEmail($tokenParse->claims()->get('email'));
return null !== $user ? $user->getEmail() : false;
}
return false;
}
}
