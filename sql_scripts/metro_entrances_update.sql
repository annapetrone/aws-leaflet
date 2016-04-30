update metro_entrances
set the_geom = ST_SetSRID(ST_MakePoint(lon,lat), 4326)
;
