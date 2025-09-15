<?php

namespace App\Http\Controllers\backend;

use PDOException;
use App\Models\Category;
use App\Models\RoomType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Middleware\backendAuthenticationMiddleware;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            backendAuthenticationMiddleware::class
        ];
    }

    public function room_category( Request $request)
    {
        $data = [];

        if ($request->isMethod('post')) {
            try {
                Category::create([
                    'category_name' => $request->category_name,
                    'room_type_id' => $request->room_type_id
                ]);
                return back()->with('success', 'Added Successfully');
            } catch (PDOException $e) {
                return back()->with('error', 'Failed: ' . $e->getMessage());
            }
        }

        $data['room_types'] = RoomType::all();
        $data['categories'] = Category::with('room_type')->orderBy('id', 'DESC')->paginate(10);
        $data['active_menu'] = 'category';
        $data['page_title'] = 'Room Category';
        return view('backend.pages.category', compact('data'));
    }

    public function room_category_update(Request $request, $id)
    {
        $data = [];
        $data['categories'] = Category::find($id);

        if (!$data['categories']) {
            return back()->with('error', 'Not found.');
        }

        if ($request->isMethod('post')) {
            try {
                $data['categories']->update([
                    'category_name' => $request->category_name,
                    'room_type_id' => $request->room_type_id
                ]);
                return back()->with('success', 'Updated Successfully');
            } catch (PDOException $e) {
                return back()->with('error', 'Failed! Please Try Again');
            }
        }

        $data['active_menu'] = 'category';
        $data['page_title'] = 'Room Category';
        return view('backend.pages.category', compact('data'));
    }

    public function room_category_delete($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return back()->with('success', 'Deleted Successfully');
        }

        return back()->with('error', 'Room Category Not Found');
    }

}
