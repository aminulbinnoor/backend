<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Model\Product;


class ProductController extends Controller
{
    public function create(Request $request)
    {
       $product = new Product;
       $product->name = $request->name;
       $product->price = $request->price;
       $product->description = $request->description;
       $product->save();
       if ($request->hasFile('images')) {
           $productImages = $request->file('images');
            foreach ($productImages as $key => $pimage) {
                $path = 'uploads/'.md5($product->id).'/';
                $file = $product->name.'-'.$key.'.png';
                image_upload_base64($path, $file,$pimage,p2p_drive());
                $productImages[$key] = ['path' => $path , 'file' => $file];
            }
         $product->images = json_encode($productImages);
       }
       $product->save();

       $msg = ['message' => 'Product created', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function get(Request $request)
    {
      if ($request->id) {
        $product = Product::where('id',$request->id)->first();
        $msg = ['message' => 'Package retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$product),200);
      }elseif ($request->show_all) {
          $products = Product::all();
          $msg = ['message' => 'Package retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$products),200);
       }

      $per_page = $request->per_page ?? 2;
      $products = Product::paginate($per_page);
      $msg = ['message' => 'product retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$products),200);
    }

    public function update(Request $request)
    {

      $product =  Product::where('id',$request->id)
                         ->update([
                           'name' => $request->name,
                           'price' => $request->price,
                           'description' => $request->description,
                         ]);

      $product =  Product::where('id',$request->id)->first();
      $productImageDeleteAfter = json_decode($request->deleted_after_product_images);
      $pimages = [];
       if ($request->deleted_product_images) {
          // existing feature image deleted
          $deletedPImages = json_decode($request->deleted_product_images);

          foreach ($deletedPImages as $key => $value) {
              foreach (image_upload_size() as $sizekey => $size) {
                  $path = $value->path.'/'.$sizekey.'/'.$value->file;
                  if(Storage::disk('local')->exists($path)){
                      Storage::disk('local')->delete($path);
                  }
              }
          }
       }
      if ($request->hasFile('images')) {
          $productImages = $request->file('images');
          foreach ($productImages as $key => $pimage) {
              $path = 'uploads/'.md5($product->id).'/';
              $file = $product->name.'-'.$key . '-'. strtotime(now()) .'.png';
              image_upload_base64($path, $file,$pimage,p2p_drive());
              $productImages[$key] = ['path' => $path , 'file' => $file];
          }
          $pimages = $productImages;
      }

     $product->images =  json_encode(array_merge($pimages,$productImageDeleteAfter));
     $product->save();
     $msg = ['message' => 'Product updated', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,$productImageDeleteAfter),200);
    }

    public function delete(Request $request)
    {
      $category = Product::where('id',$request->id)
                           ->delete();
      $msg = ['message' => 'Product Deleted', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);
    }

}
