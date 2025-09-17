<?php

namespace App\Http\Controllers\Backend;

use PDOException;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Middleware\backendAuthenticationMiddleware;

class RoomController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            backendAuthenticationMiddleware::class
        ];
    }


    public function room_add(Request $request)
    {
        $data = [];

        if ($request->isMethod('post')) {
            $photo = $request->file('photo');
            $photo_name = null;
            if(isset($request->photo)){
                
                $photo_extension = $photo->getClientOriginalExtension();
                $photo_name = 'backend_assets/images/rooms/' . uniqid() . '.' . $photo_extension;
            }

            try {
                Room::create([
                    'room_name' => $request->room_name,
                    'room_type' => $request->room_type,
                    'price_per_night' => $request->price_per_night,
                    'amenities' => $request->amenities,
                    'floor_number' => $request->floor_number,
                    'description' => $request->description,
                    'photo' => $photo_name
                ]);
                return back()->with('success', 'Added Successfully');
            } catch (PDOException $e) {
                return back()->with('error', 'Failed: ' . $e->getMessage());
            }
        }

        $data['active_menu'] = 'room_add';
        $data['page_title'] = 'Room Add';

        $data['roomTypes'] = RoomType::with('categories.subcategories')->get();
        
        return view('backend.pages.room_add', compact('data'));
    }



public function room_edit(Request $request, $id)
{
    $data = [];
    $data['room'] = Room::find($id);

    if (!$data['room']) {
        return back()->with('error', 'room not found.');
    }

    if ($request->isMethod('post')) {
        $old_photo = $data['room']->photo;
        $photo_name = $old_photo;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            
            $photo_extension = $photo->getClientOriginalExtension();
            $photo_name = 'backend_assets/images/rooms/' . uniqid() . '.' . $photo_extension;

            
            if (!empty($old_photo) && File::exists($old_photo)) {
                File::delete($old_photo);
            }

            
            try {
                Image::make($photo)->resize(300, 300)->save(public_path($photo_name));
            } catch (\Exception $e) {
                return back()->with('error', 'Image processing failed. Please try again.');
            }
        }

        
        try {
            $data['room']->update([
                'room_name' => $request->room_name,
                'room_type' => $request->room_type,
                'price_per_night' => $request->price_per_night,
                'amenities' => $request->amenities,
                'floor_number' => $request->floor_number,
                'description' => $request->description,
                'photo' => $photo_name
            ]);
            return back()->with('success', 'Updated Successfully');
        } catch (PDOException $e) {
            return back()->with('error', 'Failed! Please Try Again');
        }
    }

    $data['active_menu'] = 'room_edit';
    $data['page_title'] = 'Room Edit';
    return view('backend.pages.room_edit', compact('data'));
}



    public function room_list()
    {
        $data = [];
        $data['room_list'] = Room::with(['roomType', 'category', 'subcategory'])->get();
        $data['active_menu'] = 'room_list';
        $data['page_title'] = 'Room List';
        return view('backend.pages.room_list', compact('data'));
    }


    public function room_delete($id)
    {
        $server_response =  ['status' => 'FAILED', 'message' => 'Not Found'];
        $room = Room::find($id);
        if ($room) {
            if(File::exists($room->photo)){
                  File::delete($room->photo);
            }
            $room->delete();
            $server_response =  ['status' => 'SUCCESS', 'room' => 'Deleted Successfully'];
        } else {
            $server_response =  ['status' => 'FAILED', 'room' => 'Not Found'];
        }
        echo json_encode($server_response);
    }
}
