<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * @property CI_DB_active_record $db Description
 */
class Games_model extends CI_Model {

function __construct() {
        parent::__construct();
        $this->_table = 'users';
}

public function deleteUser($user_id,$idAs,$tableName){
    $this->db->where($idAs,$user_id);
    $this->db->delete($tableName);
}

//////////////////////////////////////////////////////////////////////////////////////
public function register($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();

}

//////////////////////////////////////////////////////////////////////////////////////
public function test_unique($parameter,$value)
{
    $this->db->where($parameter,$value);
    return $this->db->get('users')->result_array();
}


public function udata($data) { // sends users data based on his username and email address
    //    $username = $data['username'];
    $email = $data['email'];

    //    $this->db->where('username', $username);                                 username is no longer unique
    $this->db->where('email', $email);
    $query = $this->db->get('users');
    if ($query->num_rows() != 1) {
        return 'E020';
    }
    return $query->result_array();
}

///////////////////////////////////////////////////////////////////////////////////////////
public function userdata($user_id) { // sends user's data based on his user_id alone

    $this->db->where('user_id', $user_id);
    return $this->db->get('users')->result_array();

}


////////////////////////////////////////////////////////////////////////////////////////////
public function picsdata($data) { // sends number of photos of a user based on user_id
    $user_id = $data['user_id'];

    $this->db->where('users_id', $user_id);
    $this->db->from('photos');
    $query = $this->db->count_all_results();
    return $query;
}

/////////////////////////////////////////////////////////////////////////////////////////////
public function photoUpload($data) {

    //	$this->db->where('users_id',$data['user_id']);
    $this->db->insert('photos', $data);
    //	echo $this->db->last_query() ;
    return $this->db->insert_id();
}
public function insert_activity($data) {

    $this->db->insert('activity_rate', $data);
    return $this->db->insert_id();
}
public function get_activity_data($user_id) {

    $this->db->where('user_id',$user_id);
    $this->db->order_by("id", "desc"); 
    $this->db->limit(1);
    return $this->db->get('activity_rate')->result_array();

}
public function update_activity($id,$data) {
    $this->db->where('id', $id);
    $this->db->update('activity_rate',$data);
}


//////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////////////////

public function getUserBYId($id) {
    $this->db->where('user_id', $id);
    return $this->db->get('users')->result_array();
}


public function getUserName($user_id) {
    $this->db->where('user_id', $user_id);
    return $this->db->get('users')->row()->username;
}
public function check_username($username) {
    $this->db->where('username', $username);
    return $this->db->get('users')->result_array();
}

public function addNewUser($data) {

    $this->db->insert('users', $data);

    return $this->db->insert_id();
}

public function get_content_by_type_api($content_type) {
    $this->db->where('content_type',$content_type);
    return $this->db->get('contents')->result_array();
}
public function get_all_content_api() {

    return $this->db->get('contents')->result_array();
}



}

?>
