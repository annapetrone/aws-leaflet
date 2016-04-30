drop table if exists stations
;

create table stations(
station_id int,
station_name varchar(200),
terminal_id int,
lon decimal, 
lat decimal,
the_geom geometry(POINT)
)
;

create index geo_stations on stations using GIST (the_geom)
;

create index idx_stations_name on stations (station_name)
;
create index idx_station_name_low on stations(lower(station_name))
;

create index idx_station_id on stations(station_id)
;

create index idx_station_terminal on stations(terminal_id)
; 