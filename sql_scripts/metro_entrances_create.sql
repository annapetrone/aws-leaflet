drop table if exists metro_entrances
;

create table metro_entrances(
lon decimal, 
lat decimal,
object_id int,
station_name varchar(200),
web_url varchar(200),
description text,
line varchar(50),
the_geom geometry(POINT)
)
;

create index geo_metro on metro_entrances using GIST (the_geom)
;

create index idx_metro_name on metro_entrances (station_name)
;