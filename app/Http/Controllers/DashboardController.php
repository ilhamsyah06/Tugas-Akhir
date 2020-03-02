<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Absen;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $id =  Auth::user()->id;
        $tanggal = date('Y-m-d');
        $now = Carbon::now()->format('Y-m-d');
        $uangawal = DB::table('uang_modal_kasir')->where('tanggal', $now)->limit(1)->get();
        $count = Absen::where('tgl_absen','=',$now)->where('user_id',$id)->count();
        return view('home',['uangawal' => $uangawal,'count' => $count]);
    }

    public function template()
    {
        return view('layouts.master');
    }
}
