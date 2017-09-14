Add this to your httpd-vhosts.conf file
<VirtualHost *:80>
    ServerName seannolansguestbook.com
    DocumentRoot "C:/xampp/htdocs/guestbook"
    SetEnv APPLICATION_ENV "development"
    <Directory "C:/xampp/htdocs/guestbook">
        DirectoryIndex index.html
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>

where DocumentRoot is where the project has been downloaded to.

Also add this to your hosts file
127.0.0.1 seannolansguestbook.com
