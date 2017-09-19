<!DOCTYPE html>
<html>
	<head>MyMeal</head>
	<body>
		<form action="#" method="post">
		<!--read json to get recipes and list name as option-->
			<select name="mealType">
				<?php 
				$json_file = "recipes.json";
		        $handle = fopen($json_file , "r");
		        $contents = fread($handle,filesize($json_file));
		        fclose($handle);        
				$meals = json_decode($contents,true);					
				?>
				
				<?php foreach( $meals as $mealType ): ?>
					<option value="<?php echo $mealType["name"]?>"><?php echo $mealType["name"]?></option>
				<?php endforeach; ?>
			</select>
			<input type="submit" value="Done"/>
		</form>

		<?php
		
		  	$csvFile = file('fridge.csv');
		    $data = [];
		    foreach ($csvFile as $line) {
		        $data[] = str_getcsv($line);
		    }
			print "<table>\n";
			print '<tr><th>' . implode('</th><th>', $data[0]) . "</th></tr>\n";
			for($row = 1;$row<count($data);$row++)
			{
				$UsebyDate = date($data[$row][3]);
				$UsebyDate = str_replace('/', '-', $UsebyDate);
				$UsebyDate = date('Y-m-d', strtotime($UsebyDate));
				
				if (strtotime($UsebyDate)>strtotime(date("Y-m-d")))
				{
					print '<tr><td>'.implode('</td><td>', $data[$row]).'</td></tr>';
				}
			}			
			print '</table>';
//			php < 5.3				
//			$file = fopen("fridge.csv","rb");
//			$today = date("d/m/Y");
//			if (!$file){die("Can't open csvdata.csv: $php_errormsg");}
//			print "<table>\n";
//    
//			for ($line = fgetcsv($file, 1024); ! feof($file); $line = fgetcsv($file, 1024)) {
//							echo json_encode($line); 
//			    print '<tr><td>' . implode('</td><td>', $line) . "</td></tr>\n";
//			}
//			
//			print '</table>';
		?>

		<!--<?php var_dump($meals)?>-->
	</body>
</html>

