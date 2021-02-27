<?php
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Notifications\MailNotification;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use \Firebase\JWT\JWT;
use App\Excel\Export;
use App\Model\Admin;
use App\Model\User;
use App\Model\Otp;

if (!function_exists('output')) {
  function output($msg,$data) {
    $output = [
      'msg' => $msg,
      'data' => $data
    ];
    return $output;
  }
}

if (!function_exists('auth_id')) {
    function auth_id() {
        $key = "p2p";
        try {
            return JWT::decode(request()->header('jwt'),$key,array('HS256'))->id;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
}

if (!function_exists('auth_admin')) {
    function auth_admin() {
        $admin = Admin::where('id',auth_id())->first();
        return $admin;
    }
}

if (!function_exists('auth_user')) {
    function auth_user() {
        $user = User::where('id',auth_id())->first();
        return $user;
    }
}

if (!function_exists('image_upload_size')) {
    function image_upload_size() {
        $sizes = array(
            'mdpi'  => ['w' => 480, 'h' => 320],
            'hdpi'  => ['w' => 960, 'h' => 640],
            'xhdpi' => ['w' => 1040, 'h' => 720],
        );
        return $sizes;
    }
}

if (!function_exists('image_upload_base64')) {
    function image_upload_base64($folderName,$fileName,$file,$driver="public") {
        $sizes = image_upload_size();
        foreach ($sizes as $key => $size) {
                $imgData = Image::make($file)->resize($size['w'], NULL, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $thumb = $imgData->stream()->__toString();
            Storage::disk($driver)->put($folderName.'/'.$key.'/'.$fileName, $imgData, 'public');
        }

    }
}

if (!function_exists('p2p_drive')) {
    function p2p_drive()
    {
        return 'public';
    }
}
