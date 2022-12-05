<?php
/*
Mason Larcombe
16/11/2022
This code was written to output to the command line as a result I am echoing "\n" for linebreaks.
If outputting to a webpage replace all "\n" with "<br />".
I have written 2 different solutions based on whether using a php function to simplify the task is acceptable
I have also made different versions based on whether the palindrome should be case sensitive or not.
CS 	= Case Sensitive
!CS = NOT Case Sensitive
PF 	= Uses PHP Function
!PF = No PHP Function 
*/
function check_palindrome($input){
	if(is_string($input)){
		//CS PF
		if($input == strrev($input)){
			echo $input, " is a palindrome! (CS PF)";
		} else{
			echo $input, " is NOT a palindrome! (CS PF)";
		}
		echo "\n";

		//!CS PF
		if(strtoupper($input) == strtoupper(strrev($input))){
			echo $input, " is a palindrome! (!CS PF)";
		} else{
			echo $input, " is NOT a palindrome! (!CS PF)";
		}
		echo "\n";

		//CS !PF
		if($input == reverse_string($input)){
			echo $input, " is a palindrome! (CS !PF)";
		} else{
			echo $input, " is NOT a palindrome! (CS !PF)";
		}
		echo "\n";

		//!CS !PF
		if(strtoupper($input) == reverse_string(strtoupper($input))){
			echo $input, " is a palindrome!  (!CS !PF)";
		} else{
			echo $input, " is NOT a palindrome!  (!CS !PF)";
		}
		echo "\n";
	}else{
		echo "input is not a string!\n";
	}
}

function reverse_string($str){
	$output = "";
	for($i = strlen($str)-1; $i >= 0 ; $i--){
		$output .= $str[$i];
	}
	return $output;
}

check_palindrome("DAD");
check_palindrome("Dad");
check_palindrome("ABCDCBA");
check_palindrome("ABCDcba");
check_palindrome(1881);
check_palindrome(true);
check_palindrome([]);
check_palindrome(null);