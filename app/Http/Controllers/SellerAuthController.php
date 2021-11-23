<?php

namespace App\Http\Controllers;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SellerAuthController extends Controller
{
    //

    //
    public function sellerregister(Request $req)
    {
        //validate
        $rules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|unique:sellers',
            'cnicno' => 'required|string|min:13|max:13|unique:sellers',
            'address' => 'required|string',
            'cellno' => 'required|string|min:11|max:11|unique:sellers',
            'city' => 'required|string',
            'postalcode' => 'required|string',
            'password' => 'required|string|min:6'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        //create new seller table
        $seller = Seller::create([
            'firstname' => $req->firstname,
            'lastname' => $req->lastname,
            'email' => $req->email,
            'cnicno'=> $req->cnicno,
            'address'=> $req->address,
            'cellno'=> $req->cellno,
            'city'=> $req->city,
            'postalcode'=> $req->postalcode,
            'password' => Hash::make($req->password)
        ]);
        $token = $seller->createToken('Personal Access Token')->plainTextToken;
        $response = ['seller' => $seller, 'token' => $token];
        return response()->json($response, 200);
    }

    //Login functions
    public function sellerlogin(Request $req)
    {
        // validate inputs
        $rules = [
            'email' => 'required',
            'password' => 'required|string'
        ];
        $req->validate($rules);
        // find seller email in sellers table
        $seller = seller::where('email', $req->email)->first();
        // if seller email found and password is correct
        if ($seller && Hash::check($req->password, $seller->password)) {
            $token = $seller->createToken('Personal Access Token')->plainTextToken;
            $response = ['seller' => $seller, 'token' => $token];
            return response()->json($response, 200);
        }
        $response = ['message' => 'Incorrect email or password'];
        return response()->json($response, 400);
    }
}


