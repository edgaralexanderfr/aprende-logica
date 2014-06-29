drop procedure if exists assignment_download_last; 

delimiter // 

create procedure assignment_download_last () 
  begin 
    select * from assignments order by id desc limit 1; 
  end 
  // 
  
delimiter ; 