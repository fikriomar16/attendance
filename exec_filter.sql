create or replace function filter_transaction() returns trigger as $$
begin
/* cek scan in */
IF (select split_part(new.dev_alias, '-', 1))='IN' THEN
	RAISE NOTICE 'SCAN IN';
	/* cek jadwal aktif */
	IF exists (select masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1) THEN
		RAISE NOTICE 'JADWAL AKTIF';
		/* cek jika ada act dalam shift */
		IF exists (select * from acc_transaction_2a,sys_sch_users where pin=new.pin and sub_masuk<=new.event_time and new.event_time<=sub_pulang and sys_sch_users.masuk=acc_transaction_2a.masuk and sys_sch_users.pulang=acc_transaction_2a.pulang order by event_time desc limit 1) THEN
			RAISE NOTICE 'ADA LAST ACT';
			/* cek act terakhir out */
			IF exists (select * from acc_transaction_2a where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2a where pin=new.pin order by event_time desc limit 1), '-', 1))='OUT' order by event_time desc limit 1) THEN
				RAISE NOTICE 'ACT LAST OUT';
				IF (new.verify_mode_no) = 1 THEN
					RAISE NOTICE 'EMPLOYEE';
					/* Jika Employee */
					/* hitung durasi di luar ruangan */
					UPDATE acc_transaction_3a 
					SET out_duration = (SELECT out_duration from acc_transaction_3a,sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by acc_transaction_3a.masuk desc limit 1) + (SELECT AGE(new.event_time,(select event_time from acc_transaction_2a where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2a where pin=new.pin order by event_time desc limit 1), '-', 1))='OUT' order by event_time desc limit 1)))
					WHERE pin = new.pin and masuk = (select masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1);
					RAISE NOTICE 'UPDATE OUT_DURATION TIER 3';
					/* Insert Data Baru ke Tier 2 Employee */
					INSERT INTO acc_transaction_2a(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,shift,date,masuk,pulang,flag_sap)
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
						1
					);
					RAISE NOTICE 'INSERT TIER 2 EMPLOYEE';
				END IF;
				IF (new.verify_mode_no) = 4 THEN
					RAISE NOTICE 'VISITOR';
					/* Jika Visitor */
					/* Insert Data Baru ke Tier 2 Visitor */
					INSERT INTO acc_transaction_2c(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,flag_sap)
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
						1
					);
					RAISE NOTICE 'INSERT TIER 2 VISITOR';
				END IF;
			END IF;
		/* cek jika tidak ada act dalam 6 jam */
		ELSE
			RAISE NOTICE 'TIDAK ADA LAST ACT';
			IF (new.verify_mode_no) = 1 THEN
				RAISE NOTICE 'EMPLOYEE';
				/* Insert Data ke Tier 2 Employee */
				INSERT INTO acc_transaction_2a(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,shift,date,masuk,pulang,flag_sap)
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
					1
				);
				RAISE NOTICE 'INSERT TIER 2 EMPLOYEE';
				/* Insert Data ke Tier 3 */
				INSERT INTO acc_transaction_3a(area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,shift,date,masuk,pulang,in_scan,out_duration,in_duration)
				values (
					new.area_name,
					new.dept_name,
					new.dev_alias,
					new.dev_sn,
					new.event_time,
					new.name,
					new.pin,
					(SELECT shift from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
					(SELECT tanggal from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
					(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1),
					(SELECT pulang from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by pulang desc limit 1),
					new.event_time,
					'00:00:00',
					'00:00:00'
				);
				RAISE NOTICE 'INSERT TIER 3';
				IF new.event_time > (SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1) THEN
					UPDATE acc_transaction_3a 
					SET late_duration = (SELECT AGE(new.event_time,(SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1)))
					WHERE pin = new.pin and masuk = (SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1);
				END IF;
				RAISE NOTICE 'COUNT LATE';
			END IF;
			IF (new.verify_mode_no) = 4 THEN
				RAISE NOTICE 'VISITOR';
				/* Insert Data ke Tier 2 Visitor */
				INSERT INTO acc_transaction_2c(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,flag_sap)
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
					1
				);
				RAISE NOTICE 'INSERT TIER 2 VISITOR';
			END IF;
		END IF;
	END IF;
END IF;
/* cek scan out */
IF (select split_part(new.dev_alias, '-', 1))='OUT' THEN
	RAISE NOTICE 'SCAN OUT';
	/* cek jadwal aktif */
	IF exists (select masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1) THEN
		RAISE NOTICE 'JADWAL AKTIF';
		/* cek jika ada act dalam shift */
		IF exists (select * from acc_transaction_2a,sys_sch_users where pin=new.pin and sub_masuk<=new.event_time and new.event_time<=sub_pulang and sys_sch_users.masuk=acc_transaction_2a.masuk and sys_sch_users.pulang=acc_transaction_2a.pulang order by event_time desc limit 1) THEN
			RAISE NOTICE 'ADA LAST ACT';
			/* cek act terakhir in */
			IF exists (select * from acc_transaction_2a where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2a where pin=new.pin order by event_time desc limit 1), '-', 1))='IN' order by event_time desc limit 1) THEN
				RAISE NOTICE 'ACT LAST IN';
				IF (new.verify_mode_no) = 1 THEN
					RAISE NOTICE 'EMPLOYEE';
					/* Jika Employee */
					/* hitung durasi di dalam ruangan */
					UPDATE acc_transaction_3a 
					SET out_scan = new.event_time, in_duration = (SELECT in_duration from acc_transaction_3a,sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by acc_transaction_3a.masuk desc limit 1) + (SELECT AGE(new.event_time,(select event_time from acc_transaction_2a where pin=new.pin and (select split_part((select dev_alias from acc_transaction_2a where pin=new.pin order by event_time desc limit 1), '-', 1))='IN' order by event_time desc limit 1)))
					WHERE pin = new.pin and masuk = (SELECT masuk from sys_sch_users where sys_sch_users.nik = new.pin and new.event_time<=sys_sch_users.sub_pulang and sys_sch_users.sub_masuk<=new.event_time order by masuk desc limit 1);
					RAISE NOTICE 'UPDATE IN_DURATION TIER 3';
					/* Insert Data Baru ke Tier 2 Employee */
					INSERT INTO acc_transaction_2a(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,shift,date,masuk,pulang,flag_sap)
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
						1
					);
					RAISE NOTICE 'INSERT TIER 2 EMPLOYEE';
				END IF;
				IF (new.verify_mode_no) = 4 THEN
					RAISE NOTICE 'VISITOR';
					/* Jika Visitor */
					/* Insert Data Baru ke Tier 2 Visitor */
					INSERT INTO acc_transaction_2c(id,area_name,dept_name,dev_alias,dev_sn,event_time,name,pin,vid_linkage_handle,flag_sap)
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
						1
					);
					RAISE NOTICE 'INSERT TIER 2 VISITOR';
				END IF;
			END IF;
		END IF;
	END IF;
END IF;
RAISE NOTICE '=================================================================';
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