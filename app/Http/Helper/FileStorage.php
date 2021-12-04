<?php


namespace App\Helper;


use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FileStorage
{

    public static function storeFile($file, $path, $apply_date = false)
    {

        if ($apply_date)
            $path = $path . '/' . date("Y") . "/" . date("m") . "/" . date("d");

        if ($file) {
            $path = Storage::cloud()->put($path, $file);
            return $path;
        }
        return null;
    }

    public static function delete($path)
    {
        Storage::cloud()->delete($path);
    }

    public static function getLink($image, $valid_till = 1)
    {
        if ($image) {
            return Storage::disk('minio')->temporaryUrl($image, Carbon::now()->addMinute($valid_till));
        }
        return null;
    }

    public static function getPermanentLink($image) // not working
    {
        if($image) {
            return Storage::disk('minio')->url($image);
        }
        return null;
    }


    public static function getFileNameFromPath($path)
    {
        $path_array = explode('/', $path);
        return end($path_array);

    }

    public static function moveFileFrom($from, $to)
    {
        Storage::cloud()->move($from, $to);
    }

    public static function getAllFiles($directory)
    {
        $files = [];
        $temp_files = Storage::cloud()->files($directory);
        if($temp_files){
            foreach ($temp_files as $temp_file){
                array_push($files, self::getLink($temp_file));
            }
        }
        return $files;
    }

    public static function getDirectories($path) {
        return Storage::cloud()->directories($path);

    }

    private static function getDirectory($directory) {
        $paths = Storage::cloud()->directories($directory);

        if(!$paths)
            return $directory;

        foreach ($paths as $path){
            return static::getDirectory($path);
        }
    }

    public function getFIle($id)
    {

    }
}
