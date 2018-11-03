# chatstack
a chat app using laravel redis node socket.io stack

## Installing
 1. cd to the client folder and run `npm instal` ( if there is no node in your machine install it).
 2. cd to the node folder and run `npm install`.
 3. cd to the server folder and run  `composer update --no-dev` ( if there is no composer in your machine install it).
 4. create a databse and edit the config for seting the database name.
 5. run `php artisan migrate`.
 6. go to your database and add users .
 7. redis should be installed and running at port 6379 you can change the port in the .env file .

 Now Every thing should be ready

### running the project

1. cd to the client and run `npm run dev` or `npm run build`.
2. cd to the node and `run node index.js`.
2. cd to the server and run `php artisan serve`.

Now You can open your localhost:8080 if you press the chat in the menu you go to the chat screen<br>
enter one of the names that you used in adding the user in the database so you can chat with other pepole


# ABOUT
i build this project to applay realtime chat app with laravel backend and socket.io as we know we can not use socket.io without node<br>
and using php socket is so complicated there is a serise in laracast [Real time with laravel socket IO](https://laracasts.com/series/real-time-laravel-with-socket-io).<br>
from this serise i got the idea of building this kinde of app <br>
it is so complcated i used laravel 5.4 and enabled event brodcating with [Redis](https://redis.io/topics/introduction) so i can make connection with the node<br>
and the node with sockec.io will do the rest by connection with the client app (written in vuejs)
in the client app after i login i lestin to the socket that comes from the node side after firing from laravel 
so i have this stack for a chat app backbone 
## For any Informayion Plase Contact me at `hazeem.arian@gmail.com`