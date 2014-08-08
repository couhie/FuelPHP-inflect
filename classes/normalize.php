<?php
namespace Inflect;

class Normalize
{

	private static $symbol_pairs = array(
		'(' => '（',
		')' => '）',
	);

	public static function convert($data, $params = array())
	{
		if ( ! is_array($params)) return $data;
		foreach ($params as $key => $option)
		{
			if ( ! isset($data[$key])) continue;
			$data[$key] = mb_convert_kana($data[$key], $option);
			(strpos($option, 'i') !== false) and $data[$key] = str_replace(array_values(static::$symbol_pairs), array_keys(static::$symbol_pairs), $data[$key]);
			(strpos($option, 'I') !== false) and $data[$key] = str_replace(array_keys(static::$symbol_pairs), array_values(static::$symbol_pairs), $data[$key]);
		}
		return $data;
	}

	public static function format($data, $params = array())
	{
		if ( ! is_array($params)) return $data;
		foreach ($params as $key => $value)
		{
			if ( ! empty($data[$key])) continue;
			$data[$key] = $value;
		}
		return $data;
	}

	public static function trim($data, $params = array())
	{
		if ( ! is_array($params)) return $data;
		foreach ($params as $key => $value)
		{
			if ( ! isset($data[$key])) continue;
			$data[$key] = str_replace($value, '', $data[$key]);
		}
		return $data;
	}

	public static function implode($data, $params = array())
	{
		if ( ! is_array($params)) return $data;
		foreach ($params as $key => $args)
		{
			$data[$key] = '';
			foreach ($args as $arg)
			{
				isset($data[$arg]) and $data[$key] .= $data[$arg];
			}
		}
		return $data;
	}

	public static function explode($data, $params = array())
	{
		if ( ! is_array($params)) return $data;
		foreach ($params as $key => $args)
		{
			if ( ! isset($data[$key])) continue;
			$str = $data[$key];
			$len = array_shift($args);
			foreach ($args as $arg)
			{
				if (mb_strlen($str) >= $len)
				{
					$data[$arg] = mb_substr($str, 0, $len);
					$str = mb_substr($str, $len);
				}
				else
				{
					$data[$arg] = $str;
					$str = '';
				}
			}
		}
		return $data;
	}

	public static function tidy($data, $params = array())
	{
		if ( ! is_array($params)) return $data;
		foreach ($params as $method => $args)
		{
			if ( ! method_exists('Normalize', $method)) continue;
			$data = static::$method($data, $args);
		}
		return $data;
	}

}