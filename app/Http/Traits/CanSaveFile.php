<?php


namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait CanSaveFile
{
    function saveFile($contents, $path, $fileName, $fileType){
        Storage::disk('local')->putFileAS($path, $contents, $fileName . '.' . $fileType);
    }

    function deleteFile($path) {
        Storage::disk('local')->delete($path);
    }
}
