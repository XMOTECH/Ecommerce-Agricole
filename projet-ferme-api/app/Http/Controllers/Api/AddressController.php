<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    //
    public function index(Request $request){
        $addresse = $request->user()->addresses;
        return response()->json($addresse);
    }

    public function store(AddressRequest $request){
        $addresse = $request->user()->addresses()->create($request->validated());
        return response()->json($addresse);
    }
}
