<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        try {
            $author = Author::query()->get(); //get all authors
            // response successfully
            return response()->json([
                'status' => 'success',
                'message' => 'get all author information successfully',
                'data' => $author
            ]);
        } catch (QueryException $e) {

            // response error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function store(Request $request)
    {
        $image = $request->file('image')->store('image/author', 'public');
        try {
            $author = Author::query()->create([
                'name'      => $request->input('name'),
                'image'     => 'storage/' . $image,
                'tgl_lahir' => $request->input('tgl_lahir'),
                'gender'    => $request->input('gender'),
                'alamat'    => $request->input('alamat'),
            ]);
            // response successfully
            return response()->json([
                'status'    => 'success',
                'message'   => 'Created successfully',
                'data'      => $author
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
            ]);
        }
    }
    public function show($id)
    {
        try {
            $author = Author::query()->find($id); //get author by id
            // response success
            return response()->json([
                'status'    => 'success',
                'message'   => 'show author by id successfully',
                'data'      => $author
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $author = Author::query()->find($id); // get athor by id
            // check image file not null
            if ($request->file('image') !== null) {
                unlink(public_path($author->image)); //delete image file
                $image = 'storage/' . $request->file('image')->store('image/author', 'public'); // upluad image file
            } else {
                $image = $author->image;
            }
            // upadte author
            $author->update([
                'name'      => $request->input('name'),
                'image'     => $image,
                'tgl_lahir' => $request->input('tgl_lahir'),
                'gender'    => $request->input('gender'),
                'alamat'    => $request->input('alamat'),
            ]);
            // response successful
            return response()->json([
                'status' => 'success',
                'message' => 'Author successfully updated',
                'data' => $author
            ]);
        } catch (QueryException $e) {
            // response error
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $author = Author::query()->find($id);
            unlink(public_path($author->image)); // delete image in storage folder
            $author->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'delete author successfully',
                'data' => $author,
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
