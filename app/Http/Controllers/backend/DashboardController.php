<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Middleware\backendAuthenticationMiddleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller implements HasMiddleware
{

  public static function middleware(): array
  {
    return [
      backendAuthenticationMiddleware::class
    ];
  }

  public function dashboard()
  {
    $data = array();
    $data['total_student'] = 0;
    $data['active_menu'] = 'dashboard';
    $data['page_title'] = 'Dashboard';
    return view('backend.pages.dashboard', compact('data'));
  }
}
