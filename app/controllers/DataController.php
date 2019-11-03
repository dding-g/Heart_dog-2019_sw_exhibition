<?php
namespace App\Controller;
use Slim\Http\Request;


class DataController extends BaseController
{
 
    public function db_test(){
        $this->db_model->test();
    }

    //password hashed method
    public function hash_password($str_pw)
    {
        return password_hash($str_pw, PASSWORD_DEFAULT);
    }

    //make authorized code for send email
    public function make_authorized_code()
    {   
        $authorized_code = '';
        for($i = 0; $i < 6 ; $i ++){
            $authorized_code = $authorized_code.(string)mt_rand(0, 9);
        }
        return $authorized_code;
    }
    
    //sign up process method
    public function sign_up_process()
    {
        $user_data_json = file_get_contents('php://input');
        $user_data = json_decode($user_data_json, true);
        
        $user_data['hashed_password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);
        $user_data['authorized_code'] = $this->make_authorized_code();
        $user_data['is_active'] = 0;
        $is_sign_up = $this->db_model->addUser($user_data);
        $email_address = $user_data['email'];

        $result = json_decode($is_sign_up, true);

        if($result['result_code'] == 1){
            $this->emailconfig->sendMail($user_data['email'], $user_data['authorized_code']);
            $result = array(
                'result_code' => 1,
                'message' => "success"
            );
            return json_encode($result);
        }else {
            return $is_sign_up;
        }
    }


    //sign up process method_check authorized code
    public function check_authorized_code()
    {
        $user_data_json = file_get_contents('php://input');
        $user_data = json_decode($user_data_json, true);
        
        $is_active_status = $this->db_model->user_authorized_update($user_data);

        return $is_active_status;
    }


    //sign in process method
    public function sign_in_process()
    {
        $user_data_json = file_get_contents('php://input');
        $user_data = json_decode($user_data_json, true);
        
        return $this->db_model->is_sign_in($user_data);
    }

    //profile process method
    public function profile_process()
    {   
        $user_data_json = file_get_contents('php://input');
        $user_data = json_decode($user_data_json, true);

        return $this->db_model->display_profile_process($user_data);
    }

}
