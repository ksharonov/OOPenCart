<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26.03.2018
 * Time: 17:21
 */

namespace app\components;


use yii\base\Component;

class CookieComponent extends Component implements \ArrayAccess
{
    public function get($key)
    {
        return $_COOKIE[$key];
    }

    public function set($key, $value)
    {
        setcookie($key, $value, time() + 3600 * 24 * 7, "/");
    }

    public function remove($key)
    {
        setcookie($key, "", time() - 3600, "/");
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($_COOKIE[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($_COOKIE[$offset]) ? $_COOKIE[$offset] : null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        //$_COOKIE[$offset] = $value;
        setcookie($offset, $value, time() + 3600 * 24 * 7, "/");
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        setcookie($offset, "", time() - 3600, "/");
    }
}