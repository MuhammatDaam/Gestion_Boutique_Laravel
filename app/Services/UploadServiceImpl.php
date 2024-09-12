<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Services\UploadService as UploadService;
class UploadServiceImpl implements UploadService
{

    public function upload(UploadedFile $file, $directory = 'images')
    {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($directory), $fileName);

        return $fileName;
    }

    public function getBase64(string $path)
    {
        $fileContent = Storage::disk('public')->get($path);
        if (Storage::disk('public')->exists($path)) {
            
            return base64_encode($fileContent);
        }
  
        return null;
    }
}
