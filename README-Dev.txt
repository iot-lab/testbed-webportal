In order to install the webportal locally, follow the step below:

- install apache, php and php-gd
- configure php for GD library
- mkdirs /senslab/experiments/ and add this path to "open_basedir" variable in php.ini
- configure apache for mod_ssl, mod_proxy, and mod_proxy_http
- copy this in /etc/httpd/conf/httpd.conf (change it to refere to your system)

Listen:80
DocumentRoot "/path/to/webportal"
SSLProxyEngine On
RequestHeader set Front-End-Https "On"
ProxyPass /rest https://localhost/rest
ProxyPassReverse /rest https://localhost/rest
CacheDisable *
<Directory "/path/to/webportal">
    Options Indexes Includes FollowSymLinks
    AllowOverride All
    Order allow,deny
    Allow from all
    XBitHack on
</Directory>
    
- create an SSH tunnel for 443 port: "ssh -L 443:srvwww:443 root@<main-senslab-server> -p 2222"