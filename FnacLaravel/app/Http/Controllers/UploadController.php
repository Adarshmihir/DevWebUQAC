<?php

namespace App\Http\Controllers;

class UploadController extends Controller
{
	public static function img_upload($request,$var,$pathP,$width=480,$height=480){
        if($request->img){
            do{
                $imageName = str_random(40).'.'.$request->img->getClientOriginalExtension();
                $path=public_path($pathP).$imageName;
            }while(file_exists($path));
            Image::make($request->img->getRealPath())->resize($width, $height)->save($path);
            $var->img=$imageName;
        }
    }
    public function save(Request $request){
    	$request->validate(['image'=>'required|image']);
        $img = $request->file('image');
        

    }
}