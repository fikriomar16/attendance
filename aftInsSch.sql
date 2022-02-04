create or replace function set_sch_user()
returns trigger 
as
$$
begin
new.nama = (select name from pers_person where pin=new.nik order by pin asc limit 1);
new.sub_masuk = new.masuk - INTERVAL '30 min';
new.sub_pulang = new.pulang + INTERVAL '180 min';
return new;
end;
$$
LANGUAGE 'plpgsql';

CREATE OR REPLACE TRIGGER trg_set_sch_user
BEFORE INSERT
ON sys_sch_users
FOR EACH ROW
EXECUTE PROCEDURE set_sch_user();

/*
DROP TRIGGER set_sch_user on sys_sch_users;
DROP FUNCTION set_sch_user();
*/
