<?php


namespace App\Services;

use App\Models\Image;
use Image as ImageFile;

class ImageService
{
    public $imagePath;
    private $image;
    private $imageName;
    private $thumbPath;
    private $resizePath;

    /**
     * ImageService constructor.
     * @param $image
     */
    public function __construct($image = null)
    {
        $this->image = $image;

        if ($image) $this->imageName = $this->getImageName($image);
    }

    /**
     * @param $image
     * @return mixed|string
     */
    private function getImageName($image, $size = null)
    {
        $onlyName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $size ? $onlyName = $onlyName . "_" . strtotime(now()) . "_" . $size . "." . $image->extension()
            : $onlyName = $onlyName . "_" . strtotime(now()) . "." . $image->extension();
        $onlyName = str_replace(" ", "_", $onlyName);

        return $onlyName;
    }

    /**
     * create new data on database
     */
    public function createDataToDB()
    {
        $mouseImage = new Image;
        $this->saveOrUpdateDB($mouseImage);
    }

    /**
     * @param $mouseImage
     */
    private function saveOrUpdateDB($mouseImage)
    {
        $mouseImage->name = $this->imageName;
        $mouseImage->normal_path = $this->imagePath;
        $mouseImage->resize_path = $this->resizePath;
        $mouseImage->thumb_path = $this->thumbPath;
        $mouseImage->save();
    }

    /**
     * @param $id
     */
    public function updateDataInDB($id)
    {
        $mouseImage = Image::find($id);
        $this->saveOrUpdateDB($mouseImage);
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
                "status" => 204,
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
        if (!unlink($image->resize_path)) {
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

    /**
     * @param int $size
     * save resize image based on user input
     */
    public function saveResizeImage(int $size)
    {
        $this->resizePath = public_path() . '/resize_images/' . $this->getImageName($this->image, $size);
        ImageFile::make($this->image)
            ->resize($size, NULL, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($this->resizePath);
    }

    /**
     * save thumbnail image
     */
    public function saveThumbImage()
    {
        $this->thumbPath = public_path() . '/thumbnail_images/' . $this->imageName;
        ImageFile::make($this->image->getRealPath())->resize(100, 100)->save($this->thumbPath);
    }

    /**
     * save original image
     */
    public function saveOriginalImage()
    {
        $this->imagePath = public_path() . '/normal_images/' . $this->imageName;
        ImageFile::make($this->image)->save($this->imagePath);
    }
}
