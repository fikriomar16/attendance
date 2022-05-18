create or replace function filter_transaction() returns trigger as $$
begin
IF (select split_part(new.dev_alias, '-', 1))='IN' THEN
	IF (new.verify_mode_no) = 4 THEN
		IF EXISTS (select * from acc_transaction_2c where pin=new.pin and AGE(new.event_time,event_time)<='06:00:00' order by event_time desc limit 1) THEN
			IF exists (select * from acc_transaction_2c where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2c where pin=new.pin order by event_time desc limit 1), '-', 1))='OUT' order by event_time desc limit 1) THEN
				INSERT INTO acc_transaction_2c(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle)
				values (
					new.id,
					new.area_name,
					new.dept_name,
					new.dev_alias,
					new.dev_sn,
					new.event_time,
					new.name,
					new.pin,
					new.vid_linkage_handle
				);
			END IF;
		ELSE
			INSERT INTO acc_transaction_2c(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle)
			values (
				new.id,
				new.area_name,
				new.dept_name,
				new.dev_alias,
				new.dev_sn,
				new.event_time,
				new.name,
				new.pin,
				new.vid_linkage_handle
			);
			INSERT INTO acc_transaction_3c(area_name,dept_name,name,pin,first_scan,flag_sap)
			values (
				new.area_name,
				new.dept_name,
				new.name,
				new.pin,
				new.event_time,
				1
			);
		END IF;
	END IF;
	IF EXISTS (SELECT code from auth_department,pers_person where pers_person.auth_dept_id=auth_department.id and pers_person.pin=new.pin and CAST(auth_department.code as integer) in (2,3,4,12)) THEN
		IF (new.verify_mode_no) != 4 THEN
			IF exists (select masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1) THEN
				IF exists (select * from acc_transaction_2a,sys_sch_users where pin=new.pin and sub_masuk<=new.event_time and new.event_time<=sub_pulang and sys_sch_users.masuk=acc_transaction_2a.masuk and sys_sch_users.pulang=acc_transaction_2a.pulang order by event_time desc limit 1) THEN
					IF exists (select * from acc_transaction_2a where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2a where pin=new.pin order by event_time desc limit 1), '-', 1))='OUT' order by event_time desc limit 1) THEN
						UPDATE acc_transaction_3a 
						SET flag_sap = 1, out_duration = ((SELECT out_duration from acc_transaction_3a,sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by acc_transaction_3a.masuk desc limit 1) + (SELECT AGE(new.event_time,(select event_time from acc_transaction_2a where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2a where pin=new.pin order by event_time desc limit 1), '-', 1))='OUT' order by event_time desc limit 1))))
						WHERE pin = new.pin and masuk = (select masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1);
						INSERT INTO acc_transaction_2a(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,shift,date,masuk,pulang,verify_mode_name,verify_mode_no)
						values (
							new.id,
							new.area_name,
							new.dept_name,
							new.dev_alias,
							new.dev_sn,
							new.event_time,
							new.name,
							new.pin,
							new.vid_linkage_handle,
							(SELECT shift from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
							(SELECT tanggal from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
							(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
							(SELECT pulang from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by pulang desc limit 1),
							new.verify_mode_name,
							new.verify_mode_no
						);
					END IF;
				ELSE
					INSERT INTO acc_transaction_2a(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,shift,date,masuk,pulang,verify_mode_name,verify_mode_no)
					values (
						new.id,
						new.area_name,
						new.dept_name,
						new.dev_alias,
						new.dev_sn,
						new.event_time,
						new.name,
						new.pin,
						new.vid_linkage_handle,
						(SELECT shift from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
						(SELECT tanggal from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
						(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
						(SELECT pulang from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by pulang desc limit 1),
						new.verify_mode_name,
						new.verify_mode_no
					);
					INSERT INTO acc_transaction_3a(area_name,dept_name,name,pin,shift,date,masuk,pulang,in_scan,out_duration,in_duration,flag_sap,out_allowed,shift_code)
					values (
						new.area_name,
						new.dept_name,
						new.name,
						new.pin,
						(SELECT shift from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
						(SELECT tanggal from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
						(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
						(SELECT pulang from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by pulang desc limit 1),
						new.event_time,
						'00:00:00',
						'00:00:00',
						1,
						(SELECT out_allowed from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
						(SELECT shift_code from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1)
					);
					IF new.event_time > (SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1) THEN
						UPDATE acc_transaction_3a 
						SET flag_sap = 1, late_duration = (SELECT AGE(new.event_time,(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1)))
						WHERE pin = new.pin and masuk = (SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1);
					END IF;
				END IF;
			END IF;
		END IF;
	END IF;
END IF;
IF (select split_part(new.dev_alias, '-', 1))='OUT' THEN
	IF (new.verify_mode_no) = 4 THEN
		IF EXISTS (select * from acc_transaction_2c where pin=new.pin and AGE(new.event_time,event_time)<='06:00:00' order by event_time desc limit 1) THEN
			IF exists (select * from acc_transaction_2c where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2c where pin=new.pin order by event_time desc limit 1), '-', 1))='IN' order by event_time desc limit 1) THEN
				UPDATE acc_transaction_3c
				SET flag_sap = 1, last_scan = new.event_time
				WHERE pin = new.pin AND first_scan = (select first_scan from acc_transaction_3c where pin=new.pin and AGE(new.event_time,first_scan)<='06:00:00' order by first_scan desc limit 1);
				INSERT INTO acc_transaction_2c(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle)
				values (
					new.id,
					new.area_name,
					new.dept_name,
					new.dev_alias,
					new.dev_sn,
					new.event_time,
					new.name,
					new.pin,
					new.vid_linkage_handle
				);
			END IF;
		END IF;
	END IF;
	IF EXISTS (SELECT code from auth_department,pers_person where pers_person.auth_dept_id=auth_department.id and pers_person.pin=new.pin and CAST(auth_department.code as integer) in (2,3,4,12)) THEN
		IF (new.verify_mode_no) != 4 THEN
			IF exists (select masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1) THEN
				IF exists (select * from acc_transaction_2a,sys_sch_users where pin=new.pin and sub_masuk<=new.event_time and new.event_time<=sub_pulang and sys_sch_users.masuk=acc_transaction_2a.masuk and sys_sch_users.pulang=acc_transaction_2a.pulang order by event_time desc limit 1) THEN
					IF exists (select * from acc_transaction_2a where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2a where pin=new.pin order by event_time desc limit 1), '-', 1))='IN' order by event_time desc limit 1) THEN
						UPDATE acc_transaction_3a 
						SET flag_sap = 1, out_scan = new.event_time, in_duration = ((SELECT in_duration from acc_transaction_3a,sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by acc_transaction_3a.masuk desc limit 1) + (SELECT AGE(new.event_time,(select event_time from acc_transaction_2a where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2a where pin=new.pin order by event_time desc limit 1), '-', 1))='IN' order by event_time desc limit 1))))
						WHERE pin = new.pin and masuk = (SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1);
						INSERT INTO acc_transaction_2a(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,shift,date,masuk,pulang,verify_mode_name,verify_mode_no)
						values (
							new.id,
							new.area_name,
							new.dept_name,
							new.dev_alias,
							new.dev_sn,
							new.event_time,
							new.name,
							new.pin,
							new.vid_linkage_handle,
							(SELECT shift from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
							(SELECT tanggal from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
							(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
							(SELECT pulang from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by pulang desc limit 1),
							new.verify_mode_name,
							new.verify_mode_no
						);
					END IF;
				END IF;
			END IF;
		END IF;
	END IF;
END IF;
IF (new.dev_alias in ('Office','Poliklinik')) THEN
	IF EXISTS (SELECT code from auth_department,pers_person where pers_person.auth_dept_id=auth_department.id and pers_person.pin=new.pin and CAST(auth_department.code as integer) not in (2,3,4,12)) THEN
		IF EXISTS (select masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1) THEN
			INSERT INTO acc_transaction_2b(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,shift,date,masuk,pulang,verify_mode_name,verify_mode_no)
			values (
				new.id,
				new.area_name,
				new.dept_name,
				new.dev_alias,
				new.dev_sn,
				new.event_time,
				new.name,
				new.pin,
				new.vid_linkage_handle,
				(SELECT shift from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
				(SELECT tanggal from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
				(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
				(SELECT pulang from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by pulang desc limit 1),
				new.verify_mode_name,
				new.verify_mode_no
			);
			IF EXISTS (SELECT * from acc_transaction_3b,sys_sch_users where sys_sch_users.nik=new.pin and acc_transaction_3b.pin=sys_sch_users.nik and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time and sys_sch_users.tanggal=acc_transaction_3b.date order by sys_sch_users.masuk desc limit 1) THEN
				UPDATE acc_transaction_3b 
				SET flag_sap = 1, last_scan = new.event_time
				WHERE pin = new.pin and masuk = (SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1);
			ELSE
				INSERT INTO acc_transaction_3b(area_name,dept_name,name,pin,shift,date,masuk,pulang,first_scan,flag_sap,out_allowed,shift_code)
				values (
					new.area_name,
					new.dept_name,
					new.name,
					new.pin,
					(SELECT shift from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
					(SELECT tanggal from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
					(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
					(SELECT pulang from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by pulang desc limit 1),
					new.event_time,
					1,
					(SELECT out_allowed from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
					(SELECT shift_code from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1)
				);
				IF new.event_time > (SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1) THEN
					UPDATE acc_transaction_3b 
					SET flag_sap = 1, late_duration = (SELECT AGE(new.event_time,(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1)))
					WHERE pin = new.pin and masuk = (SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1);
				END IF;
			END IF;
		END IF;
	END IF;
END IF;
return new;
end;
$$ LANGUAGE 'plpgsql';

CREATE OR REPLACE TRIGGER trg_filter_transaction
AFTER INSERT
ON acc_transaction
FOR EACH ROW
EXECUTE PROCEDURE filter_transaction();

/*
DROP TRIGGER trg_filter_transaction on acc_transaction;
DROP FUNCTION filter_transaction();
*/