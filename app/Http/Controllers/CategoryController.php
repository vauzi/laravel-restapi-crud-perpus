<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $category = Category::query()->get();
            // response successfully
            return response()->json([
                'status' => 'success',
                'message' => 'find category get all successfully',
                'data' => $category,
            ]);
        } catch (QueryException $e) {
            // response error message
            return response()->json([
                'status' => 'success',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        // input image file
        try {
            $image = $request->file('image')->store('image/category', 'public');
            // insert into category
            $category = Category::query()->create([
                'name' => $request->input('name'),
                'image' => 'storage/' . $image
            ]);
            // response successfully
            return response()->json([
                'status' => 'success',
                'message' => 'category created successfully',
                'data' => $category
            ], 201);
        } catch (QueryException $e) {

            // response error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        try {
            // get category by id
            $category = Category::query()->find($id);
            // response success
            return response()->json([
                'status' => 'success',
                'message' => 'get show by id successfully',
                'data'   => $category,
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::query()->find($id); // get category by id

            // ceck file image ada atau tidak 
            // jika ada image yang lama akan di hapus dan di ganti yg baru
            if ($request->file('image') !== null) {
                unlink(public_path($category->image)); // delete image file
                $image = 'storage/' . $request->file('image')->store('image/category', 'public');
            } else {
                $image = $category->image;
            }

            // insert database
            $category->update([
                'name' => $request->input('name'),
                'image' => $image
            ]);

            // repsonse successful
            return response()->json([
                'status' => 'success',
                'message' => 'Updated category successfully',
                'data'   => $category,
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::query()->find($id); // get category by id
            unlink(public_path($category->image)); // delete image in storage folder
            $category->delete(); //delete category
            // response successfully
            return response()->json([
                'status'    => 'success',
                'message'   => 'Deletes category successfully',
                'data'      =>  $category
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
            ]);
        }
    }
}
