<?php

namespace App\Common;

use Illuminate\Support\Str;

trait HandyClass
{

    /**
     * @throws \ErrorException
     */
    public function __get($key)
    {
        $class = $this->guessClassFullName($key);

        if (class_exists($class)) {
            if (!app()->has($class)) app()->instance($class, app($class));

            return app()->get($class);
        }

        throw new \ErrorException('Undefined property ' . get_class($this) . '::' . $key);
    }

    protected function guessClassFullName($key)
    {
        if (Str::endsWith($key, 'Logic')) {
            $class = '\\App\\Logics\\' . ucfirst($key);
        } elseif (Str::endsWith($key, 'Service')) {
            $class = '\\App\\Services\\' . ucfirst($key);
        } elseif (Str::endsWith($key, 'Presenter')) {
            $class = '\\App\\Presenters\\' . ucfirst($key);
        } elseif (Str::endsWith($key, 'Tool')) {
            $class = '\\App\\Services\\Tools\\' . ucfirst($key);
        } else {
            $class = '';
        }

        return $class;
    }



}






