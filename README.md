Add this to your httpd-vhosts.conf file
<VirtualHost *:80> <br />
    ServerName seannolansguestbook.com <br />
    DocumentRoot "C:/xampp/htdocs/guestbook" <br />
    SetEnv APPLICATION_ENV "development" <br />
    <Directory "C:/xampp/htdocs/guestbook"> <br />
        DirectoryIndex index.html <br />
        AllowOverride All <br />
        Order allow,deny <br />
        Allow from all <br />
    </Directory> <br />
</VirtualHost> <br />
 <br /> <br />
where DocumentRoot is where the project has been downloaded to. <br /> <br />

Also add this to your hosts file <br />
127.0.0.1 seannolansguestbook.com
