FROM node:latest

RUN npm install udp-discovery
RUN npm install dockerode

COPY udpDiscovery.js /opt/res/
#COPY /var/run/docker.sock /opt/res/docker.sock

#EXPOSE 8000

CMD ["node", "/opt/res/udpDiscovery.js"]