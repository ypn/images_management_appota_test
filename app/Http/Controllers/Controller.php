<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Exception;

use Illuminate\Support\Facades\Input;
use Image,File,Redirect,Validator;

use App\Entities\Files;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests; 

    protected $width_thumb = 150 ;
    protected $height_thumb = 150; 	

    public function upload(){ 
        $files_thumb = Files::select('file_name','id','title')->orderBy('id','desc')->get();
        foreach ($files_thumb as $f) {
            $f->width = $this->width_thumb;
            $f->height = $this->height_thumb;
        }
        return view('manager_image',['images'=>$files_thumb]);
    }


    /*
     *@author:ypn 
     *desc : upload file    
    */
    public function fileUpload(){
    	if(Input::hasFile('image')){   

            $image = Input::file('image'); 
            $filename = time() . '.' .$image->getClientOriginalExtension();
            $imagename = $image->getClientOriginalName(); 

            $validator = Validator::make(Input::all(),[
                'image'=>'mimes:jpeg,jpg,png,gif|required|max:2048'
            ]);

            if($validator->fails()){
                return Redirect::back()->with('err','Max file size is 2M');
            }    		

            if(strlen($imagename) > 255){
                 return Redirect::back()->with('err','Your file name so stupid');
            }   		

            $image = Image::make($image);

            $this->storeImage($image,$filename);

            Files::insert([
            'title'=>trim(strip_tags($imagename)),
            'file_name'=>trim(strip_tags($filename))
            ]);		

            return Redirect::back();
    	}else{           
            return Redirect::back()->with('err','File not found!');
    	}
    }

    /* 
     *@author:ypn
     *@desc: request for save edit image when crop.
    */
    public function cropped(){      

        if(Input::has('encode_image')){
            $data = Input::get('encode_image');           
            $id = Input::get('id');  
            $title = Input::get('title');         

            $file = Files::where('id',$id)->first();
            $image = Image::make(file_get_contents($data));
            if(!empty($file)){                
                try{              
                    $filename = $file->file_name; 
                    $this->storeImage($image,$filename);                    
                    $file->title = $title;
                    $file->save();

                    return 'success';

                }catch (Exception $ex){
                    //Log error
                    return 'server_error';
                }
                
            }              


        }else{
            return 'file_not_existed';
        }
    }


    /*
     *@author:ypn
     *desc:Delete file in directory and database.Need get username first before get file path but this is test show i dont do that.
    */
    public function delete(){

        $filename = Input::get('filename');
        $id = Input::get('file_id');

        $origin_path = public_path('/uploads/users/origin/');
        $thumb_path = public_path("uploads/users/thumbs/$this->width_thumb/$this->height_thumb/");   

        try{

            $this->destroy($origin_path . $filename);
            $this->destroy($thumb_path . $filename);

            Files::where('id',$id)->delete();

            return 'success';

        }  catch(Exception $ex){
            //log exception

            return 'server_error';
        }     



    }

    /*
     *destroy file in server directory.
    */
    protected function destroy($file_path){      
        if(file_exists($file_path)){            
            File::delete($file_path);
        }
    }


    /*
     *Write file to server. foldel will auto create if not exist. Default root folder is upload must create with full permission(-rwx-).
    */
    protected function storeImage($image,$filename){

        //path of file by user name. Because test so username = users.
        $origin_path = public_path('/uploads/users/origin/');
        $thumb_path = public_path("uploads/users/thumbs/$this->width_thumb/$this->height_thumb/");

        if(!file_exists($origin_path)){
                File::makeDirectory($origin_path,0777,true,true); 
            }

        $image->save($origin_path . $filename);

        if(!file_exists($thumb_path)){
            File::makeDirectory($thumb_path,0777,true,true);
        }

        $image->fit($this->width_thumb,$this->height_thumb)->save($thumb_path . $filename);       

    }
}
