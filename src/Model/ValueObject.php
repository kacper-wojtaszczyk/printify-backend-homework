<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model;

interface ValueObject
{
    public function equals(ValueObject $other): bool;
}
