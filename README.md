Add this to your httpd-vhosts.conf file<br/>
&lt;VirtualHost *:80&gt; <br />
    ServerName seannolansguestbook.com <br />
    DocumentRoot "C:/xampp/htdocs/guestbook" <br />
    SetEnv APPLICATION_ENV "development" <br />
    &lt;Directory "C:/xampp/htdocs/guestbook"&gt; <br />
        DirectoryIndex index.html <br />
        AllowOverride All <br />
        Order allow,deny <br />
        Allow from all <br />
    &lt;/Directory&gt; <br />
&lt;/VirtualHost&gt; <br />
 <br /> <br />
where DocumentRoot and Directory are where the project has been downloaded to. <br /> <br />

Also add this to your hosts file <br />
127.0.0.1 seannolansguestbook.com

Create the database called guestbook and then create the table using the entries.sql file.  Also create a user called guestbook with the password guestbook.
