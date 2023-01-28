<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter as Response;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{

    public function register(Request $request)
    {
        $officer = new Officer();

        $data = $request->only('nama_petugas', 'username', 'password', 'password_confirmation', 'telp', 'level');

        $validator = validator(
            $data,
            [
                'nama_petugas' => 'required|max:255',
                'username' => 'required|max:255|unique:officers',
                'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'min:6',
                'telp' => 'numeric'
            ]
        );

        if ($validator->fails()) {
            return Response::error($validator->errors(), 'Validation error');
        } else {

            $data['password'] = Hash::make($data['password']);

            $officer->fill($data);
            $officer->save();

            $officer = Officer::where('username', $request->username)->first();

            // ! CREATE BEARER TOKEN
            $token = $officer->createToken('my-app-token')->plainTextToken;

            return Response::success([
                'token' => $token,
                'officer' => $officer
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

        $officer = Officer::where('username', $request->username)->first();

        if (!$officer) {
            return Response::error('Error', 'No User Found..');
        }

        // ! HASH CHECK
        if (Hash::check($request->password, $officer->password)) {
            $token = $officer->createToken('my-app-token')->plainTextToken;
            return Response::success([
                'token' => $token,
                'officer' => $officer,
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
