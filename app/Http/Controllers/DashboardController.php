<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Uang_modal_kasir;

use App\Absen;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tanggal = date('Y-m-d');
        $uangawal = Uang_modal_kasir::where('tanggal', $tanggal);
        return view('home',compact('uangawal'));
    }

    public function template()
    {
        return view('layouts.master');
    }
}
