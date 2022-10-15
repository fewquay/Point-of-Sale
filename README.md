# Point-of-Sale
POS simulator

There are three files for this POS simulator
1.  POS.php-This file is actually simulating the POS where you can put the commands

2.  POS_client.php-This is required by POS.php and makes the actual API calls

3.  POS_api.php-This is the API file and the url must be in the POS_client.php.  It is currently set as localhost
     but can be put on seperate server as it uses Curl.
     
Note:  POS.php is currently set to work without memcache and it passes the cart into total in one go.  If you have memcache
   on the test system you can remove the cart string from the total call and the previous scan() calls will build the cart
   with memcache.
  
Note2:  You can test the total functionality without scan or memcache by using Postman or the like and POSTing the url:
  http://localhost/POS_api.php/POS_Cached?function=total&cart=BCDABEAAA
