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
 */

namespace Northern\Common\Helper;

use Northern\Common\Helper\ArrayHelper as Arr;

/**
 * Filter helper functionality.
 */
abstract class FilterHelper {

	/**
	 * This method filters the specified values with the filters provided.
	 *
	 * @param  $values
	 * @param  $filters
	 * @return array
	 */
	public static function filter( array &$values, array $filters )
	{
		$keys = Arr::keys( $filters );

		foreach( $keys as $key )
		{
			$value        = Arr::get( $values,  $key );
			$valueFilters = Arr::get( $filters, $key );

			Arr::set( $values, $key, static::applyFilters( $value, $valueFilters ) );
		}

		return $values;
	}

	protected static function applyFilters( $value, $filters )
	{
		if( is_array( $filters ) )
		{
			foreach( $filters as $filter )
			{
				$value = static::applyFilter( $value, $filter );
			}
		}
		else
		{
			$value = static::applyFilter( $value, $filters );
		}

		return $value;
	}

	protected function applyFilter( $value, $filter )
	{
		if( is_array( $filter ) )
		{
			$filterClass = "\\Northern\\Common\\Helper\\Filter\\{$filter[0]}Filter";

			if( ! class_exists( $filterClass ) )
			{
				throw new \Exception("Missing class {$filterClass}");
			}

			$reflector = new \ReflectionClass( $filterClass );
			$filter = $reflector->newInstanceArgs( $filter[1] );
		}
		else
		if( is_string( $filter ) )
		{
			$filterClass = "\\Northern\\Common\\Helper\\Filter\\{$filter}Filter";

			if( ! class_exists( $filterClass ) )
			{
				throw new \Exception("Missing class {$filterClass}");
			}

			$filter = new $filterClass();
		}
		
		if( ! class_implements($filter, '\Northern\Common\Filter\FilterInterface') )
		{
			throw new \Exception("Filter is not a valid object {$value}.");
		}

		$value = $filter->filter( $value );

		return $value;
	}

}
