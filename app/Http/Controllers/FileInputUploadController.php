<?php

namespace App\Http\Controllers;

use App\Models\Base\BaseSysMedia;
use App\Tools\Result;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class FileInputUploadController extends Controller
{
    //
    private $IMG_TYPE=array();
    private $FILE_TYPE=array();
    function __construct()
    {
        $this->IMG_TYPE = array('jpg','JPG','png','PNG','jpeg','JPEG');
        $this->FILE_TYPE = array('xls', 'xlsx','doc','docx','pdf');
    }
    public function download($id){
        $file  = BaseSysMedia::find($id);
        if(!empty($file)){
            return response()->download(realpath(base_path('public/storage/uploadfile/'))."/".$file->m_url,$file->m_name);
        }else{
            return "下载错误";
        }
    }
    public function delete(){
        $media = BaseSysMedia::find(Input::get("key"));
        if(!empty($media)){
            $media->m_status = -1;
            $media->save();
        }
        $result  = new \stdClass();
        $result->code="OK";
        return response()->json($result);
    }
    /**
     * http://image.intervention.io/
     
     * @return string
     * @description
     */
    public function anyUpload()
    {
        //如果一个页面有多个文件上传的时候 需要上传文件按钮Id
        if(Input::has('filename') && Input::get('filename') != ""){
            if(isset($_FILES[Input::get('filename')])){
                $fileinfo = $_FILES[Input::get('filename')];
            }else{
                $info = array('code'=>0,'error'=>'发生错误 filename');
                return json_encode($info);
            }
        }else{ ///默认上传文件按钮ID是 fileupload
            if(isset($_FILES["fileupload"])){
                $fileinfo =$_FILES['fileupload'];
            }else{
                $info = array('code'=>0,'error'=>'发生错误 fileupload');
                return json_encode($info);
            }
        }
        $type =  Input::get('pictype',0);
        try{
            if(! is_null($type)  && $type >0){
                if($fileinfo['error']==0){
                    $media = new BaseSysMedia();
                    $filename = explode('.',$fileinfo['name']);
                    $extname = strtolower(end($filename));
                    $path = "/".$type."/".date('Ymd')."/";
                    
                    if(!file_exists(config('www.uploadfile').$path)){
                        Log::error(config('www.uploadfile')."$path");
                        mkdir(config('www.uploadfile').$path,0775,true);
                    }
                    
                    $file_save_path =config('www.uploadfile').$path;
                    $height=0;
                    $width=0;
                    $timerand = time().rand(1000,9999);
                    //如果是图片文件格式 进行缩略处理
                    if(in_array($extname,$this->IMG_TYPE)){
                        $img = Image::make($fileinfo['tmp_name']);
                        $height =  $img->height();
                        $width = $img->width();
                        $newfilename =$timerand."_".$width."X".$height.".".$extname;
                        //方形图
                        $alias="_square";
                        $aliasfilename =$timerand."_".$width."X".$height.$alias.".".$extname;
                        if($width>$height){
                            $x =  round(($width - $height) / 2);
                            $img->crop($height,$height,$x,0);
                            if($height>300){
                                $img->resize(300,300);
                            }
                            $img->save($file_save_path.$aliasfilename);
                            $img->destroy();
                        }elseif($width<$height){
                            $y =  round(($height - $width) / 2);
                            $img->crop($width,$width,0,$y);
                            if($width>300){
                                $img->resize(300,300);
                            }
                            $img->save($file_save_path.$aliasfilename);
                            $img->destroy();
                        }elseif($width==$height){
                            if($width>300){
                                $img->resize(300,300);
                                $img->save($file_save_path.$aliasfilename);
                                $img->destroy();
                            }
                        }
                        //缩略图
                        if($height > $width && $height>400){
                            $img = Image::make($fileinfo['tmp_name']);
                            $img->resize(null,400,function ($constraint) {$constraint->aspectRatio();});
                            $alias="_thumbnail";
                            $aliasfilename =$timerand."_".$width."X".$height.$alias.".".$extname;
                            $img->save($file_save_path.$aliasfilename);
                            $img->destroy();
                        }else if($height < $width && $width>400){
                            $img = Image::make($fileinfo['tmp_name']);
                            $img->resize(400,null, function ($constraint) {$constraint->aspectRatio();});
                            $alias="_thumbnail";
                            $aliasfilename =$timerand."_".$width."X".$height.$alias.".".$extname;
                            $img->save($file_save_path.$aliasfilename);
                            $img->destroy();
                        }elseif($height == $width){
                            $img = Image::make($fileinfo['tmp_name']);
                            $img->resize(400,400);
                            $alias="_thumbnail";
                            $aliasfilename =$timerand."_".$width."X".$height.$alias.".".$extname;
                            $img->save($file_save_path.$aliasfilename);
                            $img->destroy();
                        }
                        $fileinfo['width'] = $width;
                        $fileinfo['height'] = $height;
                        
                        $media->m_width = $fileinfo['width'];
                        $media->m_height = $fileinfo['height'];
                    }else{
                        $newfilename =$timerand.".".$extname;
                        $media->m_width =0;
                        $media->m_height =0;
                    }
                    move_uploaded_file($fileinfo['tmp_name'],$file_save_path.$newfilename);
                    unset($fileinfo['tmp_name']);
                    unset($fileinfo['error']);
                    
                    $media->uid = Input::has('uid')?Input::get('uid') : 0 ;
                    $media->m_name  =  $fileinfo['name'];
                    $media->m_url  =  $path.$newfilename;
                    $media->m_type = $type;
                    $media->m_format = $fileinfo['type'];
                    $media->m_size = $fileinfo['size'];
                    $media->m_metadata =json_encode($fileinfo);
                    $media->save();
                    
                    //初始化处理
                    if(in_array($extname,$this->IMG_TYPE)){
                        $initPreview=["<img src='".asset('storage/uploadfile/').$media->m_url."' style=\"width:auto;height:160px;\" class=\"file-preview-image\" alt=".$media->m_name." title=".$media->m_name.">"];
                    }else{
                        $initPreview=["<div class='file-preview-other'><span class='file-other-icon'><i class='glyphicon glyphicon-file'></i></span></div>"];
                    }
                    $initialPreviewConfig=[
                        ["caption"=>$media->m_name,
//                            "width"=>'120px',
                            "url"=>URL::action('FileInputUploadController@delete'),
                            "key"=>$media->id
                        ]
                    ];
                    
                    unset($media->m_metadata);
                    unset($media->updated_at);
                    unset($media->created_at);
                    Log::info(json_encode($media));
                    $info=array('code'=>1,'initialPreview'=>$initPreview,'initialPreviewConfig'=>$initialPreviewConfig,'msg'=>"上传成功",'data'=>$media);
                }else{
                    ///上传文件发生错误
                    $info = array('code'=>0,'error'=>'发生错误','info'=>$fileinfo);;
                }
            }else{
                $info = array('code'=>0,'error'=>'文件分类错误');
            }
        }catch (\Exception $e){
            Log::error("文件上传发生系统错误=".$e->getMessage().$e->getFile().$e->getLine());
            $info = array('code'=>0,'error'=>'文件上传发生错误'.$e->getMessage());
        }
        return  json_encode($info);
    }
}
