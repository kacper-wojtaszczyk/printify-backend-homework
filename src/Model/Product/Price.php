<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product;

use KacperWojtaszczyk\PrintifyBackendHomework\Model\ValueObject;
use Money\Currency;
use Money\Money;

final class Price implements ValueObject
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function fromMoney(Money $money): self
    {
        $self = new self((int) $money->getAmount(), $money->getCurrency()->getCode());
        return $self;
    }

    public function __toString(): string
    {
        return $this->getMoney()->getAmount() . " " . $this->getMoney()->getCurrency()->getCode();
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getMoney()
    {
        return new Money($this->amount, new Currency($this->currency));
    }

    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }
        return ($this->getAmount() === $other->getAmount()) && ($this->getCurrency() === $other->getCurrency());
    }
}