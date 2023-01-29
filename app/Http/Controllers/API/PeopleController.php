<?php

namespace App\Http\Controllers\API;

use App\Models\People;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseFormatter as JSON;
use Illuminate\Support\Facades\Auth;

class PeopleController extends Controller
{

    public function register(Request $request)
    {
        $people = new People();

        $data = $request->only('nik', 'nama', 'username', 'password', 'password_confirmation', 'telp');

        $validator = validator(
            $data,
            [
                'nik' => 'required|max:255',
                'nama' => 'required|max:255',
                'username' => 'required|max:255|unique:people',
                'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'min:6',
                'telp' => 'numeric'
            ]
        );

        if ($validator->fails()) {
            return JSON::error($validator->errors(), 'Validation error');
        } else {

            $data['password'] = Hash::make($data['password']);

            $people->fill($data);
            $people->save();

            $people = People::where('username', $request->username)->first();

            // ! CREATE BEARER TOKEN
            $token = $people->createToken('my-app-token')->plainTextToken;

            return JSON::success([
                'token' => $token,
                'people' => $people
            ], 'OK');
        }
    }

    public function login(Request $request)
    {
        // * Tangkep Hanya Request Tertentu
        $request->only('username', 'password');

        // * VALIDATE Request 
        $request->validate(
            [
                'username' => 'required|max:255',
                'password' => 'required|max:255'
            ]
        );

        $people = People::where('username', $request->username)->first();

        if (!$people) {
            return JSON::error('Error', 'No User Found..');
        }

        // ! HASH CHECK
        if (Hash::check($request->password, $people->password)) {
            $token = $people->createToken('my-app-token')->plainTextToken;
            return JSON::success([
                'token' => $token,
                'people' => $people,
            ], '200 OK');
        } else {
            return JSON::error('Error', 'Something went wrong...');
        }
    }


    public function getProfile(Request $request)
    {

        if (Auth::check()) {
            return JSON::success([
                'nik' => $request->user()->nik,
                'nama' => $request->user()->nama,
                'username' => $request->user()->username,
                'telp' => $request->user()->telp
            ], '200 OK');
        } else {
            return JSON::error('Something went wrong', 'FAILED GET DATA USER');
        }
    }
}
