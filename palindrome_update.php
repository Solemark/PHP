<?php
function check_palindrome(string $input): bool{
	if(is_string($input)){
		$c = strlen($input)-1;
		for($i = 0; $i <= $c; $i++){
			if($input[$i] != $input[$c]){
				return false;
			}
			$c--;
		}
		return true;
	}else{
		echo $input . " is not a string!";
		return false;
	}
}

echo check_palindrome("DAD");
echo check_palindrome("Dad");
echo check_palindrome("ABCDCBA");
echo check_palindrome("ABCDcba");
echo check_palindrome(1881);