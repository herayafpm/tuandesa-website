<?php
namespace App\Helpers;
use Image;
use Storage;
class ImageStorage {
  public static function upload($image,$name)
  {
    Image::make($image)
            ->encode('webp', 75)
            ->save(storage_path('app/public/'.$name.'.webp'));
    return true;
  }
  public static function delete($imagePath)
  {
    Storage::delete('public/'.$imagePath);
    return true;
  }
}