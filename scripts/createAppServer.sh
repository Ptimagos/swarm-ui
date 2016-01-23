#! /bin/bash 
servers=$@
machine_opt=""
docker_opt=""
master_server="master1 master2 master3"
dns=""

for master_serv in $master_server
do
  machine_ip=`docker-machine ls | grep $master_serv | awk '{print $5}' | awk -F"/" '{print $3}' | awk -F":" '{print $1}'`
  dns="$dns --dns $machine_ip"
  master_ip=$machine_ip
done

echo "###########################################"
echo "Create VM to docker :"
for serv in $servers
do
  echo "-------------------------------------------"
  echo "Create Docker machine $serv ..."
  docker-machine create -d virtualbox $machine_opt $serv
done

echo "###########################################"
echo "Install Swarm and start agent :"

for serv in $servers
do
  echo "-------------------------------------------"
  echo "Get Swarm image on $serv ..."
  eval "$(docker-machine env $serv)"
  docker pull swarm
  machine_ip=`docker-machine ls | grep $serv | awk '{print $5}' | awk -F"/" '{print $3}' | awk -F":" '{print $1}'`
  echo "Ip public for $serv : - $machine_ip -"
  echo "Start Swarm agent on $serv ..."
  docker run -d --name=swarm-agent $dns --dns 8.8.8.8 --dns-search service.consul swarm join --addr $machine_ip:2376 consul://consul:8500
done

