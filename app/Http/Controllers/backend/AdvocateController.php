<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Middleware\backendAuthenticationMiddleware;
use App\Models\Advocate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDOException;
use Intervention\Image\Facades\Image;

class AdvocateController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            backendAuthenticationMiddleware::class
        ];
    }

    public function advocate_add(Request $request)
    {
        $data = [];
        if ($request->isMethod('post')) {
            $photo  = $request->file('photo');
            if ($photo) {



                $photo_extension = $photo->getClientOriginalExtension();
                $photo_name = 'backend_assets/images/advocates/' . uniqid() . '.' . $photo_extension;
                $image = Image::make($photo);
                $image->resize(300, 300);
                $image->save($photo_name);
            } else {
                $photo_name = null;
            }
            try {
                Advocate::create([
                    'name' => $request->name,
                    'barcouncil_sonod_no' => $request->barcouncil_sonod_no,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'photo' => $photo_name,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'case_limit' => $request->case_limit,
                    'storage_limit' => $request->storage_limit,
                    'sms_username' => $request->sms_username,
                    'sms_password' => bcrypt($request->sms_password),
                    'active_status' => $request->active_status,
                ]);
                return back()->with('success', 'Added Successfully');
            } catch (PDOException $e) {
                return back()->with('error', 'Failed Please Try Again');
            }
        }
        $data['active_menu'] = 'advocate_add';
        $data['page_title'] = 'Advocate Add';
        return view('backend.pages.advocate_add', compact('data'));
    }
    public function advocate_edit(Request $request, $id)
    {
        $data = [];
        $data['advocate'] = Advocate::find($id);
        if ($request->isMethod('post')) {
            $old_photo = $data['advocate']->photo;
            $photo  = $request->file('photo');
            if ($photo) {
                $photo_extension = $photo->getClientOriginalExtension();
                $photo_name = 'backend_assets/images/advocates/' . uniqid() . '.' . $photo_extension;
                $image = Image::make($photo);
                $image->resize(300, 300);
                $image->save($photo_name);
                if (File::exists($old_photo)) {
                    File::delete($old_photo);
                }
            } else {
                $photo_name = $old_photo;
            }
            if ($request->password) {
                $password = bcrypt($request->password);
            } else {
                $password = $data['advocate']->password;
            }
            if ($request->sms_password) {
                $sms_password = bcrypt($request->sms_password);
            } else {
                $sms_password = $data['advocate']->sms_password;
            }
            try {
                $data['advocate']->update([
                    'name' => $request->name,
                    'barcouncil_sonod_no' => $request->barcouncil_sonod_no,
                    'email' => $request->email,
                    'password' => $password,
                    'photo' => $photo_name,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'case_limit' => $request->case_limit,
                    'storage_limit' => $request->storage_limit,
                    'sms_username' => $request->sms_username,
                    'sms_password' => $sms_password,
                    'active_status' => $request->active_status,
                ]);
                return back()->with('success', 'Updated Successfully');
            } catch (PDOException $e) {
                return back()->with('error', 'Failed Please Try Again');
            }
        }
        $data['active_menu'] = 'advocate_edit';
        $data['page_title'] = 'Advocate Edit';
        return view('backend.pages.advocate_edit', compact('data'));
    }
    public function advocate_list()
    {
        $data = [];
        $data['advocate_list'] = DB::table('advocates')->select('id', 'name', 'active_status', 'barcouncil_sonod_no', 'email', 'photo', 'start_date', 'end_date', 'case_limit', 'storage_limit')->get();
        $data['active_menu'] = 'advocate_list';
        $data['page_title'] = 'Advocate List';
        return view('backend.pages.advocate_list', compact('data'));
    }
    public function advocate_delete($id)
    {
        $server_response =  ['status' => 'FAILED', 'message' => 'Not Found'];
        $advocate = Advocate::find($id);
        if ($advocate) {
            if (File::exists($advocate->photo)) {
                File::delete($advocate->photo);
            }
            $advocate->delete();
            $server_response =  ['status' => 'SUCCESS', 'message' => 'Deleted Successfully'];
        } else {
            $server_response =  ['status' => 'FAILED', 'message' => 'Not Found'];
        }
        echo json_encode($server_response);
    }
}
