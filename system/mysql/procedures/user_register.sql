drop procedure if exists user_register; 

delimiter // 

create procedure user_register (in p_alias varchar(100), in p_password_hash varchar(40), in p_salt varchar(32), in p_cedula varchar(11), in p_name varchar(50), in p_last_name varchar(50), in p_email varchar(320)) 
  begin 
    if not exists(select 1 from users where alias = p_alias) then 
      insert into users (alias, password_hash, salt, cedula, name, last_name, email, registration_date) values (p_alias, p_password_hash, p_salt, p_cedula, p_name, p_last_name, p_email, now()); 
      
      select 0 as errno; 
    else 
      select 1 as errno; 
    end if; 
  end 
  // 
  
delimiter ; 