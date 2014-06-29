drop procedure if exists homework_get_pages; 

delimiter // 

create procedure homework_get_pages () 
  begin 
    select (select count(id) from homeworks where corrected = 1) as corrected, (select count(id) from homeworks where corrected = 0) as uncorrected; 
  end 
  // 
  
delimiter ; 