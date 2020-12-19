<?php
namespace App\repositories;
use App\interfaces\CRUDinterface;
use App\Model\ProductApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\File;



class ProductAPIRepository implements CRUDinterface{
    public function getAll(){
        $products = ProductApi::all();
        return $products;
    }
    public function findById($id){
        $product = ProductApi::find($id);
        return $product;
    }
    public function create(Request $request){
        $productImage = $request->file('image');
        $fileType = $productImage->getClientOriginalExtension();
        $ImageName = 'product'.rand(1,1000).'.'.$fileType;
        $directory = 'images/';
        $ImageURL = $directory.$ImageName;
        Image::make($productImage)->resize(1920,1274)->save($ImageURL);

        $product = new ProductApi();
        $product->Title = $request->title;
        $product->Image = "http://127.0.0.1:8000/".$ImageURL;
        $product->Description = $request->description;
        $product->Price = $request->price;

        $product->save();
        return $product;

    }

    public function delete($id){
        $product = $this->findById($id);
        $product->delete();
        return $product;
    }
}
