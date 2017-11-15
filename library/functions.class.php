<?php

class Functions extends Database {

	function redirect($link, $time = 0) {
		echo "<meta http-equiv='refresh' content='$time;URL=$link' />";
		die();
	}

	function breadcrumbs($breadcrumbs, $csTitle, $csName) {
		$breadCount = count($breadcrumbs);
		$counter = 1;
		?>
		<ul class="pagePosition margin-vertical clear" vocab="http://schema.org/" typeof="BreadcrumbList">
			<li>
				<span>Vi ste ovde:</span>
			</li>
			<li property="itemListElement" typeof="ListItem">
				<a href="/" title="<?= $csTitle; ?>" property="item" typeof="WebPage">
					<span>Početna</span>
					<meta property="name" content="<?= $csName; ?>">
				</a>
				<meta property="position" content="1">
			</li>
			<?php
			foreach ($breadcrumbs as $name => $link) {
				$link = ltrim($link, "/");
				$counter++;
				if ($link != "") {
					//if ($breadCount == $counter) {
					?>				
					<li property="itemListElement" typeof="ListItem">
						<a href="<?= $link; ?>" property="item" typeof="WebPage">
							<span property="name" class="transition"><?= $name; ?></span>
						</a>
						<meta property="position" content="<?= $counter; ?>">
					</li>
					<?php
				}
			}
			?>
		</ul>
		<?php
	}

	function cropPictureAddWhiteSpace($image, $targ_w, $targ_h, $imageType, $destinationFolder, $newImageName) {

		$width = $this->getImageWidth($image);
		$height = $this->getImageHeight($image);

		$jpeg_quality = 90;

		switch ($imageType) {
			case 'gif':
				$simg = imagecreatefromgif($image);
				break;
			case 'jpg':
				$simg = imagecreatefromjpeg($image);
				break;
			case 'png':
				$simg = imagecreatefromjpeg($image);
				break;
			case 'GIF':
				$simg = imagecreatefromgif($image);
				break;
			case 'JPG':
				$simg = imagecreatefromjpeg($image);
				break;
			case 'PNG':
				$simg = imagecreatefromjpeg($image);
				break;
		}

		$resizeImage = new ResizeImage();

		if ($width == $targ_w && $height == $targ_h) {
			copy($image, $destinationFolder . $newImageName) or die("eror");
		} else {

			if ($targ_w == $targ_h) {

				if ($width > $height) {

					$ratio = $targ_w / $width;
					$new_height = $height * $ratio;

					$resizeImage->load($image);
					$resizeImage->resizeToWidth($targ_w);
					$resizeImage->save($destinationFolder . $newImageName);

					$difference = $width - $height;
					$half = $difference / 2;
					$half = round($half);

					$difference = $targ_w - $new_height;
					$half = $difference / 2;
					$half = round($half);

					$cropX = 0;
					$cropY = $half;

					$originalFile = $destinationFolder . $newImageName;
					$im = imagecreatefromjpeg($originalFile);

					$dst_r = ImageCreateTrueColor($targ_w, $targ_h);
					imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

					imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
					imagejpeg($dst_r, $originalFile, $jpeg_quality);
				} else if ($width < $height) {

					$ratio = $targ_h / $height;
					$new_width = $width * $ratio;

					$resizeImage->load($image);
					$resizeImage->resizeToHeight($targ_h);
					$resizeImage->save($destinationFolder . $newImageName);

					$difference = $targ_h - $new_width;
					$half = $difference / 2;
					$half = round($half);

					$cropX = $half;
					$cropY = 0;

					$originalFile = $destinationFolder . $newImageName;
					$im = imagecreatefromjpeg($originalFile);

					$dst_r = ImageCreateTrueColor($targ_w, $targ_h);
					imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

					imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
					imagejpeg($dst_r, $originalFile, $jpeg_quality);
				} else if ($width == $height) {

					$resizeImage->load($image);
					$resizeImage->resize($targ_w, $targ_h);
					$resizeImage->save($destinationFolder . $newImageName);
				}
			} else if ($targ_w > $targ_h) {

				if ($width > $height) {

					$scale = ($targ_w / $width) * 100;
					$new_height = $height * $scale / 100;
					$new_width = $width * $scale / 100;

					if ($new_height > $targ_h) {
						$scale = ($targ_h / $height) * 100;
						$new_height = $height * $scale / 100;
						$new_width = $width * $scale / 100;
					}

					$difference_x = $targ_w - $new_width;
					$half_x = $difference_x / 2;
					$half_x = round($half_x);

					$difference_y = $targ_h - $new_height;
					$half_y = $difference_y / 2;
					$half_y = round($half_y);

					$cropX = $half_x;
					$cropY = $half_y;

					$resizeImage->load($image);
					$resizeImage->scale($scale);
					//$resizeImage->resize($targ_w, $targ_h);
					$resizeImage->save($destinationFolder . $newImageName);

					$originalFile = $destinationFolder . $newImageName;
					$im = imagecreatefromjpeg($originalFile);

					$dst_r = ImageCreateTrueColor($targ_w, $targ_h);
					imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

					imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
					imagejpeg($dst_r, $originalFile, $jpeg_quality);
				} else if ($height > $width) {

					$scale = ($targ_h / $height) * 100;
					$new_height = $height * $scale / 100;
					$new_width = $width * $scale / 100;

					if ($new_width > $targ_w) {
						$scale = ($targ_w / $width) * 100;
						$new_height = $height * $scale / 100;
						$new_width = $width * $scale / 100;
					}

					$difference_x = $targ_w - $new_width;
					$half_x = $difference_x / 2;
					$half_x = round($half_x);

					$difference_y = $targ_h - $new_height;
					$half_y = $difference_y / 2;
					$half_y = round($half_y);

					$cropX = $half_x;
					$cropY = $half_y;

					$resizeImage->load($image);
					$resizeImage->scale($scale);
					//$resizeImage->resize($targ_w, $targ_h);
					$resizeImage->save($destinationFolder . $newImageName);

					$originalFile = $destinationFolder . $newImageName;
					$im = imagecreatefromjpeg($originalFile);

					$dst_r = ImageCreateTrueColor($targ_w, $targ_h);
					imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

					imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
					imagejpeg($dst_r, $originalFile, $jpeg_quality);
				} else if ($width == $height) {

					$scale = ($targ_h / $height) * 100;
					$new_height = $height * $scale / 100;
					$new_width = $width * $scale / 100;

					$difference_x = $targ_w - $new_width;
					$half_x = $difference_x / 2;
					$half_x = round($half_x);

					$difference_y = $targ_h - $new_height;
					$half_y = $difference_y / 2;
					$half_y = round($half_y);

					$cropX = $half_x;
					$cropY = $half_y;

					$resizeImage->load($image);
					$resizeImage->scale($scale);
					//$resizeImage->resize($targ_w, $targ_h);
					$resizeImage->save($destinationFolder . $newImageName);

					$originalFile = $destinationFolder . $newImageName;
					$im = imagecreatefromjpeg($originalFile);

					$dst_r = ImageCreateTrueColor($targ_w, $targ_h);
					imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, imagecolorallocate($dst_r, 255, 255, 255));

					imagecopy($dst_r, $im, $cropX, $cropY, 0, 0, imagesx($im), imagesy($im));
					imagejpeg($dst_r, $originalFile, $jpeg_quality);
				}
			} else if ($targ_w < $targ_h) {

				if ($width > $height) {

					$new_w = ($height * $targ_w) / $targ_h;
					$difference = $width - $new_w;
					$half = $difference / 2;
					$half = round($half);

					$cropWidth = $new_w;
					$cropHeight = $height;
					$cropX = 0;
					$cropY = 0;
				} else if ($width < $height) {

					$new_h = ($width * $targ_h) / $targ_w;
					$difference = $height - $new_h;
					$half = $difference / 2;
					$half = round($half);

					$cropWidth = $width;
					$cropHeight = $new_h;
					$cropX = 0;
					$cropY = 0;
				} else if ($width == $height) {

					$new_w = ($height * $targ_w) / $targ_h;
					$difference = $width - $new_w;
					$half = $difference / 2;
					$half = round($half);

					$cropWidth = $new_w;
					$cropHeight = $height;
					$cropX = 0;
					$cropY = 0;
				}
			}
		}

