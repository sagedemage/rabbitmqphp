CHANGE REPLICATION SOURCE TO
/* Replace the 'source_server_ip' with the correct source VM IP address. */
SOURCE_HOST='source_server_ip',
SOURCE_USER='replica_admin',
SOURCE_PASSWORD='adminPass',
SOURCE_LOG_FILE='mysql-bin.000001',
SOURCE_LOG_POS=4;

/* Start DB Replication */
START REPLICA;
