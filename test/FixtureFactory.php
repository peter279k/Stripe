<?php
namespace ScriptFUSIONTest\Porter\Provider\Stripe;

use ScriptFUSION\Porter\Porter;
use ScriptFUSION\Porter\Provider\Stripe\Card;
use ScriptFUSION\Porter\Provider\Stripe\Customer;
use ScriptFUSION\Porter\Provider\Stripe\Provider\Resource\CreateCustomer;
use ScriptFUSION\Porter\Provider\Stripe\Provider\Resource\CreateToken;
use ScriptFUSION\Porter\Provider\Stripe\Provider\StripeProvider;
use ScriptFUSION\Porter\Provider\Stripe\Token;
use ScriptFUSION\Porter\Specification\ImportSpecification;
use ScriptFUSION\StaticClass;

final class FixtureFactory
{
    use StaticClass;

    public static function createPorter()
    {
        return (new Porter)
            ->registerProvider((new StripeProvider)->setApiKey($_SERVER['STRIPE_API_KEY']));
    }

    public static function createCard()
    {
        return new Card('4242424242424242', 12, date('Y') + 1, '123');
    }

    public static function createToken()
    {
        return new Token(
            self::createPorter()->importOne(new ImportSpecification(new CreateToken(self::createCard())))['id']
        );
    }

    public static function createCustomer()
    {
        return new Customer(
            self::createPorter()->importOne(new ImportSpecification(new CreateCustomer(self::createCard())))['id']
        );
    }
}