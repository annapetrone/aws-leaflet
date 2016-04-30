# aws-leaflet
+ this project uses an amazon web services relational database (postgres with the postgis extension) to power a [leaflet](http://leafletjs.com/) visualization 
+ here is the example created by this project [http://ec2-52-207-212-11.compute-1.amazonaws.com/](http://ec2-52-207-212-11.compute-1.amazonaws.com/)


## how to use this repo
+ below will document how to set up the database and website front end using aws. 
+ the python scripts to load data are found in [load_scripts](https://github.com/ampetr/aws-leaflet/tree/master/load_scripts). 
+ to repeat this example yourself, the data files used are included in the [data](https://github.com/ampetr/aws-leaflet/tree/master/data) directory. the sql statements to create and update tables are located in tbe [sql_scripts](https://github.com/ampetr/aws-leaflet/tree/sql_scripts/) directory.
+ website files are located in the [html](https://github.com/ampetr/aws-leaflet/tree/master/html) directory. 

# setting up an aws postgres database
+ [detailed instructions](https://console.aws.amazon.com/rds/home?region=us-east-1) from Amazon
+ log in to aws and choose to launch a new rds instance
![aws rds](https://raw.githubusercontent.com/ampetr/aws-leaflet/master/tutorial/aws-rds.png)
+ choose postgresql database. choose the 'dev' option (it's free). 
+ on the next screen, check the box to only show free tier options. can make the storage as high as 20GB
+ set up user (typically the username used in 'postgres')
+ choose to create a new security group, set up database name, etc
+ when you hit launch db instance, the database will be created, and will take a few minutes to set up
+ note, the page where all your instances are listed is referred to as the **AWS console**
+ once it is ready, right click and select 'see details' and click on the security group which will be called 'launch-wizard-1' (unless you have customized the name during setup)
+ under the 'inbound' tab, create a rule to accept postgresql requests (port 5432) from your IP address
+ download and install [pgadminIII](http://www.pgadmin.org/). launch and click on the connect to server button and enter your database credentials
  + note- the host name is your database endpoint which can be found on the RDS console (leaving off the colon and port number) 
+ run the following sql to activate the postgis extension
```
CREATE EXTENSION postgis;
CREATE EXTENSION postgis_topology;
CREATE EXTENSION fuzzystrmatch;
```
  
# install python extension for postgres
+ python3 is already installed but pip you need to get. (instructions taken from http://docs.aws.amazon.com/cli/latest/userguide/installing.html#install-pip) 
`curl -O https://bootstrap.pypa.io/get-pip.py`
`sudo python3 get-pip.py`

+ install python devtools (so that the postgres python library to work. explanation [here](https://web.archive.org/web/20110305033324/http://goshawknest.wordpress.com/2011/02/16/how-to-install-psycopg2-under-virtualenv/) 
and [here](http://stackoverflow.com/questions/11618898/pg-config-executable-not-found)
`sudo apt-get install libpq-dev python3-dev`

+ use pip to install any needed python libraries
`sudo pip install psycopg2`
`sudo pip install datetime`

# setting up an aws ubuntu instance as a web server
+ go back to aws main menu and choose to launch an EC2 instance. 
+ *choose Ubuntu!*. if you choose amazon linux, you're gonna have a bad time :-(
+ choose all the free options. note- you can increase the storage up to 30GB for free. 
+ make the security group accept SSH from your IP address, and HTTP from any IP address (so that the world can see your website, but only you can SSH to the server)
+ follow the steps to create a private key (.pem file). then use puttygen to convert to putty format (.ppk file). 
+ SSH into your instance using the public DNS as the host (available on the EC2 console), and the private key file you generated. the user name is `ubuntu`
+ run the following code to set up the instance to host a webpage that runs php:
	- run updates 
	`sudo apt-get update` 
	
	- install apache server 
	`sudo apt-get install apache2`

	- set apache to start automatically on boot 
	`sudo update-rc.d apache2 enable`
	
	- install php5 
	`sudo apt-get install php5`
	
	- install postgres extension for php  
	`sudo apt-get install php5-pgsql`
	
	- restart apache 
	`sudo /etc/init.d/apache2 restart`
	
	- Then check your website (public dns, example `ec2-54-173-188-49.compute-1.amazonaws.com`
	It should have a page saying 'Apache ubuntu default page' - Yay! Apache is running on your server 
	
	- if you want to password protect your site, do the following 
		- install utility to make password protected sites 
		`sudo apt-get install apache2 apache2-utils`
		
		- make a password file 
		`sudo htpasswd -c /etc/apache2/.htpasswd ubuntu` 
		note, for the first user we create (the ubuntu user) we need to use the -c option. 
		
		- lets add a new user, so that people dont need to use the ubuntu user name to view the website. we'll call this user datauser. 
		`sudo adduser datauser` 
		
		- now you need to give datauser log in permission to the website. use the same command, dropping the `-c` and replace `ubuntu` with `datauser`
		`sudo htpasswd /etc/apache2/.htpasswd datauser` 
		
		- you can do 
		`cat /etc/apache2/.htpasswd`
		to see the contents of the password file. note the password is encypted, ie it will not appear as you had typed it  
		
		- open the file `/etc/apache2/sites-enabled/000-default.config`. we need to tell apache to look in the config file for the password 
		- add this block somewhere inside the `<VirtualHost>` block. if you would like to restrict the entire website, use `/var/www/html` as the directory (as shown). if only a particular folder, use that folder instead.
``` 
		    <Directory "/var/www/html">
		        AuthType Basic
		        AuthName "Restricted Content"
		        AuthUserFile /etc/apache2/.htpasswd
		        Require valid-user
		    </Directory>
```
		
+ restart apache 
`sudo service apache2 restart `
+ navigate to `/var/www/html/` and remove or rename the default `index.html` file. you can now put your `index.html` or `index.php` file, and any other website files here. make sure that the folder has permissions 755 `sudo chmod 755 /var/www/html/`

## connect the website to your rds
+ take note of the private IP of your ubuntu instance (availble in the AWS console for EC2).
+ return to the security group settings of the RDS. add a new rule to accept postgresql requests from the ubuntu instance private IP address
+ now you can use the `pg_connect()` function within your php code to connect to your RDS
+ see the [`index.php`](https://github.com/ampetr/aws-leaflet/blob/master/www/index.php) file on this repo for examples connecting to a database, returning results as json, and plotting with leaflet!
