<?php

namespace App\Handlers;

use DateTimeImmutable;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Configuration;


class AuthHandler
{

    // Token generation
    public function GenerateToken($user)
    {
        $signer         = new Sha256();
        $privateKey     = env('JWT_PRIVATE_KEY');
        $signingKey     = InMemory::plainText($privateKey);
        $builder        = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $now            = new DateTimeImmutable();
            
        $token = $builder->issuedBy(env('APP_URL'))
                        ->permittedFor(env('APP_URL'))
                        ->identifiedBy($user->uuid, true)
                        ->issuedAt($now)
                        ->canOnlyBeUsedAfter($now) 
                        ->expiresAt($now->modify('+6 hour'))
                        ->withClaim('id', $user->id)
                        ->getToken($signer, $signingKey);

        return $token->toString();
    }

     // Token generation
     public function InvalidateToken($user, $token)
     {
        $configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::plainText(env('JWT_PRIVATE_KEY')),
            InMemory::plainText(env('JWT_PUBLIC_KEY'))
        );

        $jwt = $configuration->parser()->parse($token);

        $id = $jwt->claims()->get('id');

        JWTAuth::getManager()->getBlacklist()->add(new \Lcobucci\JWT\Token\Plain($id));
        
     }
}