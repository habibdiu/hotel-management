<?php

namespace App\Http\Controllers\backend;

use PDOException;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Middleware\backendAuthenticationMiddleware;

class RoomTypeController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            backendAuthenticationMiddleware::class
        ];
    }

    public function room_type( Request $request)
    {
        $data = [];

        if ($request->isMethod('post')) {
            try {
                RoomType::create([
                    'room_type_name' => $request->room_type_name
                ]);
                return back()->with('success', 'Added Successfully');
            } catch (PDOException $e) {
                return back()->with('error', 'Failed: ' . $e->getMessage());
            }
        }
        $data['room_types'] = RoomType::orderBy('id', 'DESC')->paginate(10);
        $data['active_menu'] = 'room_type';
        $data['page_title'] = 'Room Type';
        return view('backend.pages.room_type', compact('data'));
    }


    public function room_type_update(Request $request, $id)
    {
        $data = [];
        $data['room_types'] = RoomType::find($id);

        if (!$data['room_types']) {
            return back()->with('error', 'Not found.');
        }

        if ($request->isMethod('post')) {
            try {
                $data['room_types']->update([
                    'room_type_name' => $request->room_type_name
                ]);
                return back()->with('success', 'Updated Successfully');
            } catch (PDOException $e) {
                return back()->with('error', 'Failed! Please Try Again');
            }
        }

        $data['active_menu'] = 'room_type';
        $data['page_title'] = 'Room Type';
        return view('backend.pages.room_type', compact('data'));
    }

    public function room_type_delete($id)
    {
        $room_type = RoomType::find($id);

        if ($room_type) {
            $room_type->delete();
            return back()->with('success', 'Deleted Successfully');
        }

        return back()->with('error', 'Room Type Not Found');
    }

}
