<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CSV Helpers
 * Inspiration from PHP Cookbook by David Sklar and Adam Trachtenberg
 * 
 * @author		Jérôme Jaglale
 * @link		http://maestric.com/en/doc/php/codeigniter_csv
 */

// ------------------------------------------------------------------------

/**
 * Array to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('array_to_csv'))
{
	function array_to_csv($array, $download = "")
	{
		if ($download != "")
		{	
			header('Content-Type: application/csv');
			header('Content-Disposition: attachement; filename="' . $download . '.csv"');
		}		

		ob_start();
		$f = fopen('php://output', 'w') or show_error("Can't open php://output");
		$n = 0;		
		foreach ($array as $line)
		{
			$n++;
			if ( ! fputcsv($f, $line))
			{
				show_error("Can't write line $n: $line");
			}
		}
		fclose($f) or show_error("Can't close php://output");
		$str = ob_get_contents();
		ob_end_clean();

		if ($download == "")
		{
			return $str;
		}	
		else 
		{
			echo $str;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Query to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('query_to_csv'))
{
	function query_to_csv($query, $headers = TRUE, $download = "")
	{
		if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
		{
			show_error('invalid query');
		}
		
		$array = array();
		
		if ($headers)
		{
			$line = array();
			foreach ($query->list_fields() as $name)
			{
				$line[] = $name;
			}
			$array[] = $line;
		}
		
		foreach ($query->result_array() as $row)
		{
			$line = array();
			foreach ($row as $item)
			{
				$line[] = $item;
			}
			$array[] = $line;
		}

		echo array_to_csv($array, $download);
	}
}

/* End of file csv_helper.php */
/* Location: ./system/helpers/csv_helper.php */


/**
* export mysql data or any kind of data in to xls format.
* @param $fields: Name of Headers
* @param $result_array: Data to be put into excel
* @param $filename: name of download file
* @author shankar kumar
* @return download xls file
*/

if ( ! function_exists('convert_into_excel'))
{
	function convert_into_excel($fields=array(),$result_array=array(),$filename='simple')
	{
		$headers = ''; // just creating the var for field headers to append to below
		$data = ''; // just creating the var for field data to append to below
		$i=0;
		foreach ($fields as $field)
		{
			$headers .= $field . "\t";
		}
		$k=1;
		if($result_array)
		{
			foreach ($result_array as $row)
			{
				$line = '';
				$m=1;
				foreach ($row as $value)
				{
					if ((!isset($value)) or ($value == ""))
					{
						$value = "\t";
					}
					else
					{
						if($m==1)
						{
							$value = '"' . $k . '"' . "\t";
						}
						else{
							$value = str_replace('"', '""', $value);
							$value = '"' . $value . '"' . "\t";
						}
					}
					$line .= $value;
					$m++;
				}
				$data .= trim($line) . "\n";
				$k++;
			}

		}
		$data = str_replace("\r", "", $data);

		//header("Content-type: application/x-msdownload");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo "$headers\n$data";
	}
}

if ( ! function_exists('convert_html_into_excel')){

	function convert_html_into_excel($html, $filename='simple'){

		header("Content-type: application/excel");
		header("Content-Disposition: attachment; filename=$filename.xls");
		header("Pragma: no-cache");
    	header("Expires: 0");
		echo $html;

	}

}