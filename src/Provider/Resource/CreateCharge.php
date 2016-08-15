<?php
namespace ScriptFUSION\Porter\Provider\Stripe\Provider\Resource;

use ScriptFUSION\Porter\Provider\Stripe\Card;
use ScriptFUSION\Porter\Provider\Stripe\Customer;
use ScriptFUSION\Porter\Provider\Stripe\Token;

class CreateCharge extends AbstractStripeResource
{
    /** @var Token|Card */
    private $source;

    private $customer;

    private $amount;

    private $currency;

    /**
     * @param Token|Card|Customer $sourceOrCustomer
     * @param int $amount
     * @param string $currency
     */
    public function __construct($sourceOrCustomer, $amount, $currency)
    {
        if ($sourceOrCustomer instanceof Customer) {
            $this->setCustomer($sourceOrCustomer);
        } else {
            $this->setSource($sourceOrCustomer);
        }

        $this->setAmount($amount);
        $this->setCurrency($currency);
    }

    protected function getResourcePath()
    {
        return 'charges';
    }

    protected function serialize()
    {
        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
        ] + (
            ($customer = $this->getCustomer()) ? ['customer' => $customer->getId()] : []
        ) + (
            ($source = $this->getSource())
                ? ($source instanceof Card)
                    ? $source->serialize()
                    : ['source' => "$source"]
                : []
        );
    }

    /**
     * @return Token|Card
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param Token|Card $source
     */
    public function setSource($source)
    {
        if (!$source instanceof Token && !$source instanceof Card) {
            throw new \InvalidArgumentException('$source must be instance of Token or Card.');
        }

        $this->source = $source;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount|0;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = "$currency";
    }
}
