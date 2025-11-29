<?php
function display(){
     $show = "Welcome to my Tutorial";
     return $show;
}

function display2($name){
   return $show = "Welcome {$name}";
}

function display3($fname, $lname){
   return $show = "Welcome ".strtoupper($lname).", {$fname}";
}

//Simple Arithmetic function

function arithmetic($v1, $v2, $o){

    switch ($o){
        case 'a':
            echo "The value of {$v1} + {$v2} is ".($v1+$v2);
                break;

        case 's':
            echo "The value of {$v1} - {$v2} is ".($v1-$v2);
                break;               
         case 'm':
             echo "The value of {$v1} * {$v2} is ".($v1*$v2);
            break;                
        case 'd':
            echo "The value of {$v1} / {$v2} is ".($v1/$v2);
            break;
            default:
            echo "Invalid Operator";
    }
}

function nameFormatter($name){
    return ucwords(strtolower($name));
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }


  // Convert Programme ID
function convert_id($dbh, $category) {
	$sql = "select name from categories where id = :id";
	$query = $dbh->prepare($sql);
	$query->bindParam(':id', $category, PDO::PARAM_INT);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_ASSOC);
	return $result['name'];
}
?>