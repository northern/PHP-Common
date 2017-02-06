<?php
/*!
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * Portions copyrighted by Kohana team.
 */

namespace Northern\Common\Util;

/**
 * Array util functionality.
 */
abstract class ArrayUtil
{
    /**
     * Returns the value from an array by specifying its key or path for a
     * nested key. By default, the path delimiter is a . (dot) but other path
     * separators can be specified.
     *
     * To return the value of a key:
     *
     *    $arr = array('foo' => 'bar');
     *
     *    $value = get( $arr, 'foo' );
     *
     *    // $value == 'foo'
     *
     * To return the value of a nested key:
     *
     *    $arr = array('foo' => array('bar', array('baz' => 21) ) );
     *
     *    $value = getPath($arr, 'foo.bar.baz');
     *
     *    // $value == 21
     *
     * @param  array  $arr
     * @param  string $path
     * @param  mixed  $default
     * @param  string $delimiter
     * @return mixed
     */
    public static function get(&$arr, $path, $default = null, $delimiter = '.')
    {
        if (empty($arr)) {
            return $default;
        }

        if (isset($arr[ $path ])) {
            return $arr[ $path ];
        }

        $segments = explode($delimiter, $path);

        $cur = $arr;

        foreach ($segments as $segment) {
            if (! is_array($cur) or ! array_key_exists($segment, $cur)) {
                return $default;
            }

            $cur = $cur[ $segment ];
        }

        return $cur;
    }

    /**
     * Sets the value in an array by specifing its key or path. By default the
     * path delimiter is a . (dot) but the other path separators can be specified.
     *
     *    $arr = array();
     *
     *    set($arr, 'foo.bar.bar', 21);
     *
     * @param array  $arr
     * @param string $path
     * @param mixed  $value
     * @param string $delimiter
     */
    public static function set(&$arr, $path, $value, $delimiter = '.')
    {
        $segments = explode($delimiter, $path);

        if (count($segments) === 1) {
            $arr[ $path ] = $value;

            return;
        }

        $cur = &$arr;

        foreach ($segments as $segment) {
            if (is_array($cur) and ! array_key_exists($segment, $cur)) {
                $cur[ $segment ] = array();
            }

            $cur = &$cur[ $segment ];
        }

        $cur = $value;
    }

    /**
     * Inserts a new value into a key or path only if the key or path does not yet
     * exists.
     *
     * @param array  $arr
     * @param string $path
     * @param mixed  $value
     * @param string $delimiter
     */
    public static function insert(&$arr, $path, $value, $delimiter = '.')
    {
        if (! static::exists($arr, $path, $delimiter)) {
            static::set($arr, $path, $value, $delimiter);
        }
    }

    /**
     * Deletes an entry in the an array by specifing its key or path. By default the
     * path delimiter is a "." (dot) but the other path separators can be specified.
     *
     * @param array        $arr
     * @param string|array $path
     * @param string       $delimiter
     */
    public static function delete(&$arr, $path, $delimiter = '.')
    {
        if (is_array($path)) {
            $paths = $path;

            foreach ($paths as $path) {
                static::delete($arr, $path, $delimiter);
            }
        } else {
            if (isset($arr[ $path ])) {
                unset($arr[ $path ]);

                return;
            }

            $segments = explode($delimiter, $path);

            $lastSegment = end($segments);
            $segments = array_slice($segments, 0, -1);

            $cur = &$arr;

            foreach ($segments as $segment) {
                if (! array_key_exists($segment, $cur)) {
                    return;
                }

                $cur = &$cur[ $segment ];
            }

            unset($cur[ $lastSegment ]);
        }
    }

    /**
     * Tests if a key or path exists in a specified array. If the specified key or
     * path exists this method will return TRUE otherwise FALSE.
     *
     * @param  array   $arr
     * @param  string  $path
     * @param  string  $delimiter
     * @return boolean
     */
    public static function exists(array &$arr, $path, $delimiter = '.')
    {
        if (array_key_exists($path, $arr)) {
            return true;
        }

        $segments = explode($delimiter, $path);

        $cur = $arr;

        foreach ($segments as $segment) {
            if (! is_array($cur) or ! array_key_exists($segment, $cur)) {
                return false;
            }

            $cur = $cur[$segment];
        }

        return true;
    }

    /**
     * This method will prefix all values of a specified array with a given value.
     *
     * @param array  $values
     * @param string $prefix
     */
    public static function prefix(&$values, $prefix)
    {
        foreach ($values as &$value) {
            $value = "{$prefix}{$value}";
        }

        return $values;
    }

    /**
     * This method will postfix all values of a specified array with a given value.
     *
     * @param array  $values
     * @param string $postfix
     */
    public static function postfix(&$values, $postfix)
    {
        foreach ($values as &$value) {
            $value = "{$value}{$postfix}";
        }

        return $values;
    }

    /**
     * This method 'flattens' an associative array to a list array. The key/value
     * pair of the associative array are combined with $glue.
     *
     * @param  array  $array
     * @param  string $glue
     * @return array
     */
    public static function flatten($arr, $glue = '=')
    {
        $list = array();

        foreach ($arr as $key => $value) {
            $list[] = "{$key}{$glue}{$value}";
        }

        return $list;
    }

    /**
     * This method will return all the keys of a given array and optionally
     * prefix each key with a specified prefix.
     *
     * @param  array  $array
     * @param  string $prefix
     * @return array
     */
    public static function keys($array, $prefix = null)
    {
        $keys = array_keys($array);

        if (! empty($prefix)) {
            $keys = static::prefix($keys, $prefix);
        }

        return $keys;
    }

    /**
     * This method will return all values of a given array and optionally prefix
     * each value with a specified prefix.
     *
     * @param  array  $array
     * @param  string $prefix
     * @return array
     */
    public static function values($array, $prefix = null)
    {
        $values = array_values($array);

        if (! empty($prefix)) {
            $values = static::prefix($values, $prefix);
        }

        return $values;
    }

    /**
     * Recursive version of array_map, applies one or more callbacks to all
     * elements in an array, including sub-arrays.
     *
     * Apply "strip_tags" to every element in the array
     *    $array = ArrayHelper::map('strip_tags', $array);
     *
     * Apply $this->filter to every element in the array
     *    $array = ArrayHelper::map(array(array($this,'filter')), $array);
     *
     * Apply strip_tags and $this->filter to every element
     *    $array = ArrayHelper::map(array('strip_tags',array($this,'filter')), $array);
     *
     * @param  mixed $callbacks
     * @param  array $array
     * @param  mixed $keys
     * @return array
     */
    public static function map($callbacks, $arr, $keys = null)
    {
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $arr[ $key ] = static::map($callbacks, $arr[ $key ]);
            } elseif (! is_array($keys) or in_array($key, $keys)) {
                if (is_array($callbacks)) {
                    foreach ($callbacks as $callback) {
                        $arr[ $key ] = call_user_func($callback, $arr[ $key ]);
                    }
                } else {
                    $arr[ $key ] = call_user_func($callbacks, $arr[ $key ]);
                }
            }
        }

        return $arr;
    }

    /**
     * Remaps an array structure to another array structure. The $createMode
     * parameter indicates that if a entry doesn't exists in the $values
     * array if it should be created with a default value in the results.
     *
     * @param  array   $values
     * @param  array   $map
     * @param  boolean $createMode
     * @return array
     */
    public static function remap(&$values, array $map, $createMode = true)
    {
        $results = array();

        foreach ($map as $keyfrom => $keyto) {
            if (self::exists($values, $keyfrom) === true or $createMode === true) {
                self::set($results, $keyto, self::get($values, $keyfrom));
            }
        }

        return $results;
    }

    /**
     * Remaps a collection.
     *
     * @param  array $collection
     * @param  array $map
     * @return array
     */
    public static function remapCollection(array $collection, array $map, $createMode = true)
    {
        $results = array();

        foreach ($collection as $item) {
            $results[] = self::remap($item, $map, $createMode);
        }

        return $results;
    }

    /**
     * Recursivly merge two arrays together.
     *
     * Source: http://au1.php.net/manual/en/function.array-merge-recursive.php#92195
     *
     * @param  array $array1
     * @param  array $array2
     * @return array
     */
    public static function merge(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = static::merge($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * This method tests for the existence of $needle in $haystack. If $needle is a
     * non-array type this method will function exaclty like the build in PHP in_array
     * function, however, if $needle is an array this method will iterate over each
     * of the items in the $needle array and checks for the existence of each of the
     * values in $haystack.
     *
     * @param  mixed   $needle
     * @param  array   $haystack
     * @return boolean
     */
    public static function contains($needle, array $haystack)
    {
        if (is_array($needle)) {
            $needles = $needle;

            foreach ($needles as $needle) {
                if (! in_array($needle, $haystack)) {
                    return false;
                }
            }

            return true;
        } else {
            return in_array($needle, $haystack);
        }
    }

    /**
     * Returns a value for a specified array key and deletes the key.
     *
     * @param  array  $arr
     * @param  string $path
     * @param  mixed  $default
     * @param  string $delimiter
     * @return mixed
     */
    public static function extract(array &$arr, $path, $default = null, $delimiter = '.')
    {
        $value = static::get($arr, $path, $default, $delimiter);

        static::delete($arr, $path, $delimiter);

        return $value;
    }

    /**
     * Performs a "deep" compare on two given arrays. This method returns TRUE if both
     * arrays are exactly the same, otherwise this method will return FALSE.
     *
     * @param  array   $a
     * @param  array   $b
     * @return boolean
     */
    public static function compare(array $a, array $b)
    {
        return serialize($a) === serialize($b);
    }
}
