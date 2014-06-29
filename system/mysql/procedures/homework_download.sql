drop procedure if exists homework_download; 

delimiter // 

create procedure homework_download (in p_id int(18) unsigned) 
  begin 
    select homeworks.id as id, cedula, htype, send_date from homeworks inner join users on homeworks.user_id = users.id where homeworks.id = p_id; 
  end 
  // 
  
delimiter ; 