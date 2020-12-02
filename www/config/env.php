<?php
if (file_exists(__DIR__ . '/configs.php'))
{
    include __DIR__ . '/configs.php';
}

if (!function_exists('env'))
{
    /**
     * @param $key
     * @param null $default
     * @return array|mixed|string|null
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false)
        {
            return $default;
        }

        return $value;
    }
}