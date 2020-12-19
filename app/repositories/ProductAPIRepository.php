<?php
namespace App\repositories;
use App\interfaces\CRUDinterface;
use App\Model\ProductApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;



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
    public function edit(Request $request,$id){

        $productImage   =  $request->file('image');
        if($productImage){

            $ProductAPI = $this->findById($id);
            $fileType = $productImage->getClientOriginalExtension();
            $ImageName = 'product'.rand(1,1000).'.'.$fileType;
            $directory = 'productImages/';
            $ImageURL = $directory.$ImageName;
            Image::make($productImage)->resize(800,809)->save($ImageURL);


            $ProductAPI->Title = $request->title;
            $ProductAPI->Image = "http://127.0.0.1:8000/".$ImageURL;
            $ProductAPI->Description = $request->description;
            $ProductAPI->Price = $request->price;
            $ProductAPI->save();
            return $ProductAPI;

        }

            $ProductAPI = $this->findById($id);
            $ProductAPI->Title = $request->title;
            $ProductAPI->Description = $request->description;
            $ProductAPI->Price = $request->price;
            $ProductAPI->save();
            return $ProductAPI;


    }
    public function delete($id){
        $product = $this->findById($id);
        $product->delete();
        return $product;
    }
}
