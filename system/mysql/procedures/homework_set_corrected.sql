drop procedure if exists homework_set_corrected; 

delimiter // 

create procedure homework_set_corrected (in p_id int(18) unsigned, in p_corrected bit(1)) 
  begin 
    update homeworks set corrected = p_corrected where id = p_id; 
  end 
  // 
  
delimiter ; 