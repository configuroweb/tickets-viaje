<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);		
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return $_SESSION['login_station_id'];
		}else{
			return -1;
		}
	}
	function login2(){
		
		extract($_POST);		
		$qry = $this->db->query("SELECT * FROM complainants where email = '".$email."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type ==2){
		$data .= ", station_id = '$station_id' ";
		}else{
		$data .= ", station_id = 0 ";
		}
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", address = '$address' ";
		$data .= ", contact = '$contact' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * from complainants where email ='$email' ".(!empty($id) ? " and id != '$id' " : ''))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}
		if(empty($id))
			$save = $this->db->query("INSERT INTO complainants set $data");
		else
			$save = $this->db->query("UPDATE complainants set $data where id=$id ");
		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
				$qry = $this->db->query("SELECT * FROM complainants where id = $id ");
				if($qry->num_rows > 0){
					foreach ($qry->fetch_array() as $key => $value) {
						if($key != 'password' && !is_numeric($key))
							$_SESSION['login_'.$key] = $value;
					}
						return 1;
				}else{
					return 3;
				}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}

			return 1;
				}
	}
	function save_station(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM stations where station ='$station' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO stations set $data");
		}else{
			$save = $this->db->query("UPDATE stations set $data where id = $id");
		}

		if($save)
			return 1;
	}
	function delete_station(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM stations where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_price(){
		extract($_POST);
		foreach($adult as $k =>$v){
			$v = str_replace(',', '', $v);
			$student[$k] = str_replace(',', '', $student[$k]);
			$senior[$k] = str_replace(',', '', $senior[$k]);
			$data= " station_from = $origin_station ";
			$data.= ", station_to = $k ";
			$data.= ", adult_price = '$v' ";
			$data.= ", student_price = '{$student[$k]}' ";
			$data.= ", senior_price = '{$senior[$k]}' ";
			$chk = $this->db->query("SELECT * FROM prices where station_from = $origin_station and station_to = $k")->num_rows;
			if($chk > 0)
				$save[] = $this->db->query("UPDATE prices set $data where  station_from = $origin_station and station_to = $k");
			else
				$save[] = $this->db->query("INSERT INTO prices set $data");
		}
		if(isset($save))
			return 1;
	}
	function delete_product(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM products where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_price(){
		extract($_POST);
		// echo "SELECT p.*,s.station as sname FROM prices p inner join stations s on s.id = p.station_to where p.station_from = $origin_id and p.station_to = $destination_id ";
		$get = $this->db->query("SELECT p.*,s.station as sname FROM prices p inner join stations s on s.id = p.station_to where p.station_from = $origin_id and p.station_to = $destination_id ");
		$get = $get->num_rows > 0 ? $get->fetch_array() : '';
		$get['adult_price'] = isset($get['adult_price']) ? $get['adult_price'] : 0;
		$get['student_price'] = isset($get['student_price']) ? $get['student_price'] : 0;
		$get['senior_price'] = isset($get['senior_price']) ? $get['senior_price'] : 0;
		return json_encode($get);
	}
	function save_order(){
		extract($_POST);
		$data = " total_amount = '$total_amount' ";
		$data .= ", amount_tendered = '$total_tendered' ";
		$data .= ", order_number = '$order_number' ";
		if(empty($id)){
			$i = 0;
			while($i == 0){
				$ref_no  = mt_rand(1,999999999999);
				$ref_no = sprintf("%'012d", $ref_no);
				$chk = $this->db->query("SELECT * FROM orders where ref_no ='$ref_no' ");
				if($chk->num_rows <= 0){
					$i = 1;
				}
			}
			$data .= ", ref_no = '$ref_no' ";
			$save = $this->db->query("INSERT INTO orders set $data");
			if($save){
				$id = $this->db->insert_id;
			}
		}else{
			$save = $this->db->query("UPDATE orders set $data where id = $id");
		}
		if($save){
			$ids = array_filter($item_id);
			$ids = implode(',',$ids);
			if(!empty($ids))
			$this->db->query("DELETE FROM order_items where order_id = $id and id not ($ids) ");
		foreach($item_id as $k=>$v){
			$data = " order_id = $id ";
			$data .= ", product_id = '{$product_id[$k]}' ";
			$data .= ", qty = '{$qty[$k]}' ";
			$data .= ", price = '{$price[$k]}' ";
			$data .= ", amount = '{$amount[$k]}' ";
			if(empty($v)){
				$this->db->query("INSERT INTO order_items set $data");
			}else{
				$this->db->query("UPDATE order_items set $data where id = $v");
			}
		}
		return $id;
		}
	}
	function delete_order(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM orders where id = ".$id);
		$delete2 = $this->db->query("DELETE FROM order_items where order_id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_ticket(){
		extract($_POST);
		foreach($pax as $k => $v){
			if($v > 0){
				for($i=0; $i < $v; $i++){
					$ci = 0;
					while($ci == 0){
						$ticket_no  = mt_rand(1,999999999999);
						$ticket_no = sprintf("%'012d", $ticket_no);
						$chk = $this->db->query("SELECT * FROM tickets where ticket_no ='$ticket_no' ");
						if($chk->num_rows <= 0){
							$ci = 1;
						}
					}
					$data = " station_from = $origin_station ";
					$data .= ", station_to = '$destination_id' ";
					$data .= ", price = '{$price[$k]}' ";
					$data .= ", passenger_type = $k ";
					$data .= ", ticket_no = '$ticket_no' ";
					$data .= ", processed_by = '{$_SESSION['login_id']}' ";
					// echo "INSERT INTO tickets set $data \n";
					$save = $this->db->query("INSERT INTO tickets set $data");
					if($save)
						$ids[] = $this->db->insert_id;
				}
			}
		}
		if(isset($ids)){
			return urlencode(json_encode($ids));
		}
	}
}