<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Model\ProductApi;
use App\repositories\ProductAPIRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Image;


class ProductController extends Controller
{
    public $productAPIRepository;
    public function __construct(ProductAPIRepository $productAPIRepository)
    {
        $this->productAPIRepository = $productAPIRepository;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);


    }


    public function index(){
        $ProductAPI = $this->productAPIRepository->getAll();
        return response()->json([
            'success' =>true,
            'message' =>'Product API',
            'data' => $ProductAPI
        ]);
    }
    public function show($id){
        $ProductAPI = $this->productAPIRepository->findById($id);
        /**
         * show() find project id
         * @param [type] $id
         * @return void
         */
        if(is_null($ProductAPI)){
            return response()->json([
                'success' =>false,
                'message' =>'Product Details',
                'data' => null
            ]);
        }
        return response()->json([
            'success' =>true,
            'message' =>'Product Details',
            'data' => $ProductAPI
        ]);
    }
    public function store(Request $request){
        /**
         * store() update project id
         * @param [type] $id
         * @return void
         */
        $formData = $request->all();
        $validator = Validator::make($formData,[
            'title' =>'required',
            'description' =>'required',
            'image' =>'required |image:jpg,png,jpg,gif,svg|max:2048',
            'price' =>'required',

        ],[
            'title.required' =>'please give title',
            'description.required' =>'please give description'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' =>false,
                'message' =>$validator->getMessageBag()->first(),

            ]);
        }

        $ProductAPI = $this->productAPIRepository->create($request);
        return response()->json([
            'success' =>true,
            'message' =>'Product Store',
            'data' => $ProductAPI
        ]);
    }

    public function destroy($id){
    $ProductAPI = $this->productAPIRepository->findById($id);
    if(is_null($ProductAPI)){
        return response()->json([
            'success' =>false,
            'message' =>'Product Not Found',
            'data' =>null

        ]);
    }

    $ProductAPI = $this->productAPIRepository->delete($id);
    return response()->json([
        'success' =>true,
        'message' =>'Product delete',
        'data' => $ProductAPI
    ]);
}

public function updateproduct(Request $request){

    $productImage   =  $request->file('image');
    if($productImage){

        $ProductAPI = ProductAPI::find($request->id);
        File::delete($ProductAPI->Image);
        $fileType = $productImage->getClientOriginalExtension();
        $ImageName = 'product'.rand(1,1000).'.'.$fileType;
        $directory = 'images/';
        $ImageURL = $directory.$ImageName;
        Image::make($productImage)->resize(800,809)->save($ImageURL);


        $ProductAPI->Title = $request->title;
        $ProductAPI->Image = "http://127.0.0.1:8000/".$ImageURL;
        $ProductAPI->Description = $request->description;
        $ProductAPI->Price = $request->price;
        $ProductAPI->save();
        return $ProductAPI;

    }

    $ProductAPI = ProductAPI::find($request->id);
    $ProductAPI->Title = $request->title;
    $ProductAPI->Description = $request->description;
    $ProductAPI->Price = $request->price;
    $ProductAPI->save();
    return $ProductAPI;

}

}
