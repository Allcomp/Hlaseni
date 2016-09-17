/**
 * Copyright (c) 2015, Samuel Trávníček
 * All rights reserved.
 * 
 * Any use, modification or implementation of this source code in your 
 * own or third-party applications without permission is prohibited.
 */

package cz.allcomp.shs.database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

import cz.allcomp.shs.logging.Messages;

/**
 * Makes connection to MySQL database and checks on every query if the connection is not broken. 
 * If it is then the connection is restored.
 * 
 * @author Samuel Trávníček
 */
public class StableMysqlConnection {
	
	/**
	 * The number of attemts to connect to MySQL database if the connection is broken.
	 */
	private static final int NUM_RETRIES_ON_ERROR = 3;
	
	/**
	 * The default JDBC's Connection class's object.
	 */
	private Connection connection;
	
	/**
	 * Used for executing query.
	 */
	private PreparedStatement lastStatement;
	
	/**
	 * Parameters used for connection to MySQL database.
	 */
	private String host, user, name, pass;
	
	/**
	 * MySQL database connection port
	 */
	private int port;
	
	/**
	 * Number of remaining attempts to connect to MySQL database if the connection is broken.
	 * Resets with last attempt or with succesful connection.
	 */
	private int remainingRetries;
	/**
	 * Makes new object and makes connection to MySQL database.
	 * 
	 * @param host The MySQL server hostname
	 * @param user The username used to access database
	 * @param pass The password used to access database
	 * @param name The name of the database
	 * @param port The port used by MySQL server
	 */
	public StableMysqlConnection(String host, String user, String pass,
			String name, int port) {
		super();
		this.host = host;
		this.user = user;
		this.name = name;
		this.pass = pass;
		this.port = port;
                
		this.renewRemainingRetries();
		this.lastStatement = null;
		this.connection = null;
		
		this.makeNewConnection();
	}
	
	/**
	 * Sets the port to 3306 and call another constructor
	 * 
	 * @see StableMysqlConnection#StableMySQLConnection
	 * @param host
	 * @param user
	 * @param pass
	 * @param name
	 */
	public StableMysqlConnection(String host, String user, String pass,
			String name) {
		this(host, user, pass, name, 3306);
	}
	
	/**
	 * Executes query.
	 * Checks for the MySQL connection and if it is broken, the method tries to renew it.
	 * 
	 * @param sql SQL string to execute
	 * @return Set of results returned by MySQL database
	 */
	public ResultSet executeQuery(String sql) {
                if(this.connection == null) {
                    Messages.error("Could not execute query: connection is null!");
                    if(this.remainingRetries != 0) {
                            this.remainingRetries--;
                            Messages.info("Trying to repair connection...");
                            this.makeNewConnection();
                            this.executeQuery(sql);
                    } else {
                            this.renewRemainingRetries();
                            Messages.error("Fatal error connectivity! "
                                            +"It is no longer applicable.");
                    }
                    return null;
                }
		try {
                        this.closeLastStatement();
			this.lastStatement = this.connection.prepareStatement(sql);
			try {
				ResultSet rs = this.lastStatement.executeQuery();
				this.renewRemainingRetries();
				return rs;
			} catch(Exception e) {
				Messages.error("Could not execute query:\n> " + sql
						+ "\n> " + e.getMessage());
				if(this.remainingRetries != 0) {
					this.remainingRetries--;
					Messages.info("Trying to repair connection...");
					this.makeNewConnection();
					this.executeQuery(sql);
				} else {
					this.renewRemainingRetries();
					Messages.error("Fatal error connectivity! "
							+"It is no longer applicable.");
                    Messages.error(Messages.getStackTrace(e));
				}
				return null;
			}
		} catch (Exception e) {
			Messages.error("Could not prepare new statement.\n> "
					+ e.getMessage());
			if(this.remainingRetries != 0) {
				this.remainingRetries--;
				Messages.info("Trying to repair connection...");
				this.makeNewConnection();
				this.executeQuery(sql);
			} else {
				this.renewRemainingRetries();
				Messages.error("Fatal error connectivity! "
						+"It is no longer applicable.");
                Messages.error(Messages.getStackTrace(e));
			}
			return null;
		}
	}
	
	/**
	 * Executes query.
	 * Checks for the MySQL connection and if it is broken, the method tries to renew it.
	 * 
	 * @param sql SQL string to execute
	 * @return Number of affected rows in database
	 */
	public int executeUpdate(String sql) {
                if(this.connection == null) {
                    Messages.error("Could not execute query: connection is null!");
                    if(this.remainingRetries != 0) {
                            this.remainingRetries--;
                            Messages.info("Trying to repair connection...");
                            this.makeNewConnection();
                            this.executeQuery(sql);
                    } else {
                            this.renewRemainingRetries();
                            Messages.error("Fatal error connectivity! "
                                            +"It is no longer applicable.");
                    }
                    return 0;
                }
		try {
                        this.closeLastStatement();
			this.lastStatement = this.connection.prepareStatement(sql);
			try {
				int res = this.lastStatement.executeUpdate();
				this.renewRemainingRetries();
				return res;
			} catch (Exception e) {
				Messages.error("Could not execute update:\n> " + sql
						+ "\n> " + e.getMessage());
				if(this.remainingRetries != 0) {
					this.remainingRetries--;
					Messages.info("Trying to repair connection...");
					this.makeNewConnection();
					this.executeUpdate(sql);
				} else {
					this.renewRemainingRetries();
					Messages.error("Fatal error connectivity! "
							+"It is no longer applicable.");
                    Messages.error(Messages.getStackTrace(e));
				}
				return 0;
			}
		} catch (Exception e) {
			Messages.error("Could not prepare new statement.\n> "
					+ e.getMessage());
			if(this.remainingRetries != 0) {
				this.remainingRetries--;
				Messages.info("Trying to repair connection...");
				this.makeNewConnection();
				this.executeUpdate(sql);
			} else {
				this.renewRemainingRetries();
				Messages.error("Fatal error connectivity! "
						+"It is no longer applicable.");
                Messages.error(Messages.getStackTrace(e));
			}
			return 0;
		}
	}
	
	/**
	 * Method used by class. Resets counter for remaining tries to renew connection if it is broken.
	 */
	private void renewRemainingRetries() {
		this.remainingRetries = NUM_RETRIES_ON_ERROR;
	}
	
	/**
	 * Tries to make connection with MySQL database. 
	 * If any connection exists, it will close current connection and makes the new one.
	 */
	public void makeNewConnection() {
		try {
                        this.closeLastStatement();
			if(!(this.connection == null || this.connection.isClosed()))
				this.connection.close();
			
			this.connection = DriverManager.getConnection(
				"jdbc:mysql://"+this.host+":"+this.port+"/"+this.name, 
				this.user, this.pass
			);
			if(connection.isClosed())
				System.out.println("neok");
			else
				System.out.println("ok");
		} catch (Exception e) {
			Messages.error("SQL Error occured:\n> " + e.getMessage());
            Messages.error(Messages.getStackTrace(e));
		}
	}
	
	/**
	 * Tries to close connection to MySQL database.
	 */
	public void closeConnection() {
		try {
			this.connection.close();
		} catch (Exception e) {
			Messages.warning("Could not close MySQL connection. "
					+"It might have already been closed before.\n> "
					+ e.getMessage());
            Messages.error(Messages.getStackTrace(e));
		}
	}
	
	/**
	 * Tries to close last used statement.
	 */
	private void closeLastStatement() {
		if(this.lastStatement != null) {
			try {
				if(!this.lastStatement.isClosed())
					this.lastStatement.close();
			} catch (Exception e) {
				Messages.warning("Could not close last statement.\n> "
						+ e.getMessage());
                Messages.error(Messages.getStackTrace(e));
			}
		}
	}
	
	/**
	 * @return Last used statement
	 */
	public PreparedStatement getLastStatement() {
		return this.lastStatement;
	}

	/**
	 * @return Host address of the MySQL database
	 */
	public String getHost() {
		return this.host;
	}

	/**
	 * @return Username used to access the database
	 */
	public String getUser() {
		return this.user;
	}

	/**
	 * @return Name of the database
	 */
	public String getName() {
		return this.name;
	}

	/**
	 * @return Password used to access the database
	 */
	public String getPass() {
		return this.pass;
	}

	/**
	 * @return Port used by MySQL server
	 */
	public int getPort() {
		return this.port;
	}
}
