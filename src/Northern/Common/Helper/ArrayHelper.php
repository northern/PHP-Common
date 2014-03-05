<?php
/*
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
 */

namespace Northern\Common\Helper;

/**
 * Array helper functionality.
 */
abstract class ArrayHelper {

	/**
	 * This method will checks for the presence of an attribute in a given string
	 * and return its value. If the attribute does not exist a default value is
	 * returned instead.
	 * 
	 * @param  array $array
	 * @param  string $key
	 * @param  mixed $default
	 * @return mixed
	 */
	static public function get( $array, $key, $default = NULL )
	{
		return ( isset( $array[$key] ) ? $array[ $key ] : $default );
	}
	
	/**
	 * This method 'flattens' an associative array to a list array. The key/value
	 * pair of the associative array are combined with $glue.
	 * 
	 * @param  array $array
	 * @param  string $glue
	 * @return array
	 */
	static public function flatten( $array, $glue = '=' )
	{
		$list = array();
		
		foreach( $array as $key => $value )
		{
			$list[] = "{$key}{$glue}{$value}";
		}
		
		return $list;
	}
	
	/**
	 * This method will return all the keys of a given array and optionally
	 * prefix each key with a specified prefix.
	 * 
	 * @param  array $array
	 * @param  string $prefix
	 * @return array
	 */
	static public function getKeys( $array, $prefix = NULL )
	{
		$keys = array_keys( $array );
		
		if( !empty( $prefix ) )
		{
			$keys = ArrayHelper::prefix( $keys, $prefix );
		}
		
		return $keys;
	}
	
	/**
	 * This method will return all values of a given array and optionally prefix
	 * each value with a specified prefix.
	 * 
	 * @param  array $array
	 * @param  string $prefix
	 * @return array
	 */
	static public function getValues( $array, $prefix = NULL )
	{
		$values = array_values( $array );
		
		if( !empty( $prefix ) )
		{
			$values = ArrayHelper::prefix( $values, $prefix );
		}
		
		return $values;
	}
	
	/**
	 * This method will prefix all values of a specified array with a given value.
	 * 
	 * @param array $values
	 * @param string $prefix
	 */
	static public function prefix( &$values, $prefix )
	{
		foreach( $values as &$value )
		{
			$value = "{$prefix}{$value}";
		}
	}
	
	/**
	 * Tests if a path exists in a specified array. If the specified path exists
	 * this method will return TRUE otherwise FALSE.
	 *
	 * @param  array  $arr
	 * @param  string $path
	 * @param  string $delimiter
	 * @return boolean
	 */
	public static function isPath( array &$arr, $path, $delimiter = '.' )
	{
		$segments = explode( $delimiter, $path );
			
		$cur = $arr;
	
		foreach( $segments as $segment )
		{
			if( ! is_array( $cur ) OR ! array_key_exists( $segment, $cur ) )
			{
				return FALSE;
			}
	
			$cur = $cur[$segment];
		}
	
		return TRUE;
	}
	
	/**
	 * Returns the value from an array by specifying its path. By default
	 * the path delimiter is a . (dot) but other path separators can be 
	 * specified.
	 * 
	 *    $arr = array('foo' => array('bar', array('x' => 21) ) );
	 * 
	 * To return the value of 'x' specify the path:
	 * 
	 *    $x = getPath($arr, 'foo.bar.x');
	 * 
	 * @param  array $arr
	 * @param  string $path
	 * @param  mixed $default
	 * @param  string $delimiter
	 * @return mixed
	 */
	public static function getPath( &$arr, $path, $default = NULL, $delimiter = '.'  )
	{
		if( empty( $arr ) )
		{
			return $default;
		}
		
		$segments = explode( $delimiter, $path );
		
		$cur = $arr;
		
		foreach( $segments as $segment )
		{
			if( ! is_array( $cur ) OR ! array_key_exists( $segment, $cur ) )
			{
				return $default;
			}
			
			$cur = $cur[$segment];
		}
		
		return $cur;
	}
	
	/**
	 * Sets the value in an array by specifing its path. By default the path
	 * delimiter is a . (dot) but the other path separators can be specified.
	 * 
	 * $arr = array();
	 * setPath($arr, 'foo.bar.x', 21);
	 * 
	 * @param array $arr
	 * @param string $path
	 * @param mixed $value
	 * @param string $delimiter
	 */
	public static function setPath( &$arr, $path, $value, $delimiter = '.', $exceptNull = TRUE )
	{
		if( $value === NULL AND ! $exceptNull )
		{
			return;
		}
		
		$segments = explode( $delimiter, $path );
		
		$cur = &$arr;
		
		foreach( $segments as $segment )
		{
			if( ! array_key_exists( $segment, $cur ) )
			{
				$cur[$segment] = array();
			}
			
			$cur = &$cur[$segment];
		}
		
		$cur = $value;
	}
	
	/**
	 * Deletes an entry in the an array by specifing its path. By default the 
	 * path delimiter is a . (dot) but the other path separators can be specified.
	 * 
	 * @param array $arr
	 * @param string $path
	 * @param string $delimiter
	 */
	public static function deletePath( &$arr, $path, $delimiter = '.' )
	{
		$segments = explode( $delimiter, $path );
		
		$lastSegment = end( $segments );
		$segments = array_slice($segments , 0, -1 );
		
		$cur = &$arr;
		
		foreach( $segments as $segment )
		{
			if( ! array_key_exists( $segment, $cur ) )
			{
				return;
			}
			
			$cur = &$cur[$segment];
		}
		
		unset( $cur[ $lastSegment ] );
	}
	
	/**
	 * Removes an array of unallowed $paths from given $arr.
	 * 
	 * @param array $arr
	 * @param array $paths
	 * @param string $delimiter
	 */
	public static function deletePaths( &$arr, $paths, $delimiter = '.' )
	{
		foreach( $paths as $path )
		{
			self::deletePath( $arr, $path, $delimiter );
		}
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
	public static function map( $callbacks, $array, $keys = NULL )
	{
		foreach( $array as $key => $val )
		{
			if( is_array($val) )
			{
				$array[$key] = self::map($callbacks, $array[$key]);
			}
			elseif( ! is_array($keys) OR in_array($key, $keys) )
			{
				if( is_array($callbacks) )
				{
					foreach( $callbacks as $cb )
					{
						$array[$key] = call_user_func( $cb, $array[$key] );
					}
				}
				else
				{
					$array[$key] = call_user_func( $callbacks, $array[$key] );
				}
			}
		}
	 	
		return $array;
	}

	/**
	 * Remaps an array structure to another array structure. The $createMode
	 * parameter indicates that if a entry doesn't exists in the $values
	 * array if it should be created with a default value in the results.
	 * 
	 * @param  array $values
	 * @param  array $map
	 * @param  boolean $createMode
	 * @return array
	 */
	static public function remap( &$values, array $map, $createMode = TRUE )
	{
		$results = array();
		
		foreach( $map as $keyfrom => $keyto )
		{
			if( self::isPath( $values, $keyfrom ) === TRUE OR $createMode === TRUE )
			{
				self::setPath( $results, $keyto, self::getPath( $values, $keyfrom ) );
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
	static public function remapCollection( array $collection, array $map, $createMode = TRUE )
	{
		$results = array();
		
		foreach( $collection as $item )
		{
			$results[] = self::remap( $item, $map, $createMode );
		}
		
		return $results;
	}
	
}
