<?php

namespace App\Http\Controllers;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BuyerAuthController extends Controller
{
    //
    public function buyerregister(Request $req)
    {
        //validate
        $rules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|unique:buyers',
            'password' => 'required|string|min:6'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        //create new buyer table
        $buyer = Buyer::create([
            'firstname' => $req->firstname,
            'lastname' => $req->lastname,
            'email' => $req->email,
            'password' => Hash::make($req->password)
        ]);
        $token = $buyer->createToken('Personal Access Token')->plainTextToken;
        $response = ['buyer' => $buyer, 'token' => $token];
        return response()->json($response, 200);
    }

    //Login functions
    public function buyerlogin(Request $req)
    {
        // validate inputs
        $rules = [
            'email' => 'required',
            'password' => 'required|string'
        ];
        $req->validate($rules);
        // find buyer email in buyers table
        $buyer = Buyer::where('email', $req->email)->first();
        // if buyer email found and password is correct
        if ($buyer && Hash::check($req->password, $buyer->password)) {
            $token = $buyer->createToken('Personal Access Token')->plainTextToken;
            $response = ['buyer' => $buyer, 'token' => $token];
            return response()->json($response, 200);
        }
        $response = ['message' => 'Incorrect email or password'];
        return response()->json($response, 400);
    }
}

