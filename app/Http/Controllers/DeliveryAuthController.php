<?php

namespace App\Http\Controllers;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeliveryAuthController extends Controller
{
    //

    //
    public function deliveryregister(Request $req)
    {
        //validate
        $rules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|unique:deliveries',
            'cnicno' => 'required|string|min:13|max:13|unique:deliveries',
            'address' => 'required|string',
            'cellno' => 'required|string|min:11|max:11|unique:deliveries',
            'postalcode' => 'required|string',
            'password' => 'required|string|min:6'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        //create new Delivery table
        $delivery = Delivery::create([
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
        $token = $delivery->createToken('Personal Access Token')->plainTextToken;
        $response = ['delivery' => $delivery, 'token' => $token];
        return response()->json($response, 200);
    }

    //Login functions
    public function deliverylogin(Request $req)
    {
        // validate inputs
        $rules = [
            'email' => 'required',
            'password' => 'required|string'
        ];
        $req->validate($rules);
        // find Delivery email in Deliverys table
        $delivery = Delivery::where('email', $req->email)->first();
        // if Delivery email found and password is correct
        if ($delivery && Hash::check($req->password, $delivery->password)) {
            $token = $delivery->createToken('Personal Access Token')->plainTextToken;
            $response = ['delivery' => $delivery, 'token' => $token];
            return response()->json($response, 200);
        }
        $response = ['message' => 'Incorrect email or password'];
        return response()->json($response, 400);
    }
}


