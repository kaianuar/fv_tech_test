<?php

namespace App\Services\AddressFormatterService;

use Illuminate\Http\Request;

class AddressFormatter
{
    const MAX_STRING_LENGTH = 30;

    protected string $line1;

    protected string $line2;

    protected string $line3;

    protected string $fullAddress;

    protected array $unAllocatedAddress;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return static
     */
    public static function makeFromRequest(Request $request): static
    {
        $instance = new static();

        $instance
            ->setLine1($request->input('address1'))
            ->setLine2($request->input('address2') ?: '')
            ->setLine3($request->input('address3') ?: '');

        return $instance;
    }

    /**
     * @return self
     */
    public function resolveAddress(): static
    {
        $this->initAddressValues();

        if (! empty($this->getUnAllocatedAddress())) {
            $this->setAddressLine('setLine1');
        }

        if (! empty($this->getUnAllocatedAddress())) {
            $this->setAddressLine('setLine2');
        }

        if (! empty($this->getUnAllocatedAddress())) {
            $this->setAddressLine('setLine3');
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'address1' => $this->getLine1(),
            'address2' => $this->getLine2(),
            'address3' => $this->getLine3(),
        ];
    }

    /**
     * @return void
     */
    protected function initAddressValues()
    {
        // Check if any of the address lines requires trimming
        if (
            strlen($this->getLine1()) > self::MAX_STRING_LENGTH
            || strlen($this->getLine2()) > self::MAX_STRING_LENGTH
            || strlen($this->getLine3()) > self::MAX_STRING_LENGTH
        ) {
            $fullAddressString = implode(' ', [$this->getLine1(), $this->getLine2(), $this->getLine3()]);
            $this->setFullAddress(trim($fullAddressString));
            $this->setUnAllocatedAddress(explode(' ', $this->getFullAddress()));
            return;
        }

        $this->setUnAllocatedAddress([]);
    }

    /**
     * Sets the address line using the $setterName variable that calls the method with the same name
     * Updates any remaining words in the address to $this->unAllocatedAddress
     *
     * @param  string  $setterName
     * @return void
     */
    protected function setAddressLine(string $setterName)
    {
        $addressString = '';
        $addressWords = $this->getUnAllocatedAddress();

        foreach ($addressWords as $index => $addressWord) {
            if (strlen($addressString) + strlen($addressWord) <= self::MAX_STRING_LENGTH) {
                $addressString .= ' '.$addressWord;
                unset($addressWords[$index]);
            } else {
                break;
            }
        }

        $this->$setterName(trim($addressString));
        $this->setUnAllocatedAddress($addressWords);
    }

    /**
     * @return string
     */
    public function getLine1(): string
    {
        return $this->line1;
    }

    /**
     * @param  string  $line1
     * @return self
     */
    protected function setLine1(string $line1): self
    {
        $this->line1 = $line1;
        return $this;
    }

    /**
     * @return string
     */
    public function getLine2(): string
    {
        return $this->line2;
    }

    /**
     * @param  string  $line2
     * @return self
     */
    protected function setLine2(string $line2): self
    {
        $this->line2 = $line2;
        return $this;
    }

    /**
     * @return string
     */
    public function getLine3(): string
    {
        return $this->line3;
    }

    /**
     * @param  string  $line3
     * @return self
     */
    protected function setLine3(string $line3): self
    {
        $this->line3 = $line3;
        return $this;
    }

    /**
     * @return string
     */
    protected function getFullAddress(): string
    {
        return $this->fullAddress;
    }

    /**
     * @param  string  $fullAddress
     * @return self
     */
    protected function setFullAddress(string $fullAddress): self
    {
        $this->fullAddress = $fullAddress;
        return $this;
    }

    /**
     * @return array
     */
    protected function getUnAllocatedAddress(): array
    {
        return $this->unAllocatedAddress;
    }

    /**
     * @param  array  $unAllocatedAddress
     * @return self
     */
    protected function setUnAllocatedAddress(array $unAllocatedAddress): self
    {
        $this->unAllocatedAddress = $unAllocatedAddress;
        return $this;
    }
}
