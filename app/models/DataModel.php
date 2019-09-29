<?php
namespace App\Model;
header("Content-Type: text/html; charset=UTF-8");

final class DataModel extends BaseModel
{
    
    public function getAllConfig()
    {
        $sql = 'SELECT * FROM member';
        $sth = $this->db->prepare($sql);
        $sth->execute();
        $rcCfgs = $sth->fetchAll();

        echo $rcCfgs;
    }

    //INSERT user Data into uesr_temporary Table 
    public function addUser($user) {
      try{
        $sql = "SELECT email FROM member WHERE email = ?";
        $sth = $this->db->prepare($sql);
        $sth->execute(array($user['email']));

        //이메일 중복체크 
        if($sth->rowCount() > 0){
          $result = array(
            "result_code" => 2,
            "message" => "already exist email"
          );
          return json_encode($result);
        }

        $sql = "INSERT INTO member(email, hashed_password, name, phone, is_active, authorized_code, create_date)
              values (?, ?, ?, 
                       ?, ?, ?, ?);";
        $sth = $this->db->prepare($sql);
        $date = date("Y-m-d H:i:s");

        $sth->execute(array($user['email'], $user['hashed_password'], $user['name'], 
                              $user['phone'], $user['is_active'], $user['authorized_code'], $date));
        
        if($sth->rowCount() == 1){
          $result = array(
            "result_code" => 1,
            "message" => "success"
          );
        }

      }catch(\Exception $e){
        $result = array(
                    "result_code" => 0,
                    "message" => $e->getMessage()
                  );
      }

      return json_encode($result);
  }


  //SELECT authorized code using email
  public function user_authorized_update($user) {
    try{
      $sql = "SELECT email FROM member WHERE email = ? AND authorized_code = ?";
      $sth = $this->db->prepare($sql);
      $sth->execute(array($user['email'], $user['authorized_code']));

      //이메일 중복체크 
      if($sth->rowCount() > 0){
        $sql = "UPDATE member SET is_active = 1 WHERE email = ?";
        $sth = $this->db->prepare($sql);
        $sth->execute(array($user['email']));

        if($sth->rowCount() > 0){
          $result = array(
            "result_code" => 1,
            "message" => "success"
          );
        }else{
          $result = array(
            "result_code" => 2,
            "message" => "already exist value"
          );
        }
      }else{
        $result = array(
          "result_code" => 3,
          "message" => "nothing match"
        );
      }
    }catch(\Exception $e){
      $result = array(
                  "result_code" => 0,
                  "message" => $e->getMessage()
                );
    }

    return json_encode($result);
  }

    //SELECT sign_in process
    public function is_sign_in($user) {
      try{
        $sql = "SELECT hashed_password, is_active FROM member WHERE email = ?";
        $sth = $this->db->prepare($sql);
        $sth->execute(array($user['email']));
        $user_hashed_password = $sth->fetch();

        //EMAIL이 없는 경우
        if($sth->rowCount() == 0){
          $result_json = array(
            'result_code' => 3,
            'message' => 'nothing on WHERE'
          );
          return json_encode($result_json);
        }

        //아직 인증 받지 않았을 때
        if($user_hashed_password['is_active'] != 1){
          $result_json = array(
            'result_code' => 5,
            'message' => 'not yet authorized'
          );
          return json_encode($result_json);
        }
        
        //패스워드 맞는지 체크
        if(password_verify($user['password'], $user_hashed_password['hashed_password'])){
          //패스워드 맞을때
          $sql = "SELECT usn FROM member WHERE email = ?;";
          $sth = $this->db->prepare($sql);
          $sth->execute(array($user['email']));
          $result_data = $sth->fetch();

          $result_json = array(
            'result_code' => 1,
            'usn' => $result_data['usn']
          );
          
        }else{ 
          //패스워드 틀렸을때
          $result_json = array(
            'result_code' => 4,
            'message' => 'not match password'
          );
        }

        return json_encode($result_json);
      }catch(Exception $e){
        $result_json = array(
          'result_code' => 0,
          'message' => $e->getMessage()
        );
        return json_encode($result_json);
      }

    }

}
