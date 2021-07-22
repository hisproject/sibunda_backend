<?php

namespace App\Models;

use App\Http\Controllers\FileController;
use App\Http\Traits\CanSaveFile;
use App\Utils\Constants;
use http\Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatementIbuHamilPeriksa extends Model
{
    use HasFactory, CanSaveFile;
    protected $table = 'service_statement_ibu_hamil_periksa';
    protected $fillable = [
        'week',
        'tanggal_periksa',
        'tempat_periksa',
        'nama_pemeriksa',
        'keluhan_bunda',
        'jenis_kelamin',
        'tanggal_periksa_kembali',
        'hpl',
        'bb',
        'kenaikan_bb',
        'tb',
        'tfu',
        'djj',
        'sistolik',
        'diastolik',
        'map',
        'gerakan_bayi',
        'resep_obat',
        'alergi_obat',
        'riwayat_penyakit',
        'catatan_khusus',
        'trisemester_id',
        'img_usg'
    ];

    public function saveImgUsg($img) {
        $path = FileController::PATH_IMG_USG;
        $fileName = 'img-' . $this->id;

        try {
            $fileType =  $img->getClientOriginalExtension();
            $fullPath = $path . '/' . $fileName . '_' . $fileType;
            $this->saveFile($img, $path, $fileName, $fileType);
        } catch(Exception $e) {
            $fullPath = null;
        }

        $this->img_usg = config('app.url') . $fullPath;
        $this->save();
    }

    public function deleteImgUsg() {
        if(!empty($this->img_usg)) {
            try {
                $imgPathSplit = explode('/', $this->img_usg);
                $fullPath = '';
                $fileName = $imgPathSplit[count($imgPathSplit) - 1];
                $fileName = str_replace('_', '.', $fileName);

                for ($i = 3; $i < count($imgPathSplit) - 1; $i ++)
                    $fullPath .= $imgPathSplit[$i] . '/';

                $fullPath .= $fileName;

                $this->deleteFile($fullPath);
            } catch (\Exception $e){}
        }
    }
}
