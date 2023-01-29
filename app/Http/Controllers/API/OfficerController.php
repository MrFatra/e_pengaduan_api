<?php

namespace App\Http\Controllers\API;

use App\Models\Officer;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseFormatter as JSON;
use App\Models\Response;

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
            return JSON::error($validator->errors(), 'Validation error');
        } else {

            $data['password'] = Hash::make($data['password']);

            $officer->fill($data);
            $officer->save();

            $officer = Officer::where('username', $request->username)->first();

            // ! CREATE BEARER TOKEN
            $token = $officer->createToken('my-app-token')->plainTextToken;

            return JSON::success([
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
            return JSON::error('Error', 'No User Found..');
        }

        // ! HASH CHECK
        if (Hash::check($request->password, $officer->password)) {
            $token = $officer->createToken('my-app-token')->plainTextToken;
            return JSON::success([
                'token' => $token,
                'officer' => $officer,
            ], '200 OK');
        } else {
            return JSON::error('Error', 'Something went wrong...');
        }
    }

    public function listOfficers()
    {

        $listOfficers = Officer::all();

        if (Auth::check()) {
            return JSON::success($listOfficers, '200 OK');
        } else {
            return JSON::error('Something went wrong', 'FAILED GET DATA OFFICERS');
        }
    }

    public function delete(Request $request)
    {
        $idPetugas = $request->only('id');

        $validator = validator($idPetugas, [
            'id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return JSON::error($validator->errors(), 'Validation error');
        }
        if (Auth::check()) {
            $officer = Officer::destroy($idPetugas);
            return JSON::success($officer, '200 OK');
        } else {
            return JSON::error('Something went wrong', 'FAILED DELETE DATA');
        }
    }

    public function upcoming()
    {
        // $data = $request->only('id_petugas');

        // $validator = validator($data, [
        //     'id_petugas' => 'required|numeric'
        // ]);

        // if ($validator->fails()) {
        //     return JSON::error($validator->getMessageBag(), 'Validation Error');
        // }

        if (Auth::check()) {
            $complaints = Complaint::where('status', 'proses')->get();

            return JSON::success($complaints, '200 OK');
        } else {
            return JSON::error([], 'FAILED');
        }
    }

    public function verification(Request $request)
    {
        $data = $request->only('status', 'id_pengaduan');

        $validator = validator($data, [
            'status' => 'required',
            'id_pengaduan' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return JSON::error($validator->getMessageBag(), 'Validation Error');
        }

        if (Auth::check()) {
            Complaint::where('id_pengaduan', $request->id_pengaduan)->update([
                'status' => $request->status
            ]);

            // $verication->status = $request->status;
            // $verication->save();

            return JSON::success('Update Berhasil', '200 OK');
        } else {
            return JSON::error('FAILED', 'Verification Failed');
        }
    }

    public function makeResponse(Request $request)
    {
        $data = $request->only('id_pengaduan', 'id_petugas', 'tgl_tanggapan', 'tanggapan');

        $validator = validator($data, [
            'id_pengaduan' => 'required|numeric',
            'id_petugas' => 'required|numeric',
            'tgl_tanggapan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return JSON::error($validator->getMessageBag(), 'Validation Error');
        }

        if (Auth::check()) {
            $response = new Response();

            $petugas = Officer::where('id_petugas', $request->id_petugas)->first();
            $pengaduan = Complaint::where('id_pengaduan', $request->id_pengaduan)->first();

            if ($petugas && $pengaduan) {

                $response->fill($data);
                $response->save();

                return JSON::success($response, 'Response Send');
            } else {
                return JSON::error('Data is Not Valid', 'Response Failed');
            }
        } else {
            return JSON::error('Error', 'Failed to send Response');
        }
    }
}
