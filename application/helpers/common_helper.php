<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*Helper with handling common functions*/

if(!function_exists('makeUserSession')){
	function makeUserSession($userInfo){
		$CI =& get_instance();
		$newdata = array(
			'first_name' => $userInfo[0]->first_name,
			'last_name' => $userInfo[0]->last_name,
			'email' => $userInfo[0]->email,
			'id' => $userInfo[0]->id,
			'user_type' => $userInfo[0]->user_type,
			'logged_in' => TRUE
		);
		$CI->session->set_userdata($newdata);
	}
}

if(!function_exists('validEmail')){
    function validEmail($address) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
    } 
}
if(!function_exists("isUserEmailExist")){
	function isUserEmailExist($email,$user_id = '') {
		$CI =& get_instance();
		$CI->db->where(array('email'=>$email));
		if($user_id!='')
		{
			$CI->db->where('id !=',$user_id);
		}
		$query = $CI->db->get('users');
		$row = $query->result();
		if(count($row) == 0){
			return false;
		}
		return true;
	}
}

