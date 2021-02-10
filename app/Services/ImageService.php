<?php


namespace App\Services;

use App\Models\Image;
use Image as ImageFile;

class ImageService
{

    /**
     * @param null $id
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function saveImage($id = null)
    {
        $image = request()->file('image');
        $nameImage = $this->imageName($image);
        $nameImage = str_replace(' ', '_', $nameImage);

        $thumbImage = ImageFile::make($image->getRealPath())->resize(100, 100);
        $thumbPath = public_path() . '/thumbnail_images/' . $nameImage;
        ImageFile::make($thumbImage)->save($thumbPath);

        $oriPath = public_path() . '/normal_images/' . $nameImage;
        ImageFile::make($image)->save($oriPath);

        $this->saveOrUpdateDB($id, $nameImage, $oriPath, $thumbPath);

        return url('/normal_images/' . $nameImage);
    }

    /**
     * @param $image
     * @return mixed|string
     */
    private function imageName($image)
    {
        $onlyName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $onlyName = $onlyName . "_" . strtotime(now()) . "." . $image->extension();
        $onlyName = str_replace(" ", "_", $onlyName);

        return $onlyName;
    }

    /**
     * @param $id
     * @param $nameImage
     * @param $oriPath
     * @param $thumbPath
     */
    private function saveOrUpdateDB($id, $nameImage, $oriPath, $thumbPath)
    {
        if (!$id) {
            $mouseImage = new Image;
        } else {
            $mouseImage = Image::find($id);
            $this->statusDeleteImageInFile($id);
        }
        $mouseImage->name = $nameImage;
        $mouseImage->normal_path = $oriPath;
        $mouseImage->thumb_path = $thumbPath;
        $mouseImage->save();
    }

    /**
     * @param $id
     * @return array
     */
    public function statusDeleteImageInFile($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return [
                "status" => 404,
                "message" => "data not found"
            ];
        }
        if (!unlink($image->normal_path)) {
            return [
                "status" => 500,
                "message" => "Failed to delete file"
            ];
        }
        if (!unlink($image->thumb_path)) {
            return [
                "status" => 500,
                "message" => "Failed to delete file"
            ];
        }
        return [
            "status" => 200,
            "message" => "Image deleted successfully"
        ];
    }

}
