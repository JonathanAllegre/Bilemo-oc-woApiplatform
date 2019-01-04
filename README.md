# Bilemo-OC -- How to Install  

The 7th project of the OpenClassRoom training: PHP / Symfony application developer  

## Getting Started  
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.  

### Prerequisites  
 - php 7.2  
 - composer: https://getcomposer.org 
 
### Installing  
  
 #### Clone Or Download the project
 - Open your command shell prompt and enter:
 
	> git clone https://github.com/JonathanAllegre/Bilemo-oc-woApiplatform.git
	
 - Move in your folder application
 
 - Open folder with your favorite editor
 
  #### Configuration
  - Replace the file ".env.dist"  with ".env"
  - Replace value of DATABASE_URL by your own configuration.
  - Run:
 	 > composer install
 
  #### Database Configuration
  - Run:
 	 > php bin/console doctrine:database:create  
 	 > php bin/console doctrine:schema:create
 	 
  #### DataFixtures
  - Run:
 	> php bin/console doctrine:fixture:load
 	
 - Enter "Y"
 
 ## Little thing to know
 
 The API doc is available here : http://bilemo-oc.fam8.net/api/doc/
    
 Enjoy !