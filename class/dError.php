<?php
//phpinfo();

class dError
{
    public static $devmode;


	public static function init($devmode = false)
	{
        self::$devmode = $devmode;

        if(self::$devmode)
        {
            //ini_set('error_reporting', version_compare(PHP_VERSION,5,'>=') && version_compare(PHP_VERSION,6,'<') ?E_ALL^E_STRICT:E_ALL);
            error_reporting(E_ALL ^ E_STRICT);
            ini_set('display_errors', 'On');
            ini_set('log_errors', 'Off');
        }
        else
        {
            error_reporting(0);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
        }

		set_error_handler(			array(__CLASS__, '_error'));
		set_exception_handler(		array(__CLASS__, '_exception'));
		register_shutdown_function(	array(__CLASS__, '_fatal'));
	}



	public static function _exception($exception)
	{
		if(self::$devmode)
			echo self::parseException($exception);
		return true;
	}



	public static function _error($type, $message, $file, $line)
	{
		//if((error_reporting() & $type) === 0) return true;
		if(self::$devmode)
			throw new ErrorException($message, $type, 0, $file, $line);
		return true;
		//return self::_exception(new ErrorException($message, $type, 0, $file, $line));
	}



	public static function _fatal()
	{
		if($error = error_get_last())
			self::_exception(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
	}



	/**
	 *	PARSE EXCEPTION TO READABLE STRING
	 */
	public static function parseException($e)
	{
		$html = PHP_EOL . '<pre class="error alert alert-danger">' . PHP_EOL . $e->__toString() . PHP_EOL . '</pre>' . PHP_EOL;
		$html = str_replace('Stack trace:'.PHP_EOL, '<h4 style="color:blue;">Stack trace:</h4>', $html);
		$html = str_replace(getcwd(), '', $html);
		$html = preg_replace('/exception \'(.+?)\' with message \'(.*?)\'/is', '<h4 style="color:red;">$1:</h4><b>\'$2\'</b>'.PHP_EOL, $html);
		$html = preg_replace('/\#(.+?)\((\d+)\)\: (.+)/', "#$1 <b style='color:red;'>$2</b>: <span style='color:blue;'>$3</span>", $html);

		return $html;
	}

}
