<?php
	$mealName = $_POST['mealType'];
	print '<h2>'.$mealName.'</h2>';
	print "<a href='result.php'>Make It</a>";
	$json_file = "recipes.json";
    $handle = fopen($json_file , "r");
    $contents = fread($handle,filesize($json_file));
    fclose($handle);        
	$meals = json_decode($contents,true);	
	print '<table>';	
	print '<tr><th>'.'item'.'</th><th>'.'amount'.'</th><th>'.'unit'.'</th></tr>';	
	$ingredients=[];		
	foreach ($meals as $meal)
	{
		if ($meal['name']==$mealName){
				
			for($item=0;$item<count($meal['ingredients']);$item++)
			{
			print '<tr><td>'.$meal['ingredients'][$item]['item'].'</td><td>'.$meal['ingredients'][$item]['amount'].'</td><td>'.$meal['ingredients'][$item]['unit'].'</td></tr>';
			$meal['ingredients'][$item]['useby']=date('Y-m-d', strtotime("1 days ago"));
			}
			$ingredients=$meal['ingredients'];
		}
	}
	print '</table>';	
//	echo $ingredients[0]['useby'];
	print '<h2>'.'Fridge Available'.'</h2>';
	$csvFile = file('fridge.csv');
    $data = [];
    foreach ($csvFile as $line) {
        $data[] = str_getcsv($line);
    }
	print "<table>\n";
	print '<tr><th>' . implode('</th><th>', $data[0]) . "</th></tr>\n";
	for($item=1;$item<count($data);$item++){		
		for ($ingredient=0;$ingredient<count($ingredients);$ingredient++){
			$UsebyDate = date($data[$item][3]);
			$UsebyDate = str_replace('/', '-', $UsebyDate);
			$UsebyDate = date('Y-m-d', strtotime($UsebyDate));
			if ($ingredients[$ingredient]['item']==$data[$item][0]&&
				$ingredients[$ingredient]['amount']<=$data[$item][1]&&
				strtotime($ingredients[$ingredient]['useby'])<strtotime($UsebyDate)
			)
			{
				$ingredients[$ingredient]['amount']= $data[$item][1];
				$ingredients[$ingredient]['useby'] = $UsebyDate;
			}
		}
	}
	foreach ($ingredients as $ingredient){
		if (strtotime($ingredient['useby'])>=strtotime(date('Y-m-d')))
		{
			print '<tr><td>'.$ingredient['item'].'</td><td>'.$ingredient['amount'].'</td><td>'.$ingredient['unit'].'</td><td>'.$ingredient['useby'].'</td></tr>';
		}
		else {
			print '<tr><td>'.$ingredient['item'].'</td><td>'.$ingredient['amount'].'</td><td>'.$ingredient['unit'].'</td><td>'.'TakeOut'.'</td></tr>';
		}
	}
	
	
?>