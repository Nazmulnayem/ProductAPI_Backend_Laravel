<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Model\ProductApi;
use App\repositories\ProductAPIRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
    public function update(Request $request,$id){
        $ProductAPI = $this->productAPIRepository->findById($id);
        if(is_null($ProductAPI)){
            return response()->json([
                'success' =>false,
                'message' =>'Product Not Found',
                'data' =>null

            ]);
        }
        $formData = $request->all();
        $validator = Validator::make($formData,[
            'title' =>'required',
            'description' =>'required',
            'price' =>'required',

        ],[
            'description.required' =>'please give product description'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' =>false,
                'message' =>$validator->getMessageBag()->first(),

            ]);
        }

        $ProductAPI = $this->productAPIRepository->edit($request,$id);
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

}
