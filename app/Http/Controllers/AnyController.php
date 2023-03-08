<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AnyController extends Controller
{
    public function upload(Request $request)
    {
//        return $request->all();
        $uploadedFile = $request->file('file');
        $fileName = str_replace(' ', '', time() . $uploadedFile->getClientOriginalName());
        $original_name = $uploadedFile->getClientOriginalName();
        Storage::disk('local')->putFileAs('public/photos', $uploadedFile, $fileName);
        $photo = new Photo();
        $photo->path = $fileName;
        $photo->original_name = $original_name;
        $photo->save();
        return response()->json([
            'photo_id' => $photo->id
        ]);
    }

    public function create(Request $request)
    {
        return $request->all();
    }


    public function deletePhoto(Request $request)
    {
        $photo = Photo::whereId($request->id)->first();
        unlink(public_path($photo->path));
        $photo->delete();
        return "Success";
    }

    public function postCreate(Request $request)
    {
        $product = new Product();
        $product->title = $request->title;
        $product->save();
        $photos = explode(',', $request->input('photo_id')[0]);
        $product->photos()->sync($photos);
    }


    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
//        return $product->photos;
        return view('edit', compact('product'));
    }


    public function getImages($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product->photos);
    }

    public function updateProduct($id,Request $request)
    {
        $product = Product::findOrFail($id);
        $product->title =$request->title;
        $product->save();
        $photos = explode(',', $request->input('photo_id')[0]);
        $product->photos()->sync($photos);
        return back();
    }

    public function deletePhotoInUpdate(Request $request)
    {

        $product = Product::whereId($request->product_id)->first();
        $photo = Photo::whereId($request->photo_id)->first();

//        $photos = ;
        $product->photos()->sync($request->all_photo_id);
//
//
        if (File::exists(public_path($photo->path))){
            unlink(public_path($photo->path));
        }
        $photo->delete();
        return "success";
    }







}
