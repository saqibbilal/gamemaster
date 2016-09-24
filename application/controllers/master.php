<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {
	private $errors;
public function __construct() {
    parent::__construct();
//    ini_set('display_errors',1);
//    error_reporting(E_ALL);
    
    $this->layout = 'default';
    $this->errors = $this->config->item('errors');
    $this->imgpath = $this->config->item('imgpath');
    $this->load->helper('common_helper','common');
    //        $this->load->model('Htmodel');
    $this->load->model("users_model", "Users");        
    $this->load->model("games_model", "Games");        
}
public function index(){
    $this->redirectDashboard();
    if($this->input->post()){
        if(isUserEmailExist($this->cleaner($this->input->post('email')))){
            $userInfo = $this->Users->getBy('email', $this->cleaner($this->input->post('email')), 'password', md5($this->input->post('password')));
            if($userInfo){
                makeUserSession($userInfo);	
                $this->redirectDashboard();
            }else{
                $data['error'] = "<p class='text-error'>Wrong Email address or password.</p>";
                $data['email'] = $this->input->post('email');
                $this->load->view('login', $data);
            }
        }else{
            $data['error'] = "<p class='text-error'>Email address does not exist. Please signup.</p>";
            $data['email'] = $this->input->post('email');
            $this->load->view('login', $data);
        }
    }else{
        $this->load->view('login');
    }
}

public function games(){
    echo 'i am here now';
}

////////////////////////////////////////////////////////////////////////////////////////////
function register(){
    $this->redirectDashboard();
    if($this->input->post()){
        $data = array();
        $data['first_name'] = $this->cleaner($this->input->post('first_name'));
        $data['last_name'] = $this->cleaner($this->input->post('last_name'));
        $data['username'] = $this->cleaner($this->input->post('username'));
        $data['email'] = $this->cleaner($this->input->post('email'));
        $temp_password = $this->cleaner($this->input->post('password')); // encrypt it

        if ($data['first_name'] == '') {
            $this->errorCode = 'E0001';
            $this->out('', $this->errorCode);
        }
        if ($data['last_name'] == '') {
            $this->errorCode = 'E0002';
            $this->out('', $this->errorCode);
        }
        if ($data['username'] == '') {
            $this->errorCode = 'E0003';
            $this->out('', $this->errorCode);
        }
        if ($data['email'] == '') {
            $this->errorCode = 'E0004';
            $this->out('', $this->errorCode);
        }
        if(!validEmail($data['email'])){
            $this->errorCode = 'E0005';
            $this->out('', $this->errorCode);
        }
        if ($temp_password == '') {
            $this->errorCode = 'E0006';
            $this->out('', $this->errorCode);
        }
        $username_check = $this->Users->check_username($data['username']);
        if (count($username_check) > 0) {
            $this->errorCode = 'E0007';
            $this->out('', $this->errorCode);
        }
        $data['password'] = md5($temp_password);
        $data['user_type'] = 'player';
        $myresult = $this->Users->register($data);

        $result_array = array();
        if($myresult){
            $result_array['status'] = 'registration successful';
            $userInfo = $this->Users->getBy('email', $data['email'], 'password', $data['password']);
            if($userInfo){
                makeUserSession($userInfo);	
                redirect(base_url(), 'refresh');
            }else{
                $data['error'] = "<p class='text-error'>Wrong Email address or password.</p>";
                $data['email'] = $this->input->post('email');
                $this->load->view('login', $data);
            }
            makeUserSession($userInfo);
            $this->redirectDashboard();
            // call dashboard
        }else{
            $result_array['status'] = 'registration failed';
        }
    }else{
        $this->load->view('signup');
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function dashboard(){
    echo 'i am on player dashboard';
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function admin_dashboard(){
    $this->layout = 'admin';
    $this->load->view('admin_dashboard');
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
public function out($data_array = array(), $errorCode = '') {
    if ($this->errorCode != '') {
        $result_array['http_status'] = $this->errorCode;
        $result_array['error'] = $this->errors[$this->errorCode];
    } else {
        $result_array['http_status'] = '200';
        $result_array['data'] = $data_array;
    }
//    header('Content-Type: application/json');
//    echo json_encode($result_array);
//    exit;

        $data['error'] = "<p class='text-error'>".$result_array['error']."</p>";
        $this->load->view('login', $data);
    
}        
        
public function cleaner($input){
    $input = trim($input);
    $input = strip_tags($input);
    return $input;

}      

public function logout(){
        $this->load->library('session');
        $this->session->sess_destroy();
        redirect(base_url());
}	
public function redirectDashboard(){
    if($this->session->userdata('id')){
        if($this->session->userdata('user_type') == 'player'){
            redirect(base_url()."index.php/master/dashboard", 'refresh');
        }elseif($this->session->userdata('user_type') == 'gamemaster') {            
            redirect(base_url()."index.php/master/admin_dashboard", 'refresh');
        }else{
            redirect(base_url()."index.php/master/logout", 'refresh');
        }
    }else{
        // do nothing
    }
}
// end of master controller
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */