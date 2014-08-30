<?php 

namespace Northern\Common\Helper;

class ExceptionHelper {
	
	/**
	 * Returns a formatted string containing the class names of the exeptions thrown.
	 *  
	 * @param Exception $e
	 * @param string $separator
	 * @return string
	 */
	public static function getExceptionNameHierarchy( \Exception $e, $separator = ' >> ' )
	{
		$names = array();
		
		// Loop through the exception hierarchy until there is no previous
		// exception and store the class names of each found exception in the
		// $names array.
		do
		{
			$names[] = get_class( $e );
		}
		while( ( $e = $e->getPrevious() ) !== NULL );
		
		// Concatenate the exception name with the separator.
		$names = implode( $separator, $names );
		
		return $names;
	}
	
	/**
	 * Returns the formatted message of a passed in exception.
	 * 
	 * @param  \Exception $e
	 * @return string
	 */
	public static function getFormattedExceptionMessage( \Exception $e )
	{
		$name = static::getExceptionNameHierarchy( $e );
		
		list( $file, $line, $stackTrace ) = static::getOriginalExceptionFileAndLineNumber( $e );
		
		$message = "{$name}: {$e->getMessage()} ({$e->getcode()})\n{$file} {$line}\n{$stackTrace}";
		
		return $message;
	}
	
	/**
	 * Returns the file name, line number and stack trace where the original exception 
	 * was thrown.
	 * 
	 * @param \Exception $e
	 * @return array
	 */
	public static function getOriginalExceptionFileAndLineNumber( \Exception $e )
	{
		$names = array();
		
		do
		{
			// Keep track of the "previous" exception in the list. Once $e equals NULL
			// we want to return the values of $p which at the point contains the 
			// previous execption.
			$p = $e;
			
			$names[] = get_class( $e );
		}
		while( ( $e = $e->getPrevious() ) !== NULL );
		
		return array( $p->getFile(), $p->getLine(), $p->getTraceAsString() );
	}

}
