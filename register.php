<?php
//error_reporting( E_ALL | E_STRICT );
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
ini_set('display_errors','On');

//include required files
require_once './config.inc.php';
require_once './database.php';
require_once './utility.php';
require_once './xml/validation.php';

//header
require_once './header.inc.php';

//intialize the logger
$log = new Logger('./logs/webserver.log');

//initialize the database
$myDb=new DataBase($config->database);

//save the uploaded image as png
function saveImgAsPngFromUpload($target_file){
		//config
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$errors=array();
		$allowedmaxfilesize=500000;
		$allowedformats=array('JPG','JPEG','PNG','GIF','PNG');

		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["img"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				$errors[] = "The uploaded file is not an image.";
				$uploadOk = 0;
			}
		}
		// Check if file already exists
		if (file_exists($target_file)) {
			$errors[]="Uploaded image already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["img"]["size"] > $allowedmaxfilesize) {
			$errors[]="Uploaded image is too big (max accepted: ".humanFileSize($allowedmaxfilesize).')';
			$uploadOk = 0;
		}
		// Allow certain file formats
		//if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		//&& $imageFileType != "gif" ) {
//print_r(strtoupper($imageFileType));
//print_r($allowedformats);

		if(!in_array(strtoupper($imageFileType), $allowedformats)){
			$errors[]="Uploaded image is not in an allowed format ( ".implode(', ',$allowedformats).").";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$errors[]="Sorry, your image file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			//if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
			if (imagepng(imagecreatefromstring(file_get_contents($_FILES["img"]["tmp_name"])), $target_file)){
				//echo "The file ". basename( $_FILES["img"]["name"]). " has been uploaded.";
			} else {
				$errors[]="Sorry, there was an error uploading your file.";
			}
		}
	return $errors;
}

function humanFileSize($size,$unit="") {
  if( (!$unit && $size >= 1<<30) || $unit == "GB")
    return number_format($size/(1<<30),2)."GB";
  if( (!$unit && $size >= 1<<20) || $unit == "MB")
    return number_format($size/(1<<20),2)."MB";
  if( (!$unit && $size >= 1<<10) || $unit == "KB")
    return number_format($size/(1<<10),2)."KB";
  return number_format($size)." bytes";
}

	

if (array_key_exists('register', $_GET)){

	//check if this username is already in use
	$params =  new stdClass;
	$params->username =$_POST['username'];
	$table='users';
	$tempuser= $myDb->select($params, $table);
	
	$errors=array();
	if($tempuser){
		$errors[]='USERNAME ALREADY IN USE!';

	}
	//check if password is equal to password check
	if($_POST['password']!=$_POST['passwordcheck']){
		$errors[]= 'PASSWORD AND PASSWORD CHECK DO NOT MATCH!';
	}
	
	if(!count($errors)){
		$user = new User('');
		unset($_POST['passwordcheck']);
		unset($user->id);
		$user->import($_POST);
		$result=$myDb->insert($user, 'users');
		$user->id=$myDb->lastInsertId;
		
		if($result){
			if($_FILES["img"]["name"]!=''){
				$imgdest='img/users/'.$user->id.'.png';
				$imgerrors=saveImgAsPngFromUpload($imgdest);
				if(count($imgerrors)<1){
					//upload successfull, use uploaded image
					$user->img= './'.$imgdest;
				}else{
					$imgerrors[]='We will use a default image for your profile until you upload a valid one.';
					foreach($imgerrors as $error){
						echo "\n<div class='warning'>".$error."</div>";
					}
					//errors uploading img file, use default one
					$user->img= './img/user.png';
				}
			}else{
				//no img file provided use default one
				$user->img= './img/user.png';
			}

			$params =  new stdClass;
			$params->id =$user->id;
			
			$updates = new stdClass;
			$updates->img = $user->img;
			 
			$result=$myDb->update($updates, 'users', $params);
			echo "\n<div class='success'>Hi, <b>".$user->username."</b>:";
			echo "<br>You have sucessfully registered,";
			echo "<br>wellcome to the Speed Dreams Comunity.";
			//echo "<br>Now visit your ".$user->getLink('profile');
			//echo "<br>Or go to the <a href='./' alt='Home page'>home page</a>home page";
			echo "<br>Remember to insert your username and password into the \"Speed-Dreams Player configuration menu\" to allow us to track your races and lap times.";
			echo "</div>";

			exit;
		}else{
			echo "\n<div class='error'>Something gone wrong with your registration process.</div>";
		}
	}else{
		foreach($errors as $error){
			echo "\n<div class='error'>".$error."</div>";
		}
	}
}
?>

