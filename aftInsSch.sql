create or replace function set_sch_user()
returns trigger 
as
$$
begin
IF (new.nama) is null THEN
	new.nama = (select name from pers_person where pin=new.nik order by pin asc limit 1);
END IF;
IF (new.late_allowed) is null THEN
	new.late_allowed = (select late_allowed from sys_duration,pers_person where sys_duration.auth_dept_id=pers_person.auth_dept_id and pers_person.pin = new.nik);
END IF;
IF (new.out_allowed) is null THEN
	new.out_allowed = (select out_allowed from sys_duration,pers_person where sys_duration.auth_dept_id=pers_person.auth_dept_id and pers_person.pin = new.nik);
END IF;
new.sub_masuk = new.masuk - INTERVAL '30 min';
new.sub_pulang = new.pulang + INTERVAL '360 min';
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
