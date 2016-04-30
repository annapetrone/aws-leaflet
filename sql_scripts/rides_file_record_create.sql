drop table if exists rides_file_record
;

create table rides_file_record(
file_name varchar(200),
loaded_timestamp timestamp with time zone not null default (now() at time zone 'utc'), -- if you dont specify the load time it will use the current time
record_count int
)
;