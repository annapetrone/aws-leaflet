drop view if exists rides_v
;

drop table if exists rides
;

create table rides
(
duration_str varchar(100), -- old style formatting of trip duration as "0h 30min. 12sec."
duration_ms bigint, -- updated format of ride duration in miliseconds
start_time timestamp with time zone,
end_time timestamp with time zone,
start_terminal_id int,
start_station_name text,
end_terminal_id int,
end_station_name text,
bike_id	varchar(50),
member_type varchar(100),

/*duration_hours int,
duration_minutes int,
duration_seconds int,
start_hour int,*/
start_time_period varchar(50),
--end_hour int,
end_time_period varchar(50)

)
;



create index idx_rides_member on rides(member_type)
;

create index idx_rides_start_terminal on rides(start_terminal_id)
;
create index idx_rides_end_terminal on rides(end_terminal_id)
;

create index idx_rides_start_period on rides(start_time_period)
;
create index idx_rides_end_period  on rides(end_time_period)
;


create index idx_rides_start_time on rides(start_time)
;
create index idx_rides_end_time on rides(end_time)
;

create view rides_v
as
select *, 
 extract(hour from start_time) as start_hour,
 extract(hour from end_time) as end_hour,
 cast(trim(to_char(start_time,'Day')) as varchar(20)) as start_weekday,
 cast(trim(to_char(end_time,'Day')) as varchar(20)) as end_weekday
from rides
;