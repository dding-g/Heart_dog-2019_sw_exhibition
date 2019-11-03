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

    //================USER PROCESS================


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

    //프로필 정보 리턴 모델
    public function display_profile_process($user_data)
    {
      try{
        $sql = "SELECT dog_name, dog_type, dog_size, dog_gender, birth, email, name, phone, create_date
                FROM dog join member
                on dog.usn = member.usn
                WHERE dog.usn = ?";
        $sth = $this->db->prepare($sql);

        $sth->execute(array(
          $user_data['usn']
        ));

        if($sth->rowCount() > 0)
        {
          $result_json = array(
            'result_code' => 1,
            'message' => $sth->fetchAll()
          );
          return json_encode($result_json);
        }else
        {
          $result_json = array(
            'result_code' => 6,
            'message' => "Not Exist data"
          );
          return json_encode($result_json);
        }
        
      }catch(\Exception $e){
        $result_json = array(
          'result_code' => 0,
          'message' => $e->getMessage()
        );
        return json_encode($result_json);
      }
    }
    //============================================


    //================DOG PROCESS================

    public function regist_dog($dog_data) {
      try{
        $sql = "SELECT dsn FROM dog WHERE usn = ? AND dog_name = ?";
        $sth = $this->db->prepare($sql);

        $sth->execute(array(
          $dog_data['usn'],
          $dog_data['dog_name']
        ));

        // 같은 강아지가 있는지 DB에서 확인
        if($sth->rowCount() > 0){
          $result = array(
            "result_code" => 2,
            "message" => "already exist dog information"
          );
          return json_encode($result);
        }

        $sql = "INSERT dog(usn, dog_name, dog_type, dog_size, dog_gender, birth) VALUES (?, ?, ?, ?, ?, ?) ";
        $sth = $this->db->prepare($sql);

        $sth->execute(array(
          $dog_data['usn'],
          $dog_data['dog_name'],
          $dog_data['dog_type'],
          $dog_data['dog_size'],
          $dog_data['dog_gender'],
          $dog_data['birth'],
        ));
        
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

  //store dog data
  public function store_dog_data($dog_data) {
    try{
      $sql = "SELECT dsn FROM dog_info_realtime WHERE dsn = ?";
      $sth = $this->db->prepare($sql);

      $sth->execute(array(
        $dog_data['dsn'],
      ));

      $date = date("Y-m-d H:i:s"); // 서버 시간 DB에 저장

      //이미 데이터가 realtime 존재 하는 경우 UPDATE 실행
      if($sth->fetch() > 0){

        //realtime table에 데이터 update
        $sql = "UPDATE dog_info_realtime 
                SET heart_rate = ?,
                    latitude = ?, 
                    longitude = ?,
                    datetime = ?";
      
        
        $sth = $this->db->prepare($sql);
        $sth->execute(array($dog_data['heart_rate'], $dog_data['latitude'], $dog_data['longitude'], $date));
        
        
      }else{ //데이터가 realtime 에 없는 경우
        //realtime table에 데이터 INSERT
        $sql = "INSERT dog_info_realtime(dsn, usn, heart_rate, latitude, longitude, datetime)
                VALUES(?, ?, ?, ?, ?, ?)";
        $sth = $this->db->prepare($sql);
        $sth->execute(array($dog_data['dsn'], $dog_data['usn'], $dog_data['heart_rate'], $dog_data['latitude'], $dog_data['longitude'], $date));
      }

      //history에 insert
      $sql = "INSERT dog_info_history(dsn, usn, heart_rate, latitude, longitude, datetime)
      VALUES(?, ?, ?, ?, ?, ?)";
      $sth = $this->db->prepare($sql);
      $sth->execute(array($dog_data['dsn'], $dog_data['usn'], $dog_data['heart_rate'], $dog_data['latitude'], $dog_data['longitude'], $date));

      $result = array(
        "result_code" => 1,
        "message" => "success"
      );

    }catch(\Exception $e){
      $result = array(
                  "result_code" => 0,
                  "message" => $e->getMessage()
                );
    }

    return json_encode($result);
  }


    //store dog data
    public function store_dog_heart_data($dog_heart_rate_data) {
      try{
        $sql = "SELECT dsn FROM dog_heart_rate_realtime WHERE dsn = ?";
        $sth = $this->db->prepare($sql);
  
        $sth->execute(array(
          $dog_heart_rate_data['dsn'],
        ));
  
        $date = date("Y-m-d H:i:s"); // 서버 시간 DB에 저장
  
        //이미 데이터가 realtime 존재 하는 경우 UPDATE 실행
        if($sth->fetch() > 0){
  
          //realtime table에 데이터 update
          $sql = "UPDATE dog_heart_rate_realtime 
                  SET heart_rate = ?,
                      datetime = ?
                  WHERE dsn = ?";
        
          
          $sth = $this->db->prepare($sql);
          $sth->execute(array($dog_heart_rate_data['heart_rate'], $date, $dog_heart_rate_data['dsn']));
          
          
        }else{ //데이터가 realtime 에 없는 경우
          //realtime table에 데이터 INSERT
          $sql = "INSERT dog_heart_rate_realtime(dsn, usn, heart_rate, datetime)
                  VALUES(?, ?, ?, ?)";
          $sth = $this->db->prepare($sql);
          $sth->execute(array($dog_heart_rate_data['dsn'], $dog_heart_rate_data['usn'], $dog_heart_rate_data['heart_rate'], $date));
        }
  
        //history에 insert
        $sql = "INSERT dog_heart_rate_history(dsn, usn, heart_rate, datetime)
        VALUES(?, ?, ?, ?)";
        $sth = $this->db->prepare($sql);
        $sth->execute(array($dog_heart_rate_data['dsn'], $dog_heart_rate_data['usn'], $dog_heart_rate_data['heart_rate'], $date));
  
        $result = array(
          "result_code" => 1,
          "message" => "success"
        );
  
      }catch(\Exception $e){
        $result = array(
                    "result_code" => 0,
                    "message" => $e->getMessage()
                  );
      }
  
      return json_encode($result);
    }


    //store GPS data
    public function store_dog_gps_data($dog_gps_data) {
      try{
        $sql = "SELECT dsn FROM dog_gps_realtime WHERE dsn = ?";
        $sth = $this->db->prepare($sql);
  
        $sth->execute(array(
          $dog_gps_data['dsn'],
        ));
  
        $date = date("Y-m-d H:i:s"); // 서버 시간 DB에 저장
  
        //이미 데이터가 realtime 존재 하는 경우 UPDATE 실행
        if($sth->fetch() > 0){
  
          //realtime table에 데이터 update
          $sql = "UPDATE dog_gps_realtime 
                  SET latitude = ?,
                      longitude = ?,
                      datetime = ?
                  WHERE dsn = ?";
        
          
          $sth = $this->db->prepare($sql);
          $sth->execute(array( $dog_gps_data['latitude'], $dog_gps_data['longitude'], $date, $dog_gps_data['dsn']));
          
          
        }else{ //데이터가 realtime 에 없는 경우
          //realtime table에 데이터 INSERT
          $sql = "INSERT dog_gps_realtime(dsn, usn, latitude, longitude, datetime)
                  VALUES(?, ?, ?, ?, ?)";
          $sth = $this->db->prepare($sql);
          $sth->execute(array($dog_gps_data['dsn'], $dog_gps_data['usn'], $dog_gps_data['latitude'], $dog_gps_data['longitude'], $date));
        }
  
        //history에 insert
        $sql = "INSERT dog_gps_history(dsn, usn, latitude, longitude, datetime)
                VALUES(?, ?, ?, ?, ?)";
        $sth = $this->db->prepare($sql);
        $sth->execute(array($dog_gps_data['dsn'], $dog_gps_data['usn'], $dog_gps_data['latitude'], $dog_gps_data['longitude'], $date));
  
        $result = array(
          "result_code" => 1,
          "message" => "success"
        );
  
      }catch(\Exception $e){
        $result = array(
                    "result_code" => 0,
                    "message" => $e->getMessage()
                  );
      }
  
      return json_encode($result);
    }


  //     //store dog data
  // public function store_dog_data($dog_data) {
  //   try{
  //     $sql = "SELECT dsn FROM dog_info_realtime WHERE dsn = ?";
  //     $sth = $this->db->prepare($sql);

  //     $sth->execute(array(
  //       $dog_data['dsn'],
  //     ));

  //     $date = date("Y-m-d H:i:s"); // 서버 시간 DB에 저장

  //     //이미 데이터가 realtime 존재 하는 경우 UPDATE 실행
  //     if($sth->fetch() > 0){

  //       //realtime table에 데이터 update
  //       $sql = "UPDATE dog_info_realtime 
  //               SET heart_rate = ?,
  //                   latitude = ?, 
  //                   longitude = ?,
  //                   datetime = ?";
      
        
  //       $sth = $this->db->prepare($sql);
  //       $sth->execute(array($dog_data['heart_rate'], $dog_data['latitude'], $dog_data['longitude'], $date));
        
        
  //     }else{ //데이터가 realtime 에 없는 경우
  //       //realtime table에 데이터 INSERT
  //       $sql = "INSERT dog_info_realtime(dsn, usn, heart_rate, latitude, longitude, datetime)
  //               VALUES(?, ?, ?, ?, ?, ?)";
  //       $sth = $this->db->prepare($sql);
  //       $sth->execute(array($dog_data['dsn'], $dog_data['usn'], $dog_data['heart_rate'], $dog_data['latitude'], $dog_data['longitude'], $date));
  //     }

  //     //history에 insert
  //     $sql = "INSERT dog_info_history(dsn, usn, heart_rate, latitude, longitude, datetime)
  //     VALUES(?, ?, ?, ?, ?, ?)";
  //     $sth = $this->db->prepare($sql);
  //     $sth->execute(array($dog_data['dsn'], $dog_data['usn'], $dog_data['heart_rate'], $dog_data['latitude'], $dog_data['longitude'], $date));

  //     $result = array(
  //       "result_code" => 1,
  //       "message" => "success"
  //     );

  //   }catch(\Exception $e){
  //     $result = array(
  //                 "result_code" => 0,
  //                 "message" => $e->getMessage()
  //               );
  //   }

  //   return json_encode($result);
  // }




  //return heart_raet / using usn, dsn, start_date, end_date
  public function select_heart_rate_history($user_data) {
    try{
      $sql = "SELECT heart_rate, datetime
              FROM dog_heart_rate_history 
              WHERE usn = ? and dsn = ? and datetime > ? and datetime < ?";
      $sth = $this->db->prepare($sql);

      $sth->execute(array($user_data['usn'], $user_data['dsn'], $user_data['start_date'], $user_data['end_date']));
      
      if($sth->rowCount() > 0){
        $result = array(
          "result_code" => "1",
          "message" => $sth->fetchAll()
        );
      }else{
        $result = array(
          "result_code" => "3",
          "message" => "No match data in DB"
        );
      }

    }catch(\Exception $e){
      $result = array(
                  "result_code" => "0",
                  "message" => $e->getMessage()
                );
    }

    return json_encode($result);
  }


   //return dsn / using usn
   public function select_dog($usn) {
    try{
      $sql = "SELECT dsn, dog_name FROM dog WHERE usn = ?";
      $sth = $this->db->prepare($sql);

      $sth->execute(array($usn['usn']));

      if($sth->rowCount() > 0){
        $result = array(
          "result_code" => 1,
          "message" => $sth->fetchAll()
        );
      }else{
        $result = array(
          "result_code" => 3,
          "message" => "No match data in DB"
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
    
  //return dsn / using usn
  public function delect_dog_info($dog_info) {
    try{
      $sql = "DELETE FROM dog WHERE dsn = ?";
      $sth = $this->db->prepare($sql);

      $sth->execute(array($dog_info['dsn']));

      if($sth->rowCount() > 0){
        $result = array(
          "result_code" => 1,
          "message" => "DELETE Process Success"
        );
      }else{
        $result = array(
          "result_code" => 3,
          "message" => "No match data in DB"
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

}
