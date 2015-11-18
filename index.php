<!DOCTYPE html>
<html lang="ru">
<head>
<title>Нормализация</title>
	<meta charset='UTF-8' />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-10">
				<form method="post" class="form-horizontal">
					<div class="form-group">
						<label for="inputext" class="col-sm-2 control-label">Нормализация</label>
						<div class="col-sm-10">
							<textarea class="form-control" name="norma" rows="6" id="inputext" placeholder="Введите текст ..."></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10">
							<input class="btn btn-default" type="submit" name="norm-user" value="Подсчитать" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
<?php
require_once 'lingua.php';
function clearString($string) {
	return trim(htmlspecialchars(stripcslashes($string)));
}

function Normalization() {
	if(isset($_POST['norm-user'])) {
		if(!empty($_POST['norma'])) {
			$text = clearString($_POST['norma']);
			$stemmer = new Lingua_Stem_Ru(); //это определение корня слова методом Стеммера Портера.
			$text = preg_replace('/[ ]([\S]{1,2}|[\S]{21,})[ ]/',' ', $text);
			$text = preg_replace('/[^\p{L}0-9 ]/iu',' ', $text);
			$text = preg_replace('#\s*?\r?\n\s*?(?=\r\n|\n)#s', "", $text);
			while(strpos($text, '  ') !== false)
			{
				$text = str_replace('  ', ' ', $text);
			}
			$ex = explode(' ', $text);
			$str_count = array();
			for($i = 0; $i < count($ex); $i++) {
				$str_count[$ex[$i]]['count'] = 0;
				for ($j = 0; $j < count($ex); $j++) {
					if ($ex[$i] == $ex[$j]) {
						$str_count[$ex[$i]]['count'] ++;
					}
				}
			}
			arsort($str_count);
			$str_count = array_slice($str_count, 0, 10);
			echo "<div class='row'><div class='col-sm-5'><table class='table table-striped'><caption><b>10 самых частых нормализованых слов:</b></caption>
                    <tr><td><b>Слово</b></td>
                    <td><b>Кол-во:</b></td></tr><tr>";
			foreach ($str_count as $key=>$value) {
				echo "<td>".$stemmer->stem_word($key)."</td><td>".$value['count']."</td></tr>";
			}
			echo "</table></div></div>";
		} else {
			echo 'Введите пожалуйста текст';
		}
	}
}
Normalization();

