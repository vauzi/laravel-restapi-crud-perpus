<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        // try catch: heanding errors
        try {
            $category = Category::query()->get(); //get all categories. mengambil data dari database tabel categories

            // response success
            return response()->json([
                'status' => 'success',
                'message' => 'find category get all successfully',
                'data' => $category,
            ], 200);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status' => 'success',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        //validation request
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'min:3'], //validation required(harus di isi) min:3(minimal 3 karakter)
            'image' => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg'] // validation fila image and format name
        ], [
            // response validation error message
            'required'      => ':attribute harus di isi',
            'image'         => ':attribute harus berupa foto',
            'mimes'         => ':attribute, format file harus :values',
        ]);
        if ($validation->fails()) { // check validation request error message
            return response()->json($validation->errors(), 400);
        }
        // try catch: heanding errors
        try {
            $image = $request->file('image')->store('image/category', 'public'); // input image file and path image file

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
            ], 500);
        }
    }

    public function show($id)
    {
        // try catch: heanding errors
        try {
            // get category by id
            $category = Category::query()->find($id);
            // response success
            return response()->json([
                'status' => 'success',
                'message' => 'get show by id successfully',
                'data'   => $category,
            ], 200);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        //validation request
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'min:3'], //validation required(harus di isi) min:3(minimal 3 karakter)
            'image' => ['image', 'mimes:jpg,png,jpeg,gif,svg'] // validation fila image and format name
        ], [
            // response validation error message
            'required'      => ':attribute harus di isi',
            'image'         => ':attribute harus berupa foto',
            'mimes'         => ':attribute, format file harus :values',
        ]);
        if ($validation->fails()) { // check validation request error message
            return response()->json($validation->errors(), 400);
        }

        // try catch: heanding errors
        try {
            $category = Category::query()->find($id); // get category by id mengambil data dari database berdasarkan id

            // ceck file image ada atau tidak 
            // jika ada image yang lama akan di hapus dan di ganti yg baru
            if ($request->file('image') !== null) {
                unlink(public_path($category->image)); // delete image file
                $image = 'storage/' . $request->file('image')->store('image/category', 'public'); // input file and name path image
            } else {
                $image = $category->image; // path image in database
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
            ], 200);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        // try catch: heanding errors
        try {
            $category = Category::query()->find($id); // get category by id
            unlink(public_path($category->image)); // delete image in storage folder
            $category->delete(); //delete category

            // response successfully
            return response()->json([
                'status'    => 'success',
                'message'   => 'Deletes category successfully',
                'data'      =>  $category
            ], 200);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
            ], 300);
        }
    }
}
