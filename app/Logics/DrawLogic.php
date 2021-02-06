<?php

namespace App\Logics;

use Grafika\Grafika;
use Grafika\Color;
use Illuminate\Support\Facades\Storage;

class DrawLogic
{

    /**
     * 绘制三庭五眼
     */
    public function threePartsFiveEyes($path, $data)
    {
        $data = json_decode($data, true);
        $data = $data['denselandmark'];

        $editor = Grafika::createEditor();
        $editor->open($image, $path);

        for ($i = $data['face']['face_hairline_144']['x'] - 120; $i <= $data['face']['face_hairline_0']['x'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$i - 1, $data['face']['face_hairline_72']['y'] -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'L1', 26,
            $data['face']['face_hairline_144']['x'] - 120 - 13,
            $data['face']['face_hairline_72']['y'] + ($data['nose']['nose_midline_0']['y'] - $data['face']['face_hairline_72']['y']) / 2 - 13, new Color("#FF0000")
        );

        for ($i = $data['face']['face_hairline_144']['x'] - 120; $i <= $data['face']['face_hairline_0']['x'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$i - 1, $data['nose']['nose_midline_0']['y'] -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'L2', 26,
            $data['face']['face_hairline_144']['x'] - 120 - 13,
            $data['nose']['nose_midline_0']['y'] + ($data['nose']['nose_right_62']['y'] - $data['nose']['nose_midline_0']['y']) / 2 - 13, new Color("#FF0000")
        );

        for ($i = $data['face']['face_hairline_144']['x'] - 120; $i <= $data['face']['face_hairline_0']['x'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$i - 1, $data['nose']['nose_right_62']['y'] -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'L3', 26,
            $data['face']['face_hairline_144']['x'] - 120 - 13,
            $data['nose']['nose_right_62']['y'] + ($data['face']['face_contour_right_0']['y'] - $data['nose']['nose_right_62']['y']) / 2 - 13, new Color("#FF0000")
        );

        for ($i = $data['face']['face_hairline_144']['x'] - 120; $i <= $data['face']['face_hairline_0']['x'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$i - 1, $data['face']['face_contour_right_0']['y'] -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        for ($i = $data['face']['face_hairline_72']['y'] - 120; $i <= $data['face']['face_contour_right_0']['y'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$data['face']['face_contour_left_63']['x'] - 1, $i -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'E1', 20,
            $data['face']['face_contour_left_63']['x'] + ($data['left_eye_eyelid']['left_eye_eyelid_0']['x'] - $data['face']['face_contour_left_63']['x']) / 2 - 11,
            $data['face']['face_contour_right_0']['y'] + 15, new Color("#FF0000")
        );

        for ($i = $data['face']['face_hairline_72']['y'] - 120; $i <= $data['face']['face_contour_right_0']['y'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$data['left_eye_eyelid']['left_eye_eyelid_0']['x'] - 1, $i -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'E2', 20,
            $data['left_eye_eyelid']['left_eye_eyelid_0']['x'] + ($data['left_eye_eyelid']['left_eye_eyelid_31']['x'] - $data['left_eye_eyelid']['left_eye_eyelid_0']['x']) / 2 - 11,
            $data['face']['face_contour_right_0']['y'] + 15, new Color("#FF0000")
        );

        for ($i = $data['face']['face_hairline_72']['y'] - 120; $i <= $data['face']['face_contour_right_0']['y'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$data['left_eye_eyelid']['left_eye_eyelid_31']['x'] - 1, $i -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'E3', 20,
            $data['left_eye_eyelid']['left_eye_eyelid_31']['x'] + ($data['right_eye_eyelid']['right_eye_eyelid_31']['x'] - $data['left_eye_eyelid']['left_eye_eyelid_31']['x']) / 2 - 11,
            $data['face']['face_contour_right_0']['y'] + 15, new Color("#FF0000")
        );

        for ($i = $data['face']['face_hairline_72']['y'] - 120; $i <= $data['face']['face_contour_right_0']['y'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$data['right_eye_eyelid']['right_eye_eyelid_31']['x'] - 1, $i -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'E4', 20,
            $data['right_eye_eyelid']['right_eye_eyelid_31']['x'] + ($data['right_eye_eyelid']['right_eye_eyelid_0']['x'] - $data['right_eye_eyelid']['right_eye_eyelid_31']['x']) / 2 - 11,
            $data['face']['face_contour_right_0']['y'] + 15, new Color("#FF0000")
        );

        for ($i = $data['face']['face_hairline_72']['y'] - 120; $i <= $data['face']['face_contour_right_0']['y'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$data['right_eye_eyelid']['right_eye_eyelid_0']['x'] - 1, $i -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'E5', 20,
            $data['right_eye_eyelid']['right_eye_eyelid_0']['x'] + ($data['face']['face_contour_right_63']['x'] - $data['right_eye_eyelid']['right_eye_eyelid_0']['x']) / 2 - 11,
            $data['face']['face_contour_right_0']['y'] + 15, new Color("#FF0000")
        );

        for ($i = $data['face']['face_hairline_72']['y'] - 120; $i <= $data['face']['face_contour_right_0']['y'] + 120; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$data['face']['face_contour_right_63']['x'] - 1, $i -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->draw($image, Grafika::createDrawingObject(
            'Line',
            [$data['left_eye']['left_eye_pupil_center']['x'], $data['left_eye']['left_eye_pupil_center']['y']],
            [$data['right_eye']['right_eye_pupil_center']['x'], $data['right_eye']['right_eye_pupil_center']['y']],
            1,
            new Color('#FF0000'))
        );

        $editor->draw($image, Grafika::createDrawingObject(
            'Line',
            [$data['left_eye']['left_eye_pupil_center']['x'], $data['left_eye']['left_eye_pupil_center']['y']],
            [$data['nose']['nose_right_62']['x'], $data['nose']['nose_right_62']['y']],
            1,
            new Color('#FF0000'))
        );

        $editor->draw($image, Grafika::createDrawingObject(
            'Line',
            [$data['right_eye']['right_eye_pupil_center']['x'], $data['right_eye']['right_eye_pupil_center']['y']],
            [$data['nose']['nose_right_62']['x'], $data['nose']['nose_right_62']['y']],
            1,
            new Color('#FF0000'))
        );

        $editor->text($image , 'W', 24,
            $data['nose']['nose_right_62']['x'] - 12,
            $data['nose']['nose_right_62']['y'] + 10, new Color("#FF0000")
        );

        $localTmpFile = $path . '-three-parts-five-eyes';
        $editor->save($image, $localTmpFile);
        return $localTmpFile;
    }

    /**
     * 绘制脸部结构
     */
    public function faceStructure($path, $data)
    {
        $data = json_decode($data, true);
        $data = $data['denselandmark'];

        $editor = Grafika::createEditor();
        $editor->open($image, $path);

        for ($i = $data['face']['face_hairline_124']['x']; $i <= $data['face']['face_hairline_20']['x']; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$i - 1, $data['face']['face_hairline_124']['y'] -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'A', 30,
            ($data['face']['face_hairline_20']['x'] - $data['face']['face_hairline_124']['x']) / 6 + $data['face']['face_hairline_124']['x'],
            $data['face']['face_hairline_124']['y'] - 40, new Color("#FF0000")
        );

        for ($i = $data['face']['face_contour_left_58']['x']; $i <= $data['face']['face_contour_right_58']['x']; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$i - 1, $data['face']['face_contour_left_58']['y'] -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'B', 30,
            ($data['face']['face_contour_right_58']['x'] - $data['face']['face_contour_left_58']['x']) / 6 + $data['face']['face_contour_left_58']['x'],
            $data['face']['face_contour_left_58']['y'] - 40, new Color("#FF0000")
        );

        for ($i = $data['face']['face_contour_left_31']['x']; $i <= $data['face']['face_contour_right_31']['x']; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$i - 1, $data['face']['face_contour_left_31']['y'] -  1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'C', 30,
            ($data['face']['face_contour_right_31']['x'] - $data['face']['face_contour_left_31']['x']) / 6 + $data['face']['face_contour_left_31']['x'],
            $data['face']['face_contour_left_31']['y'] - 40, new Color("#FF0000")
        );

        for ($i = $data['face']['face_hairline_72']['y']; $i <= $data['face']['face_contour_right_0']['y']; $i++) {
            $obj = Grafika::createDrawingObject('Ellipse', 2, 2, [$data['face']['face_hairline_72']['x'] -  1, $i - 1], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        $editor->text($image , 'D', 30,
            $data['face']['face_hairline_72']['x'] + 10,
            $data['face']['face_hairline_72']['y'] + 30, new Color("#FF0000")
        );

        $editor->draw($image,
            Grafika::createDrawingObject(
                'Line',
                [$data['face']['face_contour_right_0']['x'], $data['face']['face_contour_right_0']['y']],
                [$data['face']['face_contour_right_31']['x'], $data['face']['face_contour_right_31']['y']],
                1,
                new Color('#FF0000')
            )
        );

        $editor->draw($image,
            Grafika::createDrawingObject(
                'Line',
                [$data['face']['face_contour_right_31']['x'], $data['face']['face_contour_right_31']['y']],
                [$data['face']['face_contour_right_49']['x'], $data['face']['face_contour_right_49']['y']],
                1,
                new Color('#FF0000')
            )
        );

        $editor->text($image , 'E', 30,
            $data['face']['face_contour_right_31']['x'] + 2,
            $data['face']['face_contour_right_31']['y'] + 2, new Color("#FF0000")
        );

        $localTmpFile = $path . '-face-structure';
        $editor->save($image, $localTmpFile);
        return $localTmpFile;
    }

    /**
     * 绘制五官
     */
    public function fiveSense($path, $data)
    {
        $data = json_decode($data, true);
        $data = $data['denselandmark'];

        $editor = Grafika::createEditor();
        $editor->open($image, $path);

        foreach ($data['right_eye'] as $val) { //右眼
            if (is_array($val)) {
                $obj = Grafika::createDrawingObject('Ellipse', 4, 4, [$val['x'] - 2, $val['y'] -  2], 0, new Color('#000000'), new Color('#FF0000'));
                $editor->draw($image, $obj);
            }
        }

        foreach ($data['left_eye'] as $val) { //左眼
            if (is_array($val)) {
                $obj = Grafika::createDrawingObject('Ellipse', 4, 4, [$val['x'] - 2, $val['y'] -  2], 0, new Color('#000000'), new Color('#FF0000'));
                $editor->draw($image, $obj);
            }
        }

        foreach ($data['right_eyebrow'] as $val) { //右眉毛
            $obj = Grafika::createDrawingObject('Ellipse', 5, 5, [$val['x'] - 2.5, $val['y'] -  2.5], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        foreach ($data['left_eyebrow'] as $val) { //左眉毛
            $obj = Grafika::createDrawingObject('Ellipse', 5, 5, [$val['x'] - 2.5, $val['y'] -  2.5], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        foreach ($data['nose'] as $key => $val) { //鼻子
            if (
                strpos($key, 'nose_left') !== false ||
                strpos($key, 'nose_right') !== false ||
                $key === 'nose_midline_0'
            ) {
                $obj = Grafika::createDrawingObject('Ellipse', 4, 4, [$val['x'] - 2, $val['y'] -  2], 0, new Color('#000000'), new Color('#FF0000'));
                $editor->draw($image, $obj);
            }
        }

        foreach ($data['mouth'] as $val) { //嘴巴
            $obj = Grafika::createDrawingObject('Ellipse', 4, 4, [$val['x'] - 2, $val['y'] -  2], 0, new Color('#000000'), new Color('#FF0000'));
            $editor->draw($image, $obj);
        }

        foreach ($data['face'] as $key => $val) { //面部
            if (
                strpos($key, 'face_contour_right') !== false ||
                strpos($key, 'face_contour_left') !== false
            ){
                $obj = Grafika::createDrawingObject('Ellipse', 4, 4, [$val['x'] - 2, $val['y'] -  2], 0, new Color('#000000'), new Color('#FF0000'));
                $editor->draw($image, $obj);
            }
        }

        $localTmpFile = $path . '-five-sense';
        $editor->save($image, $localTmpFile);
        return $localTmpFile;
    }

    /**
     * 保存图片至oss
     */
    public function saveDraw($localTmpFile, $oriImg, $subFix)
    {
        $ossPath = preg_replace('/(\.(jpg|jpeg|png|bmp|webp))$/i', $subFix . '$1', $oriImg);
        Storage::put($ossPath, file_get_contents($localTmpFile));
        unlink($localTmpFile);
    }



}





