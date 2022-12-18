<?php
function check_palindrome($input){
	if(is_string($input)){
		$c = strlen($input)-1;
		for($i = 0; $i <= $c; $i++){
			if($input[$i] != $input[$c]){
				echo $input, " is NOT a palindrome!\n";
				return;
			}
			$c--;
		}
		echo $input, " is a palindrome!";
		echo "\n";
	}else{
		echo "input is not a string!\n";
	}
}

check_palindrome("DAD");
check_palindrome("Dad");
check_palindrome("ABCDCBA");
check_palindrome("ABCDcba");
check_palindrome(1881);
check_palindrome(true);
check_palindrome([]);
check_palindrome(null);