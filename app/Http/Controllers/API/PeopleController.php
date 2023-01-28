<?php

namespace App\Http\Controllers\API;

use App\Models\People;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseFormatter as Response;

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
            return Response::error($validator->errors(), 'Validation error');
        } else {

            $data['password'] = Hash::make($data['password']);

            $people->fill($data);
            $people->save();

            $people = People::where('username', $request->username)->first();

            // ! CREATE BEARER TOKEN
            $token = $people->createToken('my-app-token')->plainTextToken;

            return Response::success([
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
            return Response::error('Error', 'No User Found..');
        }

        // ! HASH CHECK
        if (Hash::check($request->password, $people->password)) {
            $token = $people->createToken('my-app-token')->plainTextToken;
            return Response::success([
                'token' => $token,
                'people' => $people,
            ], '200 OK');
        } else {
            return Response::error('Error', 'Something went wrong...');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
