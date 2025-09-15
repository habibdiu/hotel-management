<?php

namespace App\Http\Controllers\backend;

use PDOException;
use App\Models\RoomType;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Middleware\backendAuthenticationMiddleware;
use App\Models\Category;

class SubcategoryController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            backendAuthenticationMiddleware::class
        ];
    }

    public function room_subcategory( Request $request)
    {
        $data = [];

        if ($request->isMethod('post')) {
            try {
                Subcategory::create([
                    'name' => $request->sub_category_name,
                    'category_id' => $request->category_id
                ]);
                return back()->with('success', 'Added Successfully');
            } catch (PDOException $e) {
                return back()->with('error', 'Failed: ' . $e->getMessage());
            }
        }

        $data['room_types'] = RoomType::all();
        $data['categories'] = Category::all();
        $data['subcategories']= Subcategory::with('category.room_type')->orderBy('id', 'DESC')->paginate(10);
        $data['active_menu'] = 'subcategory';
        $data['page_title'] = 'Sub Category';
        return view('backend.pages.subcategory', compact('data'));
    }

    public function room_subcategory_update(Request $request, $id)
    {
        $subcategory = Subcategory::find($id);

        if (!$subcategory) {
            return back()->with('error', 'Not found.');
        }

        try {
            $subcategory->update([
                'name'        => $request->sub_category_name,
                'category_id' => $request->category_id
            ]);

            return back()->with('success', 'Updated Successfully');
        } catch (PDOException $e) {
            return back()->with('error', 'Failed! Please Try Again');
        }
    }


    public function room_subcategory_delete($id)
    {
        $subcategory = Subcategory::find($id);

        if ($subcategory) {
            $subcategory->delete();
            return back()->with('success', 'Deleted Successfully');
        }

        return back()->with('error', 'Subcategory Not Found');
    }

}