		$stamp = "../../uploaded_pictures/watermarks/" . $targ_w . "x" . $targ_h . ".png";
		if (is_file($stamp)) {
			$stamp = imagecreatefrompng($stamp);
			$originalFile = $destinationFolder . $newImageName;
			$im = imagecreatefromjpeg($originalFile);
			imagecopy($im, $stamp, 0, 0, 0, 0, imagesx($stamp), imagesy($stamp));
			imagejpeg($im, $originalFile, $jpeg_quality);
			imagedestroy($im);
		}

		return $newImageName;
	}

	function printGallery($dir, $alt, $break_on) {
		$full_path = substr($dir, 1);

		if (is_dir($full_path)) {
			$bigs = $full_path . "bigs/";
			$thumbs = $full_path . "thumbs/";
			$counter = 0;
			if ($handle = opendir($thumbs)) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						$counter++;
						$class = "";
						if ($counter % $break_on == 0) {
							$class = " right ";
						}
						?>
						<div class="fifth left">
							<a class="fancybox <?= $class; ?>" href="<?= "/" . $bigs . $entry; ?>" data-fancybox-group="gallery" title="<?= "Slika " . $alt; ?>">
								<img alt="Galerija <?= $alt; ?>" src="<?= "/" . $thumbs . $entry; ?>" />
							</a>
						</div>
						<?php
					}
				}
				echo "<br clear='all' />";
				closedir($handle);
			}
		}
	}

	function latinica($string) {

		$string = str_replace("е", "е", $string);
		$string = str_replace("Е", "E", $string);
		$string = str_replace("р", "r", $string);
		$string = str_replace("Р", "R", $string);
		$string = str_replace("т", "t", $string);
		$string = str_replace("Т", "T", $string);
		$string = str_replace("з", "z", $string);
		$string = str_replace("З", "Z", $string);
		$string = str_replace("у", "u", $string);
		$string = str_replace("У", "U", $string);
		$string = str_replace("и", "i", $string);
		$string = str_replace("И", "I", $string);
		$string = str_replace("о", "o", $string);
		$string = str_replace("О", "O", $string);
		$string = str_replace("п", "p", $string);
		$string = str_replace("П", "P", $string);
		$string = str_replace("а", "a", $string);
		$string = str_replace("А", "A", $string);
		$string = str_replace("с", "s", $string);
		$string = str_replace("С", "S", $string);
		$string = str_replace("д", "d", $string);
		$string = str_replace("Д", "D", $string);
		$string = str_replace("ф", "f", $string);
		$string = str_replace("Ф", "F", $string);
		$string = str_replace("г", "g", $string);
		$string = str_replace("Г", "G", $string);
		$string = str_replace("х", "h", $string);
		$string = str_replace("Х", "H", $string);
		$string = str_replace("ј", "j", $string);
		$string = str_replace("Ј", "J", $string);
		$string = str_replace("л", "l", $string);
		$string = str_replace("Л", "L", $string);
		$string = str_replace("ц", "c", $string);
		$string = str_replace("Ц", "C", $string);
		$string = str_replace("в", "v", $string);
		$string = str_replace("В", "V", $string);
		$string = str_replace("б", "b", $string);
		$string = str_replace("Б", "B", $string);
		$string = str_replace("н", "n", $string);
		$string = str_replace("Н", "N", $string);
		$string = str_replace("м", "m", $string);
		$string = str_replace("М", "M", $string);
		$string = str_replace("ђ", "đ", $string);
		$string = str_replace("ж", "ž", $string);
		$string = str_replace("ш", "š", $string);
		$string = str_replace("ћ", "ć", $string);
		$string = str_replace("ч", "č", $string);
		$string = str_replace("Ђ", "Đ", $string);
		$string = str_replace("Ж", "Ž", $string);
		$string = str_replace("Ш", "Š", $string);
		$string = str_replace("Ћ", "Ć", $string);
		$string = str_replace("Ч", "Č", $string);
		$string = str_replace("љ", 'lj', $string);
		$string = str_replace("Љ", 'Lj', $string);
		$string = str_replace("Ђ", 'Đ', $string);
		$string = str_replace("ђ", 'đ', $string);
		$string = str_replace("њ", 'nj', $string);
		$string = str_replace("Њ", 'Nj', $string);
		$string = str_replace("џ", 'dž', $string);
		$string = str_replace("Џ", 'Dž', $string);

		return $string;
	}

	function makeCountdownDate($date) {
		$dateA = explode(" ", $date);
		list($y, $m, $d) = explode("-", $dateA[0]);
		list($h, $i, $s) = explode(":", $dateA[1]);

		switch ($m) {
			case "01":
				$m = "January";
				break;
			case "02":
				$m = "February";
				break;
			case "03":
				$m = "March";
				break;
			case "04":
				$m = "April";
				break;
			case "05":
				$m = "May";
				break;
			case "06":
				$m = "Jun";
				break;
			case "07":
				$m = "July";
				break;
			case "08":
				$m = "August";
				break;
			case "09":
				$m = "September";
				break;
			case "10":
				$m = "October";
				break;
			case "11":
				$m = "November";
				break;
			case "12":
				$m = "December";
				break;
		}
		return $d . " " . $m . " " . $y . " " . $h . ":" . $i . ":" . $s;
	}

	function makeFancyDate($date) {
		$dateA = explode(" ", $date);
		list($y, $m, $d) = explode("-", $dateA[0]);
		list($h, $i, $s) = explode(":", $dateA[1]);

		switch ($m) {
			case "01":
				$m = "jan";
				break;
			case "02":
				$m = "feb";
				break;
			case "03":
				$m = "mar";
				break;
			case "04":
				$m = "apr";
				break;
			case "05":
				$m = "maj";
				break;
			case "06":
				$m = "jun";
				break;
			case "07":
				$m = "jul";
				break;
			case "08":
				$m = "avg";
				break;
			case "09":
				$m = "sep";
				break;
			case "10":
				$m = "okt";
				break;
			case "11":
				$m = "nov";
				break;
			case "12":
				$m = "dec";
				break;
		}
		return $d . "." . $m . "." . $y;
	}

	function makeAdvancedDate($date) {
		$dateA = explode(" ", $date);
		list($y, $m, $d) = explode("-", $dateA[0]);
		list($h, $i, $s) = explode(":", $dateA[1]);

		switch ($m) {
			case "01":
				$m = "jan";
				break;
			case "02":
				$m = "feb";
				break;
			case "03":
				$m = "mar";
				break;
			case "04":
				$m = "apr";
				break;
			case "05":
				$m = "maj";
				break;
			case "06":
				$m = "jun";
				break;
			case "07":
				$m = "jul";
				break;
			case "08":
				$m = "avg";
				break;
			case "09":
				$m = "sep";
				break;
			case "10":
				$m = "okt";
				break;
			case "11":
				$m = "nov";
				break;
			case "12":
				$m = "dec";
				break;
		}
		return $d . "." . $m . "." . $y . " u " . $h . ":" . $i;
	}

	function print_gallery($text) {

		$string = "";
		$url = $text;

		$album = new View("albums", $url, "title");
		$album_id = $album->id;

		$string = "<ul class=\"gallery\" id=\"album_$album_id\">\n";
		$picturesCollection = new Collection("pictures");
		$pictures = $picturesCollection->getCollection("WHERE album_id='$album_id'", "ORDER BY ordering");

		$cropDim = new View("dimensions", 1);
		foreach ($pictures as $picture) {

			$file = $picture->file_name;
			if ($file != "." && $file != ".." && $file != "") {
				$string .= "<li><a href=\"/uploads/uploaded_pictures/albums/resize/$file\" title=\"$picture->picture_name\" rel=\"prettyPhoto[gallery$album_id]\">";
				$string .= "<img style=\"width: " . $cropDim->width . "px; height: " . $cropDim->height . "px;\"src=\"/uploads/uploaded_pictures/albums/crop/$file\" />";
				$string .= "</a></li>\n";
			}
		}

		$string .= "</ul>\n<br clear=\"all\" />";


		return $string;
	}

	function printAlbums($string) {

		return preg_replace_callback("/\[ALBUM url=\"([0-9a-z]+)\"\]/", array($this, print_gallery), $string);
	}

	function stripString($string, $lenght) {

		$stringArr = explode(" ", $string);
		$value = "";
		for ($i = 0; $i < $lenght; $i++) {
			$value .= $stringArr[$i] . " ";
		}
		echo $value;
	}

	function stringCleaner($string) {

		$string = trim($string);
		$string = mysqli_real_escape_string($this->dbLink, $string);

		$string = str_replace("delete ", " ", $string);
		$string = str_replace("update ", " ", $string);
		$string = str_replace("drop ", " ", $string);
		$string = str_replace("insert ", " ", $string);
		$string = str_replace("select ", " ", $string);


		return $string;
	}

	/**
	 * Cisti string prilikom ispisivanja teksta strane itd...
	 */
	function printCleanString($string) {
		$string = str_replace("../../", "/", $string);
		$string = stripslashes($string);
		return $string;
	}

	function printData($string) {
		return stripslashes($string);
	}

	function calculateDate($date) {

		$timeNow = time();
		$time = strtotime($date);

		$difference = $timeNow - $time;
		if ($difference < 60) {
			$seconds = $difference;
			return "pre " . $seconds . " sekund" . $this->lastChar($seconds);
		} else if ($difference < 3600) {
			$minutes = round($difference / 60);
			return "pre " . $minutes . " minut" . $this->lastChar($minutes);
		} else if ($difference < 86400) {
			$hours = round($difference / 3600);
			return "pre " . $hours . " sat" . $this->lastChar($hours);
		} else if ($difference < 2592000) {
			$days = round($difference / 86400);
			return "pre " . $days . " dan" . $this->lastChar($days);
		} else if ($difference < 31104000) {
			$months = round($difference / 2592000);
			return "pre " . $months . " mesec" . $this->lastChar($months);
		} else {
			$years = round($difference / 31104000);
			return "pre " . $years . " godina" . $this->lastChar($years);
		}
	}

	function kShuffle(&$array) {
		if (!is_array($array) || empty($array)) {
			return false;
		}
		$tmp = array();
		foreach ($array as $key => $value) {
			$tmp[] = array('k' => $key, 'v' => $value);
		}
		shuffle($tmp);
		$array = array();
		foreach ($tmp as $entry) {
			$array[$entry['k']] = $entry['v'];
		}
		return true;
	}

	function tagCloud() {
		$tmp = array();
		$query = $this->execQuery("SELECT content_id, tag, count(`tag`) AS br FROM tags  GROUP BY lower(`tag`) ORDER BY br DESC LIMIT 25");
		$i = 1;
		while ($data = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			if (Database::getValue("status", "content", "id", $data["content_id"]) == 1) {
				if ($i >= 1 && $i < 3)
					$font = 18;
				elseif ($i >= 3 && $i < 8)
					$font = 16;
				elseif ($i >= 8 && $i < 12)
					$font = 14;
				elseif ($i >= 12 && $i < 19)
					$font = 12;
				elseif ($i >= 19)
					$font = 10;

				$tmp[$data['tag']] = $font;
				$i++;
			}
		}
		$this->kShuffle($tmp);

		foreach ($tmp as $key => $value) {
			echo "<a href=\"/sr/tag/" . $key . "\" style=\"font-size: " . $value . "px;\">" . $key . "</a> ";
		}
	}

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	function generateFormToken($form) {

		// generate a token from an unique value
		$token = md5(uniqid(microtime(), true));

		// Write the generated token to the session variable to check it against the hidden field when the form is sent
		$_SESSION[$form . '_token'] = $token;
		return $token;
	}

	function verifyFormToken($form) {

		// check if a session is started and a token is transmitted, if not return an error
		if (!isset($_SESSION[$form . '_token'])) {
			return false;
		}

		// check if the form is sent with token in it
		if (!isset($_POST['token'])) {
			return false;
		}

		// compare the tokens against each other if they are still the same
		if ($_SESSION[$form . '_token'] !== $_POST['token']) {
			return false;
		}

		return true;
	}

	function getValue($value, $content = '', $id = '', $data = '') {
		global $_POST, $_GET, $_SERVER;

		$REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];

		if ($REQUEST_METHOD == 'POST') {
			$takenValue = $_POST[$value];
		} else if ($REQUEST_METHOD == 'GET') {
			$takenValue = $_GET[$value];
		}

		if (!is_array($takenValue)) {
			$takenValue = $this->stringCleaner($takenValue);
		}

		return $takenValue;
	}

	function mailValidator($email) {
		$result = true;
		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
			$result = false;
		}
		return $result;
	}

	function passwordGeneration($length = 10, $possible = "0123456789abcdefghijklmnoprstuvwxyz") {
		$password = "";
		$i = 0;
		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
			if (!strstr($password, $char)) {
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}

	function cropPictureISS($image, $targ_w, $targ_h, $imageType, $image_destination, $image_name) {
		switch ($imageType) {
			case 'gif':
				$source = imagecreatefromgif($image);
				break;
			case 'jpg':
				$source = imagecreatefromjpeg($image);
				break;
			case 'png':
				$source = imagecreatefromjpeg($image);
				break;
			case 'GIF':
				$source = imagecreatefromgif($image);
				break;
			case 'JPG':
				$source = imagecreatefromjpeg($image);
				break;
			case 'PNG':
				$source = imagecreatefromjpeg($image);
				break;
		}
		list($width, $height) = getimagesize($image);

		$original_aspect = $width / $height;
		$thumb_aspect = $targ_w / $targ_h;

		if ($original_aspect >= $thumb_aspect) {
			// If image is wider than thumbnail (in aspect ratio sense)
			$new_height = $targ_h;
			$new_width = $width / ($height / $targ_h);
		} else {
			// If the thumbnail is wider than the image
			$new_width = $targ_w;
			$new_height = $height / ($width / $targ_w);
		}

		$thumb = imagecreatetruecolor($targ_w, $targ_h);

		// Resize and crop
		imagecopyresampled($thumb, $source, 0 - ($new_width - $targ_w) / 2, 0 - ($new_height - $targ_h) / 2, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($thumb, $image_destination . $image_name, 100);

		return $image_name;
	}

	function generatePassword($length = 20) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$count = mb_strlen($chars);

		for ($i = 0, $result = ''; $i < $length; $i++) {
			$index = rand(0, $count - 1);
			$result .= mb_substr($chars, $index, 1);
		}

		return $result;
	}

	function stampa($string) {

		$string = str_replace("е", "e", $string);
		$string = str_replace("Е", "E", $string);

		$string = str_replace("р", "r", $string);
		$string = str_replace("Р", "R", $string);

		$string = str_replace("т", "t", $string);
		$string = str_replace("Т", "T", $string);

		$string = str_replace("з", "z", $string);
		$string = str_replace("З", "Z", $string);

		$string = str_replace("у", "u", $string);
		$string = str_replace("У", "U", $string);

		$string = str_replace("и", "i", $string);
		$string = str_replace("И", "I", $string);

		$string = str_replace("о", "o", $string);
		$string = str_replace("О", "O", $string);

		$string = str_replace("п", "p", $string);
		$string = str_replace("П", "P", $string);

		$string = str_replace("а", "a", $string);
		$string = str_replace("А", "A", $string);

		$string = str_replace("с", "s", $string);
		$string = str_replace("С", "S", $string);

		$string = str_replace("д", "d", $string);
		$string = str_replace("Д", "D", $string);

		$string = str_replace("ф", "f", $string);
		$string = str_replace("Ф", "F", $string);

		$string = str_replace("г", "g", $string);
		$string = str_replace("Г", "G", $string);

		$string = str_replace("х", "h", $string);
		$string = str_replace("Х", "H", $string);

		$string = str_replace("ј", "j", $string);
		$string = str_replace("Ј", "J", $string);

		$string = str_replace("к", "k", $string);
		$string = str_replace("К", "K", $string);

		$string = str_replace("л", "l", $string);
		$string = str_replace("Л", "L", $string);

		$string = str_replace("ц", "c", $string);
		$string = str_replace("Ц", "C", $string);

		$string = str_replace("в", "v", $string);
		$string = str_replace("В", "V", $string);

		$string = str_replace("б", "b", $string);
		$string = str_replace("Б", "B", $string);

		$string = str_replace("н", "n", $string);
		$string = str_replace("Н", "N", $string);

		$string = str_replace("м", "m", $string);
		$string = str_replace("М", "M", $string);

		$string = str_replace("ђ", "đ", $string);
		$string = str_replace("ж", "ž", $string);
		$string = str_replace("ш", "š", $string);

		$string = str_replace("ћ", "ć", $string);
		$string = str_replace("ч", "č", $string);

		$string = str_replace("Ђ", "Đ", $string);
		$string = str_replace("Ж", "Ž", $string);
		$string = str_replace("Ш", "Š", $string);
		$string = str_replace("Ћ", "Ć", $string);
		$string = str_replace("Ч", "Č", $string);

		$string = str_replace("љ", 'lj', $string);
		$string = str_replace("Љ", 'Lj', $string);

		$string = str_replace("Ђ", 'Đ', $string);
		$string = str_replace("ђ", 'đ', $string);

		$string = str_replace("њ", 'nj', $string);
		$string = str_replace("Њ", 'Nj', $string);

		$string = str_replace("џ", 'dž', $string);
		$string = str_replace("Џ", 'Dž', $string);



		return $string;
	}

	function generateUrlFromText($strText) {

		$strText = $this->stampa($strText);

		$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
			"}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
			"â€”", "â€“", ",", "<", ".", ">", "/", "?");
		$strText = trim(str_replace($strip, "", strip_tags($strText)));

		// RUSKI
		$strText = str_replace("Ё", "E", $strText);
		$strText = str_replace("ё", "e", $strText);
		$strText = str_replace("Й", "I", $strText);
		$strText = str_replace("й", "i", $strText);
		$strText = str_replace("Ъ", "ie", $strText);
		$strText = str_replace("ъ", "Y", $strText);
		$strText = str_replace("Ы", "y", $strText);
		$strText = str_replace("ы", "u", $strText);
		$strText = str_replace("Э", "E", $strText);
		$strText = str_replace("э", "e", $strText);
		$strText = str_replace("Ю", "IU", $strText);
		$strText = str_replace("ю", "iu", $strText);
		$strText = str_replace("Я", "IA", $strText);
		$strText = str_replace("я", "ia", $strText);
		$strText = str_replace("ß", "s", $strText);

		// NEMACKI
		$strText = str_replace("Ä", "A", $strText);
		$strText = str_replace("ä", "a", $strText);
		$strText = str_replace("Ö", "O", $strText);
		$strText = str_replace("ö", "o", $strText);
		$strText = str_replace("Ü", "U", $strText);
		$strText = str_replace("ü", "u", $strText);

		// MADJARSKI
		$strText = str_replace("Ó", "O", $strText);
		$strText = str_replace("ó", "o", $strText);
		$strText = str_replace("Ő", "O", $strText);
		$strText = str_replace("ő", "o", $strText);
		$strText = str_replace("ÿ", "y", $strText);
		$strText = str_replace("Á", "A", $strText);
		$strText = str_replace("á", "a", $strText);
		$strText = str_replace("É", "E", $strText);
		$strText = str_replace("é", "e", $strText);
		$strText = str_replace("Í", "I", $strText);
		$strText = str_replace("í", "i", $strText);
		$strText = str_replace("ú", "u", $strText);
		$strText = str_replace("Ű", "U", $strText);
		$strText = str_replace("ű", "u", $strText);
		$strText = str_replace("ē", "e", $strText);
		$strText = str_replace("è", "e", $strText);
		$strText = str_replace("ȅ", "e", $strText);
		$strText = str_replace("Õ", "O", $strText);
		$strText = str_replace("õ", "o", $strText);
		$strText = str_replace("Û", "U", $strText);
		$strText = str_replace("û", "u", $strText);
		$strText = str_replace("ȅ", "e", $strText);
		$strText = str_replace("Ӳ", "Y", $strText);
		$strText = str_replace("ӳ", "y", $strText);

		$strText = str_replace("š", "s", $strText);
		$strText = str_replace("Š", "S", $strText);
		$strText = str_replace("ž", "z", $strText);
		$strText = str_replace("Ž", "z", $strText);
		$strText = str_replace("Č", "c", $strText);
		$strText = str_replace("č", "c", $strText);
		$strText = str_replace("Ć", "c", $strText);
		$strText = str_replace("ć", "c", $strText);
		$strText = str_replace("Đ", "Dj", $strText);
		$strText = str_replace("đ", "dj", $strText);
		$strText = preg_replace('/[^A-Za-z0-9-]/', ' ', $strText);
		$strText = preg_replace('/ +/', ' ', $strText);
		$strText = trim($strText);
		$strText = str_replace(' ', '-', $strText);
		$strText = preg_replace('/-+/', '-', $strText);
		$strText = str_replace("---", "-", $strText);
		$strText = strtolower($strText);
		return $strText;
	}

	function generateTags($strText) {
		$strText = preg_replace('/[\[\]?!\.,\/\\"\'\(\)]/', ' ', $strText);
		$strText = preg_replace('/[ ]+/', ' ', $strText);
		$array = explode(" ", $strText);

		$tmpArray = array();
		for ($i = 0; $i < count($array); $i++) {
			if (strlen($array[$i]) > 4) {
				array_push($tmpArray, $array[$i]);
			}
		}

		return $tmpArray;
	}

	function debug($input) {
		echo "<pre>";
		var_dump($input);
		echo "</pre>";
	}

	function recognizeURL($text) {
		$regEx = "((www\.|http\.|(www|http|https|ftp|news|file)+\:\/\/)([_.a-z0-9-]+\.[a-zA-Z0-9\/_:@=.+?,##%&~-]*[^.|\'|\# |!|\(|?|,| |>|< |;|\)]))";
		$text = preg_replace($regEx, "<a href='$0'>$0</a>", $text);

		return $text;
	}

	function recognizeEmoticons($text) {
		$regEx = "((\:\))|(\;\))|(\:P)|(\:D)|(\:\*)|(\:\()|";
		$regEx .= "(\:\-\))|(\;\-\))|(\:\-P)|(\:\-D)|(\:\-\*)|(\:\-\())";
		$text = preg_replace($regEx, "<img src=\"/images/emoticons/smiley_$0.jpg\" />", $text);
		$text = str_replace("/smiley_:).jpg", "/smiley_1.jpg", $text);
		$text = str_replace("/smiley_;).jpg", "/smiley_2.jpg", $text);
		$text = str_replace("/smiley_:P.jpg", "/smiley_3.jpg", $text);
		$text = str_replace("/smiley_:D.jpg", "/smiley_4.jpg", $text);
		$text = str_replace("/smiley_:*.jpg", "/smiley_5.jpg", $text);
		$text = str_replace("/smiley_:(.jpg", "/smiley_7.jpg", $text);
		$text = str_replace("/smiley_:-).jpg", "/smiley_1.jpg", $text);
		$text = str_replace("/smiley_;-).jpg", "/smiley_2.jpg", $text);
		$text = str_replace("/smiley_:-P.jpg", "/smiley_3.jpg", $text);
		$text = str_replace("/smiley_:-D.jpg", "/smiley_4.jpg", $text);
		$text = str_replace("/smiley_:-*.jpg", "/smiley_5.jpg", $text);
		$text = str_replace("/smiley_:-(.jpg", "/smiley_7.jpg", $text);
		return $text;
	}

	function fileUpload($field_name, $targetDir, $fileName, $exstensions) {

		if (isset($_FILES["$field_name"]) && $_FILES["$field_name"]['size'] > 0) {
			$tmp_name = $_FILES["$field_name"]["tmp_name"];
			$file_type = $_FILES["$field_name"]["type"];

			$getExt = explode('.', $_FILES["$field_name"]['name']);
			$file_ext = $getExt[count($getExt) - 1];

			$file_ext = strtolower($file_ext);

			$file_size = $_FILES["$field_name"]['size'];
			$niz = explode(",", $exstensions);

			if (!in_array($file_ext, $niz))
				die("Error: Only these picture extensions are allowed <strong>" . $exstensions . "</strong>");


			$name = $fileName . "." . $file_ext;
			$n = $targetDir . $name;

			move_uploaded_file($tmp_name, $n);

			return $name;
		}
	}

	function newCropImage($image, $targ_w, $targ_h, $imageType, $destinationFolder, $newImageName) {

		$width = $this->getImageWidth($image);
		$height = $this->getImageHeight($image);

		$jpeg_quality = 90;

		switch ($imageType) {
			case 'gif':
				$simg = imagecreatefromgif($image);
				break;
			case 'jpg':
				$simg = imagecreatefromjpeg($image);
				break;
			case 'png':
				$simg = imagecreatefromjpeg($image);
				break;
			case 'GIF':
				$simg = imagecreatefromgif($image);
				break;
			case 'JPG':
				$simg = imagecreatefromjpeg($image);
				break;
			case 'PNG':
				$simg = imagecreatefromjpeg($image);
				break;
		}

		if ($width == $targ_w && $height == $targ_h) {
			copy($image, $destinationFolder . $newImageName) or die("eror");
		} else {

			if ($targ_w == $targ_h) {

				if ($width > $height) {

					$difference = $width - $height;
					$half = $difference / 2;
					$half = round($half);

					$cropX = 0;
					$cropY = 0;
					$cropWidth = $height;
					$cropHeight = $height;
				} else if ($width < $height) {

					$difference = $height - $width;
					$half = $difference / 2;
					$half = round($half);

					$cropX = 0;
					$cropY = 0;
					$cropWidth = $width;
					$cropHeight = $width;
				} else if ($width == $height) {

					$cropWidth = $width;
					$cropHeight = $height;
					$cropX = 0;
					$cropY = 0;
				}
			} else if ($targ_w > $targ_h) {

				if ($width > $height) {

					$new_h = ($width * $targ_h) / $targ_w;
					$difference = $height - $new_h;
					$half = $difference / 2;
					$half = round($half);

					$cropWidth = $width;
					$cropHeight = $new_h;
					$cropX = 0;
					$cropY = 0;
				} else if ($height > $width) {

					$new_h = ($width * $targ_h) / $targ_w;
					$difference = $height - $new_h;
					$half = $difference / 2;
					$half = round($half);

					$cropWidth = $width;
					$cropHeight = $new_h;
					$cropX = 0;
					$cropY = 0;
				} else if ($width == $height) {

					$new_h = ($width * $targ_h) / $targ_w;
					$difference = $height - $new_h;
					$half = $difference / 2;
					$half = round($half);

					$cropWidth = $width;
					$cropHeight = $new_h;
					$cropX = 0;
					$cropY = 0;
				}
			} else if ($targ_w < $targ_h) {

				if ($width > $height) {

					$new_w = ($height * $targ_w) / $targ_h;
					$difference = $width - $new_w;
					$half = $difference / 2;
					$half = round($half);

					$cropWidth = $new_w;
					$cropHeight = $height;
					$cropX = 0;
					$cropY = 0;
				} else if ($width < $height) {

					$new_h = ($width * $targ_h) / $targ_w;
					$difference = $height - $new_h;
					$half = $difference / 2;
					$half = round($half);

					$cropWidth = $width;
					$cropHeight = $new_h;
					$cropX = 0;
					$cropY = 0;
				} else if ($width == $height) {

					$new_w = ($height * $targ_w) / $targ_h;
					$difference = $width - $new_w;
					$half = $difference / 2;
					$half = round($half);

					$cropWidth = $new_w;
					$cropHeight = $height;
					$cropX = 0;
					$cropY = 0;
				}
			}

			$dst_r = ImageCreateTrueColor($targ_w, $targ_h);

			imagecopyresampled($dst_r, $simg, 0, 0, $cropX, $cropY, $targ_w, $targ_h, $cropWidth, $cropHeight);

			imagejpeg($dst_r, $destinationFolder . $newImageName, $jpeg_quality);
		}

		$stamp = "uploaded_pictures/watermarks/" . $targ_w . "x" . $targ_h . ".png";
		if (is_file($stamp)) {
			$stamp = imagecreatefrompng($stamp);
			$originalFile = $destinationFolder . $newImageName;
			$im = imagecreatefromjpeg($originalFile);
			imagecopy($im, $stamp, 0, 0, 0, 0, imagesx($stamp), imagesy($stamp));
			imagejpeg($im, $originalFile, $jpeg_quality);
			imagedestroy($im);
		}

		return $newImageName;
	}

	function sizeOfFile($file_size) {
		if ($file_size >= 1048576) {
			$show_filesize = number_format(($file_size / 1048576), 2) . " MB";
		} elseif ($file_size >= 1024) {
			$show_filesize = number_format(($file_size / 1024), 2) . " KB";
		} elseif ($file_size >= 0) {
			$show_filesize = $file_size . " b";
		} else {
			$show_filesize = "0 b";
		}
		return $show_filesize;
	}

	function createPagination($url_link, $page, $resultCount, $limit) {

		$totalPages = ceil($resultCount / $limit);
		if ($totalPages > 1) {

			$firstPage = ($page == 1) ? 0 : 1;
			$lastPage = ($page == $totalPages) ? 0 : $totalPages;
			$prevPage = ($page - 1 <= 0) ? 0 : $page - 1;
			$nextPage = ($page + 1 <= $totalPages) ? $page + 1 : 0;

			if ($page < $totalPages) {
				$i = 1;
				$countTill = $totalPages;
			} else {
				$i = $page - $totalPages + 1;
				$countTill = $totalPages;
			}

			// printing previous page link

			if ($prevPage != 0) {

				echo "<a class='leftArrow' href=\"" . $url_link . "/strana/" . $prevPage . "\">◄</a>";
			} else {
				?>

				<?php
			}
			// printing middle pages

			for ($i; $i <= $countTill; $i++) {

				if ($page == $i) {
					echo "<a href=\"#\" id='current' class='number'>" . $i . "</a> ";
				} else {
					echo "<a class='number' href=\"" . $url_link . "/strana/" . $i . "\">" . $i . "</a> ";
				}
			}
			// printing next page link

			if ($nextPage != 0) {
				echo "<a class='rightArrow' href=\"" . $url_link . "/strana/" . $nextPage . "\">►</a>";
			} else {
				?>

				<?php
			}
		}
	}

	function createAdminPagination($url_link, $page, $resultCount, $limit) {

		echo "<div id=\"pagination\" align=\"left\">";

		$totalPages = ceil($resultCount / $limit);


		$firstPage = ($page == 1) ? 0 : 1;
		$lastPage = ($page == $totalPages) ? 0 : $totalPages;
		$prevPage = ($page - 1 <= 0) ? 0 : $page - 1;
		$nextPage = ($page + 1 <= $totalPages) ? $page + 1 : 0;

		if ($page < 6) {
			$i = 1;
			$countTill = ($totalPages > 10) ? 10 : $totalPages;
		} else {
			$i = $page - 5;
			$countTill = ($page + 5 <= $totalPages) ? $page + 5 : $totalPages;
		}

		// printing first page link
		echo "<div>";
		if ($firstPage != 0) {
			echo "<a href=\"" . $url_link . "page=" . $firstPage . "\">First</a> ";
		} else {
			echo "First ";
		}
		echo "</div>";

		// printing previous page link
		echo "<div>";
		if ($prevPage != 0) {
			echo "<a href=\"" . $url_link . "page=" . $prevPage . "\">Previous</a> ";
		} else {
			echo "Previous ";
		}
		echo "</div>";

		// printing middle pages
		echo "<div>";
		for ($i; $i <= $countTill; $i++) {
			if ($page == $i)
				$additionalClass = " active";
			else
				$additionalClass = "";
			echo "<a href=\"" . $url_link . "page=" . $i . "\" class=\"number $additionalClass\">" . $i . "</a> ";
		}
		echo "</div>";

		// printing next page link
		echo "<div>";
		if ($nextPage != 0) {
			echo "<a href=\"" . $url_link . "page=" . $nextPage . "\">Next</a> ";
		} else {
			echo "Next ";
		}
		echo "</div>";

		// printing last page link
		echo "<div>";
		if ($lastPage != 0) {
			echo "<a href=\"" . $url_link . "page=" . $lastPage . "\">Last</a> ";
		} else {
			echo "Last ";
		}
		echo "</div>";

		echo "</div>";
	}

	/*
	 * $emailFrom         -   email from
	 * $fromName          -   name from
	 * $emailTo           -   email to
	 * $nameTo            -   name to
	 * $subject           -   mail subject
	 * $body              -   mail body
	 * $currentLanguage   -   current language id
	 */

	function sendMail($emailFrom, $fromName, $emailTo, $nameTo, $subject, $body, $currentLanguage) {

		require_once("library/phpmailer/PHPMailerAutoload.php");
		$settings = new View("settings", $currentLanguage, "lang_id");

		$body = nl2br($body);
		$body = str_replace(array("\
            ", "\\r", "\\n", "bcc:"), "<br/>", $body);

		if (is_file("images/logo.png")) {
			$logo = $settings->site_host . "images/logo.png";
		} elseif (is_file("images/logo.svg")) {
			$logo = $settings->site_host . "images/logo.svg";
		} elseif (is_file("images/logo.jpg")) {
			$logo = $settings->site_host . "images/logo.jpg";
		}

		$socials = "";
		if ($settings->site_facebook != "") {
			$socials .= '<a style="color: #000;font-family:Arial, sans-serif;font-size: 14px;line-height: 21px;text-transform: lowercase;" href="' . $settings->site_facebook . '" title="Facebook stranica ' . $settings->site_host . '" target="_blank"><img style="width: 30px;margin: 0 5px;" src="' . $settings->site_host . 'images/socials/facebook.png" alt="Facebook stranica ' . $settings->site_firm . '"></a>';
		}
		if ($settings->site_twitter != "") {
			$socials .= '<a style="color:#000;font-family: Arial, sans-serif;font-size:14px;line-height:21px;text-transform:lowercase;" href="' . $settings->site_twitter . '" title="Twitter stranica ' . $settings->site_host . '" target="_blank"><img style="width: 30px;margin: 0 5px;" src="' . $settings->site_host . 'images/socials/twitter.png" alt="Twitter stranica ' . $settings->site_firm . '"></a>';
		}
		if ($settings->site_google_plus != "") {
			$socials .= '<a style="color:#000;font-family: Arial, sans-serif;font-size:14px;line-height:21px;text-transform:lowercase;" href="' . $settings->site_google_plus . '" title="Google Plus stranica ' . $settings->site_host . '" target="_blank"><img style="width: 30px;margin: 0 5px;" src="' . $settings->site_host . 'images/socials/google-plus.png" alt="Google Plus stranica ' . $settings->site_firm . '"></a>';
		}
		if ($settings->site_instagram != "") {
			$socials .= '<a style="color:#000;font-family: Arial, sans-serif;font-size:14px;line-height:21px;text-transform:lowercase;" href="' . $settings->site_instagram . '" title="Instagram stranica ' . $settings->site_host . '" target="_blank"><img style="width: 30px;margin: 0 5px;" src="' . $settings->site_host . 'images/socials/instagram.png" alt="Instagram stranica ' . $settings->site_firm . '"></a>';
		}
		if ($settings->site_pinterest != "") {
			$socials .= '<a style="color:#000;font-family: Arial, sans-serif;font-size:14px;line-height:21px;text-transform:lowercase;" href="' . $settings->site_pinterest . '" title="Pinterest stranica ' . $settings->site_host . '" target="_blank"><img style="width: 30px;margin: 0 5px;" src="' . $settings->site_host . 'images/socials/pinterest.png" alt="Pinterest stranica ' . $settings->site_firm . '"></a>';
		}
		if ($settings->site_youtube != "") {
			$socials .= '<a style="color:#000;font-family: Arial, sans-serif;font-size:14px;line-height:21px;text-transform:lowercase;" href="' . $settings->site_youtube . '" title="You Tube stranica ' . $settings->site_host . '" target="_blank"><img style="width: 30px;margin: 0 5px;" src="' . $settings->site_host . 'images/socials/youtube.png" alt="You Tube stranica ' . $settings->site_firm . '"></a>';
		}
		if ($settings->site_vimeo != "") {
			$socials .= '<a style="color:#000;font-family: Arial, sans-serif;font-size:14px;line-height:21px;text-transform:lowercase;" href="' . $settings->site_vimeo . '" title="Vimeo stranica ' . $settings->site_host . '" target="_blank"><img style="width: 30px;margin: 0 5px;" src="' . $settings->site_host . 'images/socials/vimeo.png" alt="Vimeo stranica ' . $settings->site_firm . '"></a>';
		}

		
		$footer = $settings->site_firm.", ".$settings->site_address.", ".$settings->site_zip." ".$settings->site_city."<br /><a style='color: #000;font-family:Arial, sans-serif;font-size: 14px;line-height: 21px;text-transform: lowercase;' href='tel:$settings->site_phone'>".$settings->site_phone."</a>";
		if($settings->site_phone_2){
			$footer .= ", <a style='color: #000;font-family:Arial, sans-serif;font-size: 14px;line-height: 21px;text-transform: lowercase;' href='tel:$settings->site_phone_2'>".$settings->site_phone_2."</a>";
		}
		$footer .= "<br /><a style='color: #000;font-family:Arial, sans-serif;font-size: 14px;line-height: 21px;text-transform: lowercase;' href='mailto:$settings->site_email'>".$settings->site_email."</a>";
		
		$bodyMail = file_get_contents("includes/mail.html");
		$bodyMail = str_replace(array("[CONTENT]", "[LOGO]", "[SITE_TITLE]", "[FOOTER]", "[DOMAIN]", "[SUBJECT]", "[SOCIALS]"), array("<p>".$body."</p>", $logo, $settings->site_title, $footer, $settings->site_host, $subject, $socials), $bodyMail);

		
		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->CharSet = 'UTF-8';
		$mail->Host = $settings->site_outgoing_server;
		$mail->Port = $settings->site_smtp_port;
		$mail->SMTPAuth = true;
		$mail->Username = $settings->site_username;
		$mail->Password = $settings->site_password;
		$mail->Mailer = "smtp";
		$mail->SMTPSecure = 'ssl';

		$mail->From = $emailFrom;
		$mail->FromName = $fromName;
		$mail->AddAddress($emailTo, $nameTo);
		$mail->AddReplyTo($emailFrom, $fromName);

		$mail->isHTML(true);
		$mail->WordWrap = 50;
		$mail->Subject = $subject;
		$mail->Body = $bodyMail;
		$mail->AltBody = strip_tags($body);

		if (!$mail->Send()) {
			echo 'Message was not sent. Mailer error: ' . $mail->ErrorInfo . '<br />';
		}
	}

	function formatBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		$bytes /= pow(1024, $pow);

		return round($bytes, $precision) . ' ' . $units[$pow];
	}

	function printSelectOption($table, $valueField, $nameField, $lang_id) {
		$array[$table] = array();
		$query = Database::execQuery("SELECT $valueField, $nameField FROM $table WHERE lang_id='$lang_id' ORDER BY $nameField");
		while ($data = mysqli_fetch_array($query)) {
			$help = array($data[$valueField] => "&nbsp;&nbsp;&nbsp;&nbsp;$data[$nameField]");
			array_push($array[$table], $help);
		}

		return $array;
	}

	function printlanguageOptions($selected) {
		$query = Database::execQuery("SELECT * FROM languages ORDER BY ordering");
		while ($data = mysqli_fetch_array($query)) {
			?>
			<option value="<?= $data['id']; ?>" <?php if ($data['id'] == $selected) echo 'selected="selected"'; ?>><?= $data['title']; ?></option>
			<?php
		}
	}

	function makeNormalDate($date) {
		list($y, $m, $d) = explode("-", $date);
		return $d . "." . $m . "." . $y . ".";
	}

	function getImageHeight($image) {
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}

	function getImageWidth($image) {
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}

	function numPicturesAlbum($album_id) {
		$url = Database::getValue("url", "albums", "id", $album_id);
		$dir = "uploaded_pictures/albums/crop/$url/";
		$dh = opendir($dir);

		$slike = array();

		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				array_push($slike, $dir . $file);
			}
		}
		return count($slike);
	}

	function categoryLevel($category) {
		if ($category->parent_id == 0)
			return 0;
		$categoryCollection = new Collection("categories");
		$parentCategory = $categoryCollection->getCollection("WHERE resource_id = $category->parent_id AND lang = $category->lang");
		return 1 + $this->categoryLevel($parentCategory[0]);
	}

}

// end of class
?>