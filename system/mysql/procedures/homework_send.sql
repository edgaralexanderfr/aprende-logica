drop procedure if exists homework_send; 

delimiter // 

create procedure homework_send (in p_user_id int(10) unsigned, in p_htype varchar(5), in TIME_PER_HOMEWORK int(10) unsigned, in HOMEWORKS_PER_TIME int(2)) 
  begin 
    if ((select count(id) from homeworks where user_id = p_user_id and timestampdiff(second, send_date, now()) < TIME_PER_HOMEWORK) < HOMEWORKS_PER_TIME) then 
      insert into homeworks (user_id, htype, send_date) values (p_user_id, p_htype, now()); 
      
      select 0 as errno, last_insert_id() as id; 
    else 
      select 1 as errno; 
    end if; 
  end 
  // 
  
delimiter ; 