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

        $left = $data['face']['face_hairline_144']['x'] - 120;
        $right = $data['face']['face_hairline_0']['x'] + 120;
        $top = $data['face']['face_hairline_72']['y'] - 120;
        $bottom = $data['face']['face_contour_right_0']['y'] + 120;

        $minHeight = min(
            $data['nose']['nose_midline_0']['y'] - $data['face']['face_hairline_72']['y'],
            $data['nose']['nose_right_62']['y'] - $data['nose']['nose_midline_0']['y'],
            $data['face']['face_contour_right_0']['y'] - $data['nose']['nose_right_62']['y']
        );
        $heightSize = min($minHeight * 0.4, ceil($heightSize = $data['face']['face_hairline_144']['x'] / 2));

        $this->drawLine($editor, $image, [$left, $data['face']['face_hairline_72']['y']], [$right, $data['face']['face_hairline_72']['y']]);

        $editor->text($image , 'L1', $heightSize,
            $data['face']['face_hairline_144']['x'] - $heightSize * 2,
            $data['face']['face_hairline_72']['y'] + ($data['nose']['nose_midline_0']['y'] - $data['face']['face_hairline_72']['y']) / 2 - $heightSize / 2,
            new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$left, $data['nose']['nose_midline_0']['y']], [$right, $data['nose']['nose_midline_0']['y']]);

        $editor->text($image , 'L2', $heightSize,
            $data['face']['face_hairline_144']['x'] - $heightSize * 2,
            $data['nose']['nose_midline_0']['y'] + ($data['nose']['nose_right_62']['y'] - $data['nose']['nose_midline_0']['y']) / 2 - $heightSize / 2,
            new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$left, $data['nose']['nose_right_62']['y']], [$right, $data['nose']['nose_right_62']['y']]);

        $editor->text($image , 'L3', $heightSize,
            $data['face']['face_hairline_144']['x'] - $heightSize * 2,
            $data['nose']['nose_right_62']['y'] + ($data['face']['face_contour_right_0']['y'] - $data['nose']['nose_right_62']['y']) / 2 - $heightSize / 2,
            new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$left, $data['face']['face_contour_right_0']['y']], [$right, $data['face']['face_contour_right_0']['y']]);

        $minWidth = min(
            $data['left_eye_eyelid']['left_eye_eyelid_0']['x'] - $data['face']['face_contour_left_63']['x'],
            $data['left_eye_eyelid']['left_eye_eyelid_31']['x'] - $data['left_eye_eyelid']['left_eye_eyelid_0']['x'],
            $data['right_eye_eyelid']['right_eye_eyelid_31']['x'] - $data['left_eye_eyelid']['left_eye_eyelid_31']['x'],
            $data['right_eye_eyelid']['right_eye_eyelid_0']['x'] - $data['right_eye_eyelid']['right_eye_eyelid_31']['x'],
            $data['face']['face_contour_right_63']['x'] - $data['right_eye_eyelid']['right_eye_eyelid_0']['x']
        );
        $widthSize = $minWidth * 0.5;

        $this->drawLine($editor, $image, [$data['face']['face_contour_left_63']['x'], $top], [$data['face']['face_contour_left_63']['x'], $bottom]);

        $editor->text($image , 'E1', $widthSize,
            $data['face']['face_contour_left_63']['x'] + ($data['left_eye_eyelid']['left_eye_eyelid_0']['x'] - $data['face']['face_contour_left_63']['x']) / 2 - $widthSize / 1.5,
            $this->calEY($image->getHeight(), $data, $widthSize), new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$data['left_eye_eyelid']['left_eye_eyelid_0']['x'], $top], [$data['left_eye_eyelid']['left_eye_eyelid_0']['x'], $bottom]);

        $editor->text($image , 'E2', $widthSize,
            $data['left_eye_eyelid']['left_eye_eyelid_0']['x'] + ($data['left_eye_eyelid']['left_eye_eyelid_31']['x'] - $data['left_eye_eyelid']['left_eye_eyelid_0']['x']) / 2 - $widthSize / 1.5,
            $this->calEY($image->getHeight(), $data, $widthSize), new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$data['left_eye_eyelid']['left_eye_eyelid_31']['x'], $top], [$data['left_eye_eyelid']['left_eye_eyelid_31']['x'], $bottom]);

        $editor->text($image , 'E3', $widthSize,
            $data['left_eye_eyelid']['left_eye_eyelid_31']['x'] + ($data['right_eye_eyelid']['right_eye_eyelid_31']['x'] - $data['left_eye_eyelid']['left_eye_eyelid_31']['x']) / 2 - $widthSize / 1.5,
            $this->calEY($image->getHeight(), $data, $widthSize), new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$data['right_eye_eyelid']['right_eye_eyelid_31']['x'], $top], [$data['right_eye_eyelid']['right_eye_eyelid_31']['x'], $bottom]);

        $editor->text($image , 'E4', $widthSize,
            $data['right_eye_eyelid']['right_eye_eyelid_31']['x'] + ($data['right_eye_eyelid']['right_eye_eyelid_0']['x'] - $data['right_eye_eyelid']['right_eye_eyelid_31']['x']) / 2 - $widthSize / 1.5,
            $this->calEY($image->getHeight(), $data, $widthSize), new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$data['right_eye_eyelid']['right_eye_eyelid_0']['x'], $top], [$data['right_eye_eyelid']['right_eye_eyelid_0']['x'], $bottom]);

        $editor->text($image , 'E5', $widthSize,
            $data['right_eye_eyelid']['right_eye_eyelid_0']['x'] + ($data['face']['face_contour_right_63']['x'] - $data['right_eye_eyelid']['right_eye_eyelid_0']['x']) / 2 - $widthSize / 1.5,
            $this->calEY($image->getHeight(), $data, $widthSize), new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$data['face']['face_contour_right_63']['x'], $top], [$data['face']['face_contour_right_63']['x'], $bottom]);

        $this->drawLine($editor, $image, [$data['left_eye']['left_eye_pupil_center']['x'], $data['left_eye']['left_eye_pupil_center']['y']], [$data['right_eye']['right_eye_pupil_center']['x'], $data['right_eye']['right_eye_pupil_center']['y']]);

        $this->drawLine($editor, $image, [$data['left_eye']['left_eye_pupil_center']['x'], $data['left_eye']['left_eye_pupil_center']['y']], [$data['nose']['nose_right_62']['x'], $data['nose']['nose_right_62']['y']]);

        $this->drawLine($editor, $image, [$data['right_eye']['right_eye_pupil_center']['x'], $data['right_eye']['right_eye_pupil_center']['y']], [$data['nose']['nose_right_62']['x'], $data['nose']['nose_right_62']['y']]);

        $wSize = ($data['right_eye_eyelid']['right_eye_eyelid_31']['x'] - $data['left_eye_eyelid']['left_eye_eyelid_31']['x']) * 0.5;
        $editor->text($image , 'W', $wSize,
            $data['nose']['nose_right_62']['x'] - $wSize / 2,
            $data['nose']['nose_right_62']['y'] + 8, new Color("#FF0000")
        );

        $localTmpFile = $path . '-three-parts-five-eyes';
        $editor->save($image, $localTmpFile);
        return $localTmpFile;
    }

    protected function calEY($imgHeight, $data, $size)
    {
        if ($data['face']['face_contour_right_0']['y'] + 13 + $size >= $imgHeight) {
            for ($i = 12; $i >= 4; $i--) {
                if ($i == 4) {
                    return $data['face']['face_contour_right_0']['y'] + 4;
                }

                if ($data['face']['face_contour_right_0']['y'] + $i + $size >= $imgHeight) {
                    continue;
                } else {
                    return $data['face']['face_contour_right_0']['y'] + $i;
                }
            }
        }

        return $data['face']['face_contour_right_0']['y'] + 13;
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

        $minHeight = min(
            $data['face']['face_contour_left_58']['y'] - $data['face']['face_hairline_123']['y'],
            $data['face']['face_contour_left_31']['y'] - $data['face']['face_contour_left_58']['y']
        );
        $size = $minHeight * 0.5;

        $this->drawLine($editor, $image, [$data['face']['face_hairline_123']['x'], $data['face']['face_hairline_123']['y']], [$data['face']['face_hairline_21']['x'], $data['face']['face_hairline_21']['y']]);

        $editor->text($image , 'A', $size,
            $data['face']['face_hairline_123']['x'] + 3,
            $data['face']['face_hairline_123']['y'] - $size - 5, new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$data['face']['face_contour_left_58']['x'], $data['face']['face_contour_left_58']['y']], [$data['face']['face_contour_right_58']['x'], $data['face']['face_contour_right_58']['y']]);

        $editor->text($image , 'B', $size,
            $data['face']['face_contour_left_58']['x'] + 3,
            $data['face']['face_contour_left_58']['y'] - $size - 5, new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$data['face']['face_contour_left_31']['x'], $data['face']['face_contour_left_31']['y']], [$data['face']['face_contour_right_31']['x'], $data['face']['face_contour_right_31']['y']]);

        $editor->text($image , 'C', $size,
            $data['face']['face_contour_left_31']['x'] + 3,
            $data['face']['face_contour_left_31']['y'] - $size - 5, new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$data['face']['face_hairline_72']['x'], $data['face']['face_hairline_72']['y']], [$data['face']['face_contour_right_0']['x'], $data['face']['face_contour_right_0']['y']]);

        $editor->text($image , 'D', $size,
            $data['face']['face_hairline_72']['x'] + 8,
            $data['face']['face_hairline_72']['y'] + $size/1.6, new Color("#FF0000")
        );

        $this->drawLine($editor, $image, [$data['face']['face_contour_right_0']['x'], $data['face']['face_contour_right_0']['y']], [$data['face']['face_contour_right_31']['x'], $data['face']['face_contour_right_31']['y']]);

        $this->drawLine($editor, $image, [$data['face']['face_contour_right_31']['x'], $data['face']['face_contour_right_31']['y']], [$data['face']['face_contour_right_49']['x'], $data['face']['face_contour_right_49']['y']]);

        $editor->text($image , 'E', $size,
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

        $red = new Color('#FF0000');
        $size = $this->getEllipseSize($image);

        foreach ($data['right_eye_eyelid'] as $val) { //右眼
            $editor->draw($image, Grafika::createDrawingObject('Ellipse', $size['min'], $size['min'], [$val['x'] - $size['min'] / 2, $val['y'] - $size['min'] / 2], 0, $red, $red));
        }

        foreach ($data['left_eye_eyelid'] as $val) { //左眼
            $editor->draw($image, Grafika::createDrawingObject('Ellipse', $size['min'], $size['min'], [$val['x'] - $size['min'] / 2, $val['y'] - $size['min'] / 2], 0, $red, $red));
        }

        foreach ($data['right_eyebrow'] as $val) { //右眉毛
            $editor->draw($image, Grafika::createDrawingObject('Ellipse', $size['max'], $size['max'], [$val['x'] - $size['max'] / 2, $val['y'] - $size['max'] / 2], 0, $red, $red));
        }

        foreach ($data['left_eyebrow'] as $val) { //左眉毛
            $editor->draw($image, Grafika::createDrawingObject('Ellipse', $size['max'], $size['max'], [$val['x'] - $size['max'] / 2, $val['y'] - $size['max'] / 2], 0, $red, $red));
        }

        foreach ($data['nose'] as $key => $val) { //鼻子
            if (
                strpos($key, 'nose_left') !== false ||
                strpos($key, 'nose_right') !== false ||
                $key === 'nose_midline_0'
            ) {
                $editor->draw($image, Grafika::createDrawingObject('Ellipse', $size['min'], $size['min'], [$val['x'] - $size['min'] / 2, $val['y'] - $size['min'] / 2], 0, $red, $red));
            }
        }

        foreach ($data['mouth'] as $val) { //嘴巴
            $editor->draw($image, Grafika::createDrawingObject('Ellipse', $size['max'], $size['max'], [$val['x'] - $size['max'] / 2, $val['y'] - $size['max'] / 2], 0, $red, $red));
        }

        foreach ($data['face'] as $key => $val) { //面部
            if (
                strpos($key, 'face_contour_right') !== false ||
                strpos($key, 'face_contour_left') !== false ||
                strpos($key, 'face_hairline') !== false
            ){
                $editor->draw($image, Grafika::createDrawingObject('Ellipse', $size['max'], $size['max'], [$val['x'] - $size['max'] / 2, $val['y'] - $size['max'] / 2], 0, $red, $red));
            }
        }

        $localTmpFile = $path . '-five-sense';
        $editor->save($image, $localTmpFile);
        return $localTmpFile;
    }

    /**
     * 保存图片至oss
     */
    public function saveDraw($localTmpFile, $target)
    {
        Storage::put($target, file_get_contents($localTmpFile));
        unlink($localTmpFile);
    }

    /**
     * 三庭五眼对应的后缀
     */
    public function threePartsFiveEyesSuffix($oriImg)
    {
        $suffix = '-three-parts-five-eyes';
        return $this->genOriSuffix($oriImg, $suffix);
    }

    /**
     * 脸部结构对应的后缀
     */
    public function faceStructureSuffix($oriImg)
    {
        $suffix = '-face-structure';
        return $this->genOriSuffix($oriImg, $suffix);
    }

    /**
     * 五官对应的后缀
     */
    public function fiveSenseSuffix($oriImg)
    {
        $suffix = '-five-sense';
        return $this->genOriSuffix($oriImg, $suffix);
    }

    /**
     * 原图后缀拼接
     */
    public function genOriSuffix($oriImg, $suffix)
    {
        return preg_replace('/(\.(jpg|jpeg|png|bmp|webp))$/i', $suffix . '$1', $oriImg);
    }

    /**
     * 根据图片大小得到画线的粗细
     */
    protected function getLineSize($image)
    {
        $px = min($image->getWidth(), $image->getHeight());

        if ($px <= 300) { //0-300
            return 1;
        } elseif ($px <= 700) { //301-700
            return 2;
        } elseif ($px <= 900) { //701-900
            return 3;
        } elseif ($px <= 1200) { //901-1200
            return 4;
        } else { //1201-inf
            return 5;
        }
    }

    /**
     * 根据图片大小得到画圆的粗细
     */
    protected function getEllipseSize($image)
    {
        $iWidth = $image->getWidth();
        $iHeight = $image->getHeight();

        $px = min($iWidth, $iHeight);

        $min = $max = 0;

        if ($px <= 400) {
            $min = 1;
            $max = 1;
        } else {
            $min = ceil( ($px - 400) / 200 ) + 1;
            $max = $min + 1;

            if (max($iWidth, $iHeight) / $px >= 2)
                $max += 1;
        }

        return [
            'min' => $min,
            'max' => $max,
        ];
    }

    /**
     * 画线段
     */
    protected function drawLine($editor, $image, array $start, array $end, $size = null)
    {
        $color = new Color('#FF0000');
        $size = $size ?: $this->getLineSize($image);

        if (abs($start[0] - $end[0]) >= abs($start[1] - $end[1])) { //横斜线
            $lineType = 1;
        } elseif (abs($start[0] - $end[0]) < abs($start[1] - $end[1])) { //竖斜线
            $lineType = 2;
        }

        $editor->draw($image, Grafika::createDrawingObject('Line', $start, $end, 1, $color));

        if ($size >= 2) {
            if ($lineType == 1) {
                $editor->draw($image, Grafika::createDrawingObject('Line', [$start[0], $start[1] + 1], [$end[0], $end[1] + 1], 1, $color));
            } elseif ($lineType == 2) {
                $editor->draw($image, Grafika::createDrawingObject('Line', [$start[0] - 1, $start[1]], [$end[0] - 1, $end[1]], 1, $color));
            }
        }

        if ($size >= 3) {
            if ($lineType == 1) {
                $editor->draw($image, Grafika::createDrawingObject('Line', [$start[0], $start[1] - 1], [$end[0], $end[1] - 1], 1, $color));
            } elseif ($lineType == 2) {
                $editor->draw($image, Grafika::createDrawingObject('Line', [$start[0] + 1, $start[1]], [$end[0] + 1, $end[1]], 1, $color));
            }
        }

        if ($size >= 4) {
            if ($lineType == 1) {
                $editor->draw($image, Grafika::createDrawingObject('Line', [$start[0], $start[1] + 2], [$end[0], $end[1] + 2], 1, $color));
            } elseif ($lineType == 2) {
                $editor->draw($image, Grafika::createDrawingObject('Line', [$start[0] - 2, $start[1]], [$end[0] - 2, $end[1]], 1, $color));
            }
        }

        if ($size >= 5) {
            if ($lineType == 1) {
                $editor->draw($image, Grafika::createDrawingObject('Line', [$start[0], $start[1] - 2], [$end[0], $end[1] - 2], 1, $color));
            } elseif ($lineType == 2) {
                $editor->draw($image, Grafika::createDrawingObject('Line', [$start[0] + 2, $start[1]], [$end[0] + 2, $end[1]], 1, $color));
            }
        }
    }



}





