<?php
/*
 * wineTasting.php
 * created by <sushil.singh>
 * 
 */

// error setup
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// increasing the meomory limit
ini_set('memory_limit', '1024M');

/**
 * wine class
 * input filename 
 * output total wine sole and individual details
 */
class wine {
	public $fileName;
	function __construct($text) {
		$this->fileName = $text;
	}
	
	/**
     * function processFile
     * input the file which contain the person and wine data, 
     * output a TSV file which contain the desired result based on puzzle.
     */
	public function processFile() {
		// initialize the array
		$wish_list = [];
		$total_wine_list = [];
		$total_wine_sold = 0;
		$assigned_wines = [];
		
		// read the file data
		$file = fopen($this->fileName,"r");
		while (($line = fgets($file)) !== false) {
			// explode the wine and person data
			$name_and_wine = explode("\t", $line);
			$name = trim($name_and_wine[0]);
			$wine = trim($name_and_wine[1]);
			
			// check for unique wine wish list
			if(!array_key_exists($wine, $wish_list)) {
				$wish_list[$wine] = [];
			}
			$wish_list[$wine][] = $name;
			$total_wine_list[] = $wine;
		}
		
		// close the file handle
		fclose($file);
		
		// process the data and assign unique wines to person from wish list
		$total_wine_list = array_unique($total_wine_list);
		foreach ($total_wine_list as $key => $wine) {
			$maxSize = count($wine);
			$wine_counter = 0;
			while($wine_counter < 10) { // max wish list 10
				$i = intval(floatval(rand()/(float)getrandmax()) * $maxSize);
				$person = $wish_list[$wine][$i];
				if(!array_key_exists($person, $assigned_wines)) { // check for unique wines
					$assigned_wines[$person] = [];
				}
				if(count($assigned_wines[$person]) < 3 ) { // max wine allowed to be sold to one person
					$assigned_wines[$person][] = $wine;
					$total_wine_sold++;
					break;
				}
				$wine_counter++;
			}
		}
		
		// write the calculated data to output file
		$fh = fopen("wine_output.txt", "w");
		fwrite($fh, "Total number of wine bottles sold in aggregate : ".$total_wine_sold."\n"); // write the data in output file
		foreach (array_keys($assigned_wines) as $key => $person) {
			foreach ($assigned_wines[$person] as $key => $wine) {
				fwrite($fh, $person." ".$wine."\n");
			}
		}
		
		fclose($fh); // close the file handle
	}
}

// call the class 
echo "Start time : ".date('Y-m-d H:i:s')."<br />";
$puzzle = new wine("wine_input.txt");
$puzzle->processFile();
echo "End time : ".date('Y-m-d H:i:s')."\n";
?>
