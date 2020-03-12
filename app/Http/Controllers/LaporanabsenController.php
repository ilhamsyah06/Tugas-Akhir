<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Absen;

class LaporanabsenController extends Controller
{
    public function index()
    {
        return view('laporan.absen.index');
    }
    
    public function getselectuser(Request $request)
    {
            if (!$request->ajax()) {
                return null;
            }
    
            $user = User::all();
    
            $data = [];
            $cacah = 0;
            foreach ($user as $i => $d) {
                    $data[$cacah] = [
                        $d->name,
                        $d->id
                    ];
    
                    $cacah++; 
                   
            }
    
            return response()->json([
                'data' => $data
            ]);
        
    }

    public function datauser(Request $request) {
    	if (!$request->ajax()) {
    		return null;
    	}

        $input = $request->all();

    	$user = $input['user'];
        $start = $input['start'];
        $end = $input['end'];

    	if ($start == '' || $end == '') {
            if ($user == null || trim($user) == '') {
                $absen = Absen::all();
            } else {
                $absen = Absen::where('user_id', $user)->get();
            }
    	} else {
    		$arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

            if ($user == null || trim($user) == '') {
                $absen = Absen::whereBetween('tgl_absen',[$from, $to], 'and')->get();
            } else {
                $absen = Absen::where('user_id', $user)->whereBetween('tgl_absen',[$from, $to], 'and')->get();
            }
    	}

    	$data = [];
        $cacah = 0;

        foreach ($absen as $i => $d) {
        	$data[$cacah] = [
                $d->user->name,
                $d->created_at->format('d/m/Y'),
                $d->created_at->format('H:i:s') 
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
