<?php
//To check Input value is exist or not
function required( $propertyName, $propertyValue)
{
    if (empty( $propertyValue )){
		die(json_encode( array("MissingRequiredQueryParameter"=>" $propertyName")));
	}
    return($propertyValue);
}

$result 			= array();
$end  				= 100;
$primeNumberCount 	= required('primeNumberCount',$argv[1]);
$primeArray	   	  	= range(2,$end);

//Increase MemorySize and Execution Time
if($primeNumberCount >1500){
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 3000);
}
generatePrimeNumbers($primeArray);

/*
 * This function will generate all prime numbers in primary array.
 * will send generated primary list to primeCountCheck function
 */
function generatePrimeNumbers($primeArray){	
	for ($index=2;$index <= max($primeArray);$index ++){
	    
		$noOfTimeDividedBy    = 0;
		$multiIndex           = 1;		
		$PrimeCheck           = TRUE;		
		$multiplication       = array();
		$filteredArray        = array();
		
		for($innerIndex = 1 ;$innerIndex*$index <= max($primeArray);$innerIndex ++){
			//Check Current Number is prime or not
			if($PrimeCheck){
				if($innerIndex <= $index && ($index%$innerIndex == 0))
					$noOfTimeDividedBy++;

				if($noOfTimeDividedBy > 2)
					$PrimeCheck = FALSE;
			}
			//Store multiplication value of Current Index (M) with innerIndex Series (N)   	
			$multiplication [$multiIndex] = $innerIndex*$index; // MN = M*N
			$multiIndex ++;
		}
		if($noOfTimeDividedBy <= 2) // Unset Current Number from Multiplication Array
			unset($multiplication[1]);
		
		$filteredArray = array_diff($primeArray,$multiplication); //Remove all Current Index- multiplication value (MN) from Prime Array
		$primeArray = count($filteredArray) > 0 ?$filteredArray:$primeArray;
		$primeArray = array_values($primeArray);
		unset($multiplication,$filteredArray);
    }
	primeCountCheck ($primeArray);
}

/*
 * Check generated prime count is equal to User's Input or not
 * Call generatePrimeNumbers function with new start and end range to extend primeCount
 */

function primeCountCheck ($primeArray){
	global $result;
	global $primeNumberCount,$end;
		
	$result = array_merge($result,$primeArray);
	
	if(count($result) < $primeNumberCount){
		$primeArray = array();
		$primeArray = range($end+1,$end+100);
		generatePrimeNumbers($primeArray);
	}else
		displayOutput($result);
}

/*
 * Display Output
 */
	
function displayOutput($primeArray){
	global $primeNumberCount;
	$lineContent = '';

	$tableContent = array();               

	for($primeIndex=0,$coulmnIndex =1; $primeIndex < $primeNumberCount;$primeIndex ++,$coulmnIndex++){                                                    
		$tableContent[0][$coulmnIndex] = $tableContent[$coulmnIndex][0] = $primeArray[$primeIndex];
		$lineContent .= str_pad("___", 3);
	}
	$index = 0;
	for($row=1;$row<=$primeNumberCount;$row++){
		for($col=1;$col<count($tableContent);$col++){
			$tableContent[$row][$col] = $tableContent[0][$row] * $tableContent[$col][0];                                
		}
	}
	$content =  "\n".str_pad("*|", 2);
	for($row=0;$row<=$primeNumberCount;$row++){
		for($col=0;$col<=$primeNumberCount;$col++){			
			if($row == 0 && $col >= 1)
				$content .= str_pad($tableContent[$row][$col], 8);
			else if($col == 0 && $row >= 1)
				$content .= str_pad($tableContent[$row][$col]."|", 8);
			else
				$content .= str_pad($tableContent[$row][$col], 8);
	}
	if($row==0)
	    $content .= "\n".$lineContent.$lineContent." \n";
	else
	    $content .= "\n";              
	}
	echo $content;	
}
?>