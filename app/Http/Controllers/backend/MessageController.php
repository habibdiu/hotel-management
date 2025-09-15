<?php

namespace App\Http\Controllers\backend;


use PDOException;
use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Middleware\backendAuthenticationMiddleware;
use Illuminate\Support\Facades\File;

class MessageController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            backendAuthenticationMiddleware::class
        ];
    }


    public function message_add(Request $request)
    {
        $data = [];

        if ($request->isMethod('post')) {
            $photo = $request->file('photo');
            //Upload Image
            $photo_name = null;
            if(isset($request->photo)){
                
                $photo_extension = $photo->getClientOriginalExtension();
                $photo_name = 'backend_assets/images/advocates/' . uniqid() . '.' . $photo_extension;
            }

            try {
                Messages::create([
                    'title' => $request->title,
                    'name' => $request->name,
                    'position' => $request->position,
                    'company' => $request->company,
                    'description' => $request->description,
                    'photo' => $photo_name,
                    'priority' => $request->priority,
                ]);
                return redirect()->route('admin.message.list')->with('success', 'Updated Successfully');
            } catch (PDOException $e) {
                Log::error('Message creation failed: ' . $e->getMessage());
                return back()->with('error', 'Failed: ' . $e->getMessage());
            }
        }

        $data['active_menu'] = 'message_add';
        $data['page_title'] = 'Message Add';
        return view('backend.pages.message_add', compact('data'));
    }



public function message_edit(Request $request, $id)
{
    $data = [];
    $data['message'] = Messages::find($id);

    if (!$data['message']) {
        return back()->with('error', 'Message not found.');
    }

    if ($request->isMethod('post')) {
        $old_photo = $data['message']->photo;
        $photo_name = $old_photo;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            
            $photo_extension = $photo->getClientOriginalExtension();
            $photo_name = 'backend_assets/images/advocates/' . uniqid() . '.' . $photo_extension;

            
            if (!empty($old_photo) && File::exists($old_photo)) {
                File::delete($old_photo);
            }

            
            try {
                Image::make($photo)->resize(300, 300)->save(public_path($photo_name));
            } catch (\Exception $e) {
                Log::error('Image resize failed: ' . $e->getMessage());
                return back()->with('error', 'Image processing failed. Please try again.');
            }
        }

        
        try {
            $data['message']->update([
                'title' => $request->title,
                'name' => $request->name,
                'position' => $request->position,
                'company' => $request->company,
                'description' => $request->description,
                'photo' => $photo_name,
                'priority' => $request->priority,
            ]);
            return redirect()->route('admin.message.list')->with('success', 'Updated Successfully');
        } catch (PDOException $e) {
            Log::error('Message update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed! Please Try Again');
        }
    }

    $data['active_menu'] = 'message_edit';
    $data['page_title'] = 'Message Edit';
    return view('backend.pages.message_edit', compact('data'));
}



    public function message_list()
    {
        $data = [];
        $data['message_list'] = DB::table('messages')->select('id','title','name','position','company','description','photo','priority')->get();
        $data['active_menu'] = 'message_list';
        $data['page_title'] = 'Message List';
        return view('backend.pages.message_list', compact('data'));
    }

    public function message_delete($id)
    {
        
        $server_response =  ['status' => 'FAILED', 'message' => 'Not Found'];
        $message = Messages::find($id);
        if ($message) {
            if(File::exists($message->photo)){
                  File::delete($message->photo);
            }
            $message->delete();
            $server_response =  ['status' => 'SUCCESS', 'message' => 'Deleted Successfully'];
        } else {
            $server_response =  ['status' => 'FAILED', 'message' => 'Not Found'];
        }
        echo json_encode($server_response);
    }
}
