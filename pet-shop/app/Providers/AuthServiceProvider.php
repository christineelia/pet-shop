<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\Clock\SystemClock;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

        Auth::viaRequest('jwt', function ($request) {
            

            try {
                $configuration = Configuration::forAsymmetricSigner(
                new Sha256(),
                InMemory::plainText(env('JWT_PRIVATE_KEY')),
                InMemory::plainText(env('JWT_PUBLIC_KEY'))
            );
                $jwt = $configuration->parser()->parse($request->bearerToken());

                if (! $jwt instanceof UnencryptedToken) {
                    throw new ConstraintViolation('You should pass a plain token');
                }

                // Validate based on constraints
                $validator = new Validator();
                    
                $validator->assert($jwt, new IssuedBy(env('APP_URL')));
                $validator->assert($jwt, new PermittedFor(env('APP_URL')));
                $validator->assert($jwt, new StrictValidAt( SystemClock::fromUTC()));

                
                return \App\Models\User::find($jwt->claims()->get('id'));
                //return Auth::loginUsingId($jwt->claims()->get('jti'));
                
            } catch (\Lcobucci\JWT\Exception $e) {
                return null;
            }
        });
        
    }
}
