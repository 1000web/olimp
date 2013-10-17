<?php

class CronController extends CController
{
    public function actionIndex(){
        $images = Image::model()->getInvisible(1)->getData();
        foreach ($images as $img) {
            $dir = Yii::app()->basePath . '/../images/';
            if (!empty($img->section)) $dir .= $img->section . '/';
            $path_parts = pathinfo($img->image_url);
            $ext = $path_parts['extension'];
            $image_name = $img->id . '.' . $ext;
            $local_image = $dir . 'original/' . $image_name;


            foreach (array(150, 300, 800) as $width) {
            }
        }

    }

    public function actionMinute($num = 5)
    {
        $images = Image::model()->getCron($num)->getData();

        foreach ($images as $image) {
            $dir = Yii::app()->basePath . '/../images/';
            if (!empty($image->section)) $dir .= $image->section . '/';
            $path_parts = pathinfo($image->image_url);
            $ext = $path_parts['extension'];
            $image_name = $image->id . '.' . $ext;
            $local_image = $dir . 'original/' . $image_name;

            // сохраняем изображение локально
            $ch = curl_init($image->image_url);
            $fp = fopen($local_image, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            // определяем размеры изображения
            $size = getimagesize($image->image_url);
            $original_width = $size[0];
            $original_height = $size[1];

            foreach (array(150, 300, 800) as $width) {
                $height = intval($original_height * $width / $original_width);
                $resized_image = $dir . $width . '/' . $image_name;

                // создаем новое изображение с заданными размерами
                $img = new imagick($local_image);
                $img->setImageFormat($ext);
                $img->scaleImage($width, 0);
                $img->cropImage($width, $height, 0, 0);
                $img->writeImage($resized_image);
            }
            // сохраняем статус и имя картинки
            $model = Image::model()->findbyPk($image->id);
            $model->setAttributes(array(
                'cron' => 1,
                'image_name' => $image_name,
            ));
            $model->save();

        }
    }

}