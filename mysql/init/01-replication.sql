-- Create replication user
CREATE USER 'replicator'@'%' IDENTIFIED BY 'replication_password_here';
GRANT REPLICATION SLAVE ON *.* TO 'replicator'@'%';

-- Create WordPress database user with limited privileges
CREATE USER 'wordpress_user'@'%' IDENTIFIED BY 'wordpress_password_here';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, INDEX, LOCK TABLES ON wordpress_db.* TO 'wordpress_user'@'%';

-- Create monitoring user
CREATE USER 'monitor'@'%' IDENTIFIED BY 'monitor_password_here';
GRANT PROCESS, REPLICATION CLIENT, SELECT ON *.* TO 'monitor'@'%';

-- Flush privileges
FLUSH PRIVILEGES;
