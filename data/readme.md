# data directory

+ The cabi rides data is pulled from their [website](https://s3.amazonaws.com/capitalbikeshare-data/index.html), using the download_files.py python script.
+ The headers.csv file contains the file format information for each of the rides csv files. Over time the column names changed and some columns were modified/ added. So to sort all this out, check the headers.csv file, which contains the hear row and first data record of each file. This file is created by running get_headers.py
+ The bike stations data, which contains the names, ids, and spatial coordinates of the bike stations, is contained in bikeStations.csv. I manually pulled these from the live station status [feed](https://www.capitalbikeshare.com/data/stations/bikeStations.xml), but really this should be done automatically. This will be an update when I have time.