<?php
function dprint ($prop){
	
	if (array_key_exists($prop, $_POST)) {
		echo $_POST[$prop];
	}else{
		echo '';
	}
}
?>
<form action="./register.php?register" method="post" enctype="multipart/form-data">
	<table>
		<tr>
			<td>
				<label>Racer Name:</label>
				<input name="username" placeholder="choose a nickname" value="<?php dprint('username');?>" autofocus required>
				<br>
				<label>Email:</label>
				<input name="email" type="email" placeholder="you@something.net" value="<?php dprint('email');?>" required>
				<br>
				<label>Password:</label>
				<input name="password" type="password" placeholder="password" id="password" required>
				<br>
				<label>Password check:</label>
				<input name="passwordcheck" type="password" placeholder="repeat the password" pattern="test" id="passwordcheck" required>
				<br>
				<label>Nation:</label>
				<img id="flagimg" src="./img/flags/flags_medium/Afghanistan.png" alt="Flag" />
				<select name="nation" id="flaginput" value="<?php dprint('nation');?>" required>
					<option>Afghanistan</option>
					<option>Albania</option>
					<option>Algeria</option>
					<option>American Samoa</option>
					<option>Andorra</option>
					<option>Angola</option>
					<option>Anguilla</option>
					<option>Antigua and Barbuda</option>
					<option>Argentina</option>
					<option>Armenia</option>
					<option>Aruba</option>
					<option>Australia</option>
					<option>Austria</option>
					<option>Azerbaijan</option>
					<option>Bahamas</option>
					<option>Bahrain</option>
					<option>Bangladesh</option>
					<option>Barbados</option>
					<option>Belarus</option>
					<option>Belgium</option>
					<option>Belize</option>
					<option>Benin</option>
					<option>Bermuda</option>
					<option>Bhutan</option>
					<option>Bolivia</option>
					<option>Bosnia</option>
					<option>Botswana</option>
					<option>Brazil</option>
					<option>British Virgin Islands</option>
					<option>Brunei</option>
					<option>Bulgaria</option>
					<option>Burkina Faso</option>
					<option>Burundi</option>
					<option>Cambodia</option>
					<option>Cameroon</option>
					<option>Canada</option>
					<option>Cape Verde</option>
					<option>Cayman Islands</option>
					<option>Central African Republic</option>
					<option>Chad</option>
					<option>Chile</option>
					<option>China</option>
					<option>Christmas Island</option>
					<option>Colombia</option>
					<option>Comoros</option>
					<option>Cook Islands</option>
					<option>Costa Rica</option>
					<option>Croatia</option>
					<option>Cuba</option>
					<option>Cyprus</option>
					<option>Czech Republic</option>
					<option>Côte d'Ivoire</option>
					<option>Democratic Republic of the Congo</option>
					<option>Denmark</option>
					<option>Djibouti</option>
					<option>Dominica</option>
					<option>Dominican Republic</option>
					<option>Ecuador</option>
					<option>Egypt</option>
					<option>El Salvador</option>
					<option>Equatorial Guinea</option>
					<option>Eritrea</option>
					<option>Estonia</option>
					<option>Ethiopia</option>
					<option>Falkland Islands</option>
					<option>Faroe Islands</option>
					<option>Fiji</option>
					<option>Finland</option>
					<option>France</option>
					<option>French Polynesia</option>
					<option>Gabon</option>
					<option>Gambia</option>
					<option>Georgia</option>
					<option>Germany</option>
					<option>Ghana</option>
					<option>Gibraltar</option>
					<option>Greece</option>
					<option>Greenland</option>
					<option>Grenada</option>
					<option>Guam</option>
					<option>Guatemala</option>
					<option>Guinea</option>
					<option>Guinea Bissau</option>
					<option>Guyana</option>
					<option>Haiti</option>
					<option>Honduras</option>
					<option>Hong Kong</option>
					<option>Hungary</option>
					<option>Iceland</option>
					<option>India</option>
					<option>Indonesia</option>
					<option>Iran</option>
					<option>Iraq</option>
					<option>Ireland</option>
					<option>Israel</option>
					<option>Italy</option>
					<option>Jamaica</option>
					<option>Japan</option>
					<option>Jordan</option>
					<option>Kazakhstan</option>
					<option>Kenya</option>
					<option>Kiribati</option>
					<option>Kuwait</option>
					<option>Kyrgyzstan</option>
					<option>Laos</option>
					<option>Latvia</option>
					<option>Lebanon</option>
					<option>Lesotho</option>
					<option>Liberia</option>
					<option>Libya</option>
					<option>Liechtenstein</option>
					<option>Lithuania</option>
					<option>Luxembourg</option>
					<option>Macao</option>
					<option>Macedonia</option>
					<option>Madagascar</option>
					<option>Malawi</option>
					<option>Malaysia</option>
					<option>Maldives</option>
					<option>Mali</option>
					<option>Malta</option>
					<option>Marshall Islands</option>
					<option>Martinique</option>
					<option>Mauritania</option>
					<option>Mauritius</option>
					<option>Mexico</option>
					<option>Micronesia</option>
					<option>Moldova</option>
					<option>Monaco</option>
					<option>Mongolia</option>
					<option>Montserrat</option>
					<option>Morocco</option>
					<option>Mozambique</option>
					<option>Myanmar</option>
					<option>Namibia</option>
					<option>Nauru</option>
					<option>Nepal</option>
					<option>Netherlands</option>
					<option>Netherlands Antilles</option>
					<option>New Zealand</option>
					<option>Nicaragua</option>
					<option>Niger</option>
					<option>Nigeria</option>
					<option>Niue</option>
					<option>Norfolk Island</option>
					<option>North Korea</option>
					<option>Norway</option>
					<option>Oman</option>
					<option>Pakistan</option>
					<option>Palau</option>
					<option>Panama</option>
					<option>Papua New Guinea</option>
					<option>Paraguay</option>
					<option>Peru</option>
					<option>Philippines</option>
					<option>Pitcairn Islands</option>
					<option>Poland</option>
					<option>Portugal</option>
					<option>Puerto Rico</option>
					<option>Qatar</option>
					<option>Republic of the Congo</option>
					<option>Romania</option>
					<option>Russian Federation</option>
					<option>Rwanda</option>
					<option>Saint Kitts and Nevis</option>
					<option>Saint Lucia</option>
					<option>Saint Pierre</option>
					<option>Saint Vicent and the Grenadines</option>
					<option>Samoa</option>
					<option>San Marino</option>
					<option>Sao Tomé and Príncipe</option>
					<option>Saudi Arabia</option>
					<option>Senegal</option>
					<option>Serbia and Montenegro</option>
					<option>Seychelles</option>
					<option>Sierra Leone</option>
					<option>Singapore</option>
					<option>Slovakia</option>
					<option>Slovenia</option>
					<option>Soloman Islands</option>
					<option>Somalia</option>
					<option>South Africa</option>
					<option>South Georgia</option>
					<option>South Korea</option>
					<option>Soviet Union</option>
					<option>Spain</option>
					<option>Sri Lanka</option>
					<option>Sudan</option>
					<option>Suriname</option>
					<option>Swaziland</option>
					<option>Sweden</option>
					<option>Switzerland</option>
					<option>Syria</option>
					<option>Taiwan</option>
					<option>Tajikistan</option>
					<option>Tanzania</option>
					<option>Thailand</option>
					<option>Tibet</option>
					<option>Timor-Leste</option>
					<option>Togo</option>
					<option>Tonga</option>
					<option>Trinidad and Tobago</option>
					<option>Tunisia</option>
					<option>Turkey</option>
					<option>Turkmenistan</option>
					<option>Turks and Caicos Islands</option>
					<option>Tuvalu</option>
					<option>UAE</option>
					<option>Uganda</option>
					<option>Ukraine</option>
					<option>United Kingdom</option>
					<option>United States of America</option>
					<option>Uruguay</option>
					<option>US Virgin Islands</option>
					<option>Uzbekistan</option>
					<option>Vanuatu</option>
					<option>Vatican City</option>
					<option>Venezuela</option>
					<option>Vietnam</option>
					<option>Wallis and Futuna</option>
					<option>Yemen</option>
					<option>Zambia</option>
					<option>Zimbabwe</option>
				</select>
			</td>
			<td>
				<label>Profile Image:</label>
				<img id="img" src="./img/user.png" alt="Image Preview" width="300" />
				<br>
				<input name="img" type="file" id="imginput">	
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit">	
			</td>
		</tr>
	</table>
</form>
<script>
//repeat password check
//update passwordcheck validation pattern to match password value when this change
document.forms[0].querySelector("#password").onchange=function(){
	document.forms[0].querySelector("#passwordcheck").pattern=this.value;
}

//profile image preview
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
           document.forms[0].querySelector("#img").src=e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}

document.forms[0].querySelector("#imginput").onchange=function(){
    readURL(this);
};

//flag image preview
document.forms[0].querySelector("#flaginput").onchange=function(){
	var newsrc='./img/flags/flags_medium/'+this.value.replace(' ','_')+'.png';
    document.forms[0].querySelector("#flagimg").src=newsrc;
};
</script>

<?php
require_once './footer.inc.php';
?>

