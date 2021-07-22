<?php

namespace App\Http\Controllers;

class FileController extends Controller
{
    //
    const PATH_IMG_USG = '/img/usg';

    private function downloadLocalFile($path, $fileName) {
        try {
            $fileName = str_replace('_', '.', $fileName);
            $file = storage_path('app') . $path . '/' . $fileName;

            $header = array(
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment',
                'filename' => $fileName,
            );

            // auth code
            return response()->download($file, $fileName, $header);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getUsgImg($fileName) {
        return $this->downloadLocalFile(self::PATH_IMG_USG, $fileName);
    }
}
