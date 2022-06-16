<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressFormatterRequest;
use App\Services\AddressFormatterService\AddressFormatter;
use Illuminate\Http\JsonResponse;

class AddressFormatterController extends Controller
{
    public function formatAddress(AddressFormatterRequest $request): JsonResponse
    {
        $addressFormatter = AddressFormatter::makeFromRequest($request);

        return response()->json(
            $addressFormatter
                ->resolveAddress()
                ->toArray()
        );
    }
}
