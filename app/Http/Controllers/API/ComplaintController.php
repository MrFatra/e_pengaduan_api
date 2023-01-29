<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter as JSON;
use App\Models\People;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{

    public function complaint(Request $request)
    {
        $data = $request->all();

        $request->validate([
            'tgl_pengaduan' => 'required|date',
            // 'nik' => 'required',
            'isi_laporan' => 'required',
            // 'foto' => 'required',
        ]);

        $complaint = new Complaint();

        $complaint->nik = Auth::user()->nik;

        $result = $complaint->fill($data)->save();

        if ($result) {
            return JSON::success($result, '200 OK');
        } else {
            return JSON::success($result, '200 OK');
        }
    }

    public function reports(Request $request)
    {

        // $user = People::where('nik', $request->user()->nik)->first();

        $listReports = Complaint::where('nik', $request->user()->nik)->get();

        if (Auth::check()) {
            return JSON::success($listReports, 'Total Data: ' . count($listReports));
        } else {
            return JSON::error('Something went wrong...', 'FAILED');
        }
    }
}
