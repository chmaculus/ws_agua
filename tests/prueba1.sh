rm ../../ws_agua/log/*

#url='http://localhost/ws_agua/prb1.php';
#url="http://10.231.45.108/ws_agua/webservice.php"
url='http://localhost/ws_agua/webservice.php';

out='../ws_agua/log/16j.json';

#destino='/mnt/c/xampp_1-7-7/htdocs/ws_agua/log/a1b16j.json'

destino='./a1b3j.json'

#curl -X POST "http://localhost/ws_agua/prb1.php" -F 'a=1' -F 'b=16j' -F data=@16j.json >return.txt

#curl --header "Content-Type: application/json" --data @prueba1.sh http://localhost/ws_agua/prb1.php

#curl -X POST "http://localhost/ws_agua/prb1.php" -F 'a=1' -F 'b=16j' -F data=@16j.json 

#echo "data: `cat 16j.json`" >temp1.txt


#a1 corresponde a agua 3j a require 3j
curl -X POST $url -F 'a=1' -F 'b=3j' -F 'r=1' >$destino



#ok
#curl -X POST $url -d @16j.json

#funca a medias
#curl -X POST $url -d "a=1" -d "b=16j" -d @16j.json >$out


#curl -X POST $url -d @16j.json >$out
   

#curl -X POST "http://10.231.45.108/ws_agua/webservice.php" -F 'a=1' -F 'b=3' -F 'r=a,b' 
#curl -X POST "http://10.231.45.108/ws_agua/webservice.php" -F 'a=1' -F 'b=16j' >/mnt/c/xampp_1-7-7/htdocs/ws_agua/log/a1b16j.json




#curl -X POST $url -d @16j.json -F 'a=1' -F 'b=16j' 



