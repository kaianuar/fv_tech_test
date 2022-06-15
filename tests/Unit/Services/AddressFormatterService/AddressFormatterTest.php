<?php

namespace Tests\Unit\Services\AddressFormatterService;

use App\Services\AddressFormatterService\AddressFormatter;
use Illuminate\Http\Request;
use Tests\TestCase;

class AddressFormatterTest extends TestCase
{
    protected string $address1;

    protected string $address2;

    protected string $address3;

    protected string $longAddress;

    public function setUp(): void
    {
        parent::setUp();
        $this->address1 = 'Business Office, Malcolm Long';
        $this->address2 = '92911 Proin Road Lake Charles';
        $this->address3 = 'Maine';
    }

    public function test_can_create_new_instance_from_request()
    {
        $input = [
            'address1' => $this->address1,
            'address2' => $this->address2,
            'address3' => $this->address3,
        ];

        $request = new Request($input);
        $addressFormatter = AddressFormatter::makeFromRequest($request);

        $this->assertInstanceOf(AddressFormatter::class, $addressFormatter);
        $this->assertEquals($this->address1, $addressFormatter->getLine1());
        $this->assertEquals($this->address2, $addressFormatter->getLine2());
        $this->assertEquals($this->address3, $addressFormatter->getLine3());
    }

    public function test_no_changes_to_address_if_within_limit()
    {
        $input = [
            'address1' => $this->address1,
        ];

        $request = new Request($input);
        $addressFormatter = AddressFormatter::makeFromRequest($request);
        $addressFormatter->resolveAddress();

        $this->assertEquals($this->address1, $addressFormatter->getLine1());
    }

    public function test_extra_string_from_address_1_moved_to_address_2()
    {
        $input = [
            'address1' => $this->address1 . ' ' . $this->address2,
        ];

        $request = new Request($input);
        $addressFormatter = AddressFormatter::makeFromRequest($request);
        $addressFormatter->resolveAddress();

        $this->assertEquals($this->address1, $addressFormatter->getLine1());
        $this->assertEquals($this->address2, $addressFormatter->getLine2());
    }

    public function test_extra_string_from_address_1_moved_to_address_2_and_address3()
    {
        $input = [
            'address1' => implode(' ', [$this->address1, $this->address2, $this->address3]),
        ];

        $request = new Request($input);
        $addressFormatter = AddressFormatter::makeFromRequest($request);
        $addressFormatter->resolveAddress();

        $this->assertEquals($this->address1, $addressFormatter->getLine1());
        $this->assertEquals($this->address2, $addressFormatter->getLine2());
        $this->assertEquals($this->address3, $addressFormatter->getLine3());
    }

    public function test_address_line_is_not_cut_mid_word()
    {
        $input = [
            'address1' => 'Business Office, Malcolm Longisland'
        ];

        $request = new Request($input);
        $addressFormatter = AddressFormatter::makeFromRequest($request);
        $addressFormatter->resolveAddress();

        $this->assertEquals('Business Office, Malcolm', $addressFormatter->getLine1());
        $this->assertEquals('Longisland', $addressFormatter->getLine2());
    }

    public function test_address_2_is_not_removed_when_appended_extra_string()
    {
        $input = [
            'address1' => 'Business Office, Malcolm Longisland',
            'address2' => '92911 Proin Road'
        ];

        $request = new Request($input);
        $addressFormatter = AddressFormatter::makeFromRequest($request);
        $addressFormatter->resolveAddress();

        $this->assertEquals('Business Office, Malcolm', $addressFormatter->getLine1());
        $this->assertEquals('Longisland 92911 Proin Road', $addressFormatter->getLine2());
    }

    public function test_address_3_is_not_removed_when_appended_extra_string()
    {
        $input = [
            'address1' => 'Business Office, Malcolm Longisland',
            'address2' => '92911 Proin Road Lake Charles',
            'address3' => 'Maine',
        ];

        $request = new Request($input);
        $addressFormatter = AddressFormatter::makeFromRequest($request);
        $addressFormatter->resolveAddress();

        $this->assertEquals('Business Office, Malcolm', $addressFormatter->getLine1());
        $this->assertEquals('Longisland 92911 Proin Road', $addressFormatter->getLine2());
        $this->assertEquals('Lake Charles Maine', $addressFormatter->getLine3());
    }
}
