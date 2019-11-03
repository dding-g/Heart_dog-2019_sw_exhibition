<?php
namespace App\Controller;
use Slim\Http\Request;


class DoginfoController extends BaseController
{
        //dog_registration_process method
        public function dog_registration_process()
        {
            $dog_data_json = file_get_contents('php://input');
            $dog_data = json_decode($dog_data_json, true);
       
            return $this->db_model->regist_dog($dog_data);
        }


        //dog profile process
        public function dog_profile_process()
        {
            $dog_data_json = file_get_contents('php://input');
            $dog_data = json_decode($dog_data_json, true);
       
            return $this->db_model->regist_dog($dog_data);
        }

        //dog data store process
        public function dog_data_store_process()
        {
            $dog_data_json = file_get_contents('php://input');
            $dog_data = json_decode($dog_data_json, true);
       
            return $this->db_model->store_dog_data($dog_data);
        }

        //HEART RATE data store process
        public function heart_rate_store_process()
        {
            $dog_data_json = file_get_contents('php://input');
            $dog_data = json_decode($dog_data_json, true);
       
            return $this->db_model->store_dog_heart_data($dog_data);
        }

        //GPS data store process
        public function gps_store_process()
        {
            $dog_data_json = file_get_contents('php://input');
            $dog_data = json_decode($dog_data_json, true);
       
            return $this->db_model->store_dog_gps_data($dog_data);
        }
        
        //dog select before bluetooth connection
        public function select_heart_rate_history_process()
        {
            $data_json = file_get_contents('php://input');
            $data = json_decode($data_json, true);
            
            return $this->db_model->select_heart_rate_history($data);
        }

        //dog select before bluetooth connection
        public function select_dog_process()
        {
            $usn_json = file_get_contents('php://input');
            $usn = json_decode($usn_json, true);
       
            return $this->db_model->select_dog($usn);
        }


        //dog delete
        public function delect_dog_info_process()
        {
            $dog_json = file_get_contents('php://input');
            $dsn = json_decode($dog_json, true);
       
            return $this->db_model->delect_dog_info($dsn);
        }

        //dog delete
        public function home_device_store_dog_info()
        {
            $dog_json = file_get_contents('php://input');
            $dog_info = json_decode($dog_json, true);
       
            return $this->db_model->store_dog_gps_data($dog_info);
        }

        //test
        public function test_response()
        {   
            $file = fopen("/test_fd/test.txt", "a+"); //파일을 열음 또는 생성 fwirte("파일이름", "입력할문자열") //파일에 내용 입력 fclose("파일이름") //열었던 파일을 종료
            fwrite($file, file_get_contents('php://input'));  
            fclose($file);
        }

        //test
        public function test_response_view()
        {
            return $this->test_data;
        }

    
}

?>