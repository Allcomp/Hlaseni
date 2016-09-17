package cz.allcomp.announcement;

import java.io.IOException;

import com.pi4j.io.i2c.I2CFactory.UnsupportedBusNumberException;

import cz.allcomp.shs.cfg.Configuration;
import cz.allcomp.shs.database.StableMysqlConnection;
import cz.allcomp.shs.logging.Messages;

public class Announcements implements Runnable {

	public static final String[] VERSION_NAMES = {"Radion"};
	
	public static final String VERSION_NAME = VERSION_NAMES[0];
	public static final String VERSION = "1.1";
	
	private boolean running, shouldStop;
	private Thread routineThread;
	
	private Configuration mainConfig;
	private Configuration databaseConfig;
	
	private StableMysqlConnection database;
	private AnnouncementsManager announcementsManager;
	private GPIOManager gpioManager;
	
	public Announcements() {
		this.routineThread = new Thread(this);
		this.running = false;
		this.shouldStop = true;
		this.loadConfig();
		this.setupMessages();
		
		Runtime.getRuntime().addShutdownHook(new Thread() {
			public void run() {
				gpioManager.shutdown();
			}
		});
	}
	
	@Override
	public void run() {
		if(this.running) {
			Messages.warning("Announcement system has already started.");
			return;
		}
		
		this.shouldStop = false;
		try {
			this.init();
		} catch (NumberFormatException | UnsupportedBusNumberException | IOException e) {
			Messages.error(Messages.getStackTrace(e));
		}
		this.running = true;
		
		this.announcementsManager.start();
		Messages.info("Announcements system started.");
		
		while(!this.shouldStop);
		
		this.announcementsManager.signalStop();
		Messages.info("Announcements system terminated.");
		
		this.running = false;
	}
	
	private void init() throws NumberFormatException, UnsupportedBusNumberException, IOException {
		Messages.info("Announcements system version: " + VERSION_NAME + " " + VERSION + "...");
		Messages.info("Establishing connection to MySQL database");
		Messages.info(">> host: " + this.databaseConfig.get("host"));
		Messages.info(">> user: " + this.databaseConfig.get("user"));
		Messages.info(">> password: " + this.databaseConfig.get("password"));
		Messages.info(">> name: " + this.databaseConfig.get("name"));
		Messages.info(">> port: " + Integer.parseInt(this.databaseConfig.get("port")));
		this.database = new StableMysqlConnection(
			this.databaseConfig.get("host"), 
			this.databaseConfig.get("user"), 
			this.databaseConfig.get("password"), 
			this.databaseConfig.get("name"), 
			Integer.parseInt(this.databaseConfig.get("port"))
		);

		this.announcementsManager = new AnnouncementsManager(this.database, this, 
				this.mainConfig.get("web_path"), 
				Long.parseLong(this.mainConfig.get("tune_recording_pause")),
				Integer.parseInt(this.mainConfig.get("database_update_ticks")));
		
		this.gpioManager = new GPIOManager(
				Long.parseLong(this.mainConfig.get("post_power_pause")), 
				Long.parseLong(this.mainConfig.get("post_enable_pause")),
				this
		);
	}
	
	public GPIOManager getGPIOManager() {
		return this.gpioManager;
	}
	
	public StableMysqlConnection getDatabase() {
		return this.database;
	}

	public void signalStop() {
		this.shouldStop = true;
	}
	
	public boolean isRunning() {
		return this.running;
	}
	
	public boolean isStopping() {
		return this.running && this.shouldStop;
	}

	public void start() {
		Messages.info("Trying to start Announcements system...");
		if(!this.running) {
			this.routineThread = null;
			this.routineThread = new Thread(this);
			this.routineThread.start();
		}
	}
	
	private void setupMessages() {
		Messages.info("Messages configuration:");
		try {
			Messages.SHOW_ERRORS = Boolean.parseBoolean(this.mainConfig.get("show_errors"));
			Messages.info("-> show errors: " + (Messages.SHOW_ERRORS ? "true" : "false"));
		} catch (Exception e) {}
		try {
			Messages.SHOW_WARNINGS = Boolean.parseBoolean(this.mainConfig.get("show_warnings"));
			Messages.info("-> show warnings: " + (Messages.SHOW_WARNINGS ? "true" : "false"));
		} catch (Exception e) {}
		try {
			Messages.SHOW_INFO = Boolean.parseBoolean(this.mainConfig.get("show_info"));
			Messages.info("-> show info: " + (Messages.SHOW_INFO ? "true" : "false"));
		} catch (Exception e) {}
		try {
			Messages.LOG_ERRORS = Boolean.parseBoolean(this.mainConfig.get("log_errors"));
			Messages.info("-> log errors: " + (Messages.LOG_ERRORS ? "true" : "false"));
		} catch (Exception e) {}
		try {
			Messages.LOG_WARNINGS = Boolean.parseBoolean(this.mainConfig.get("log_warnings"));
			Messages.info("-> log warnings: " + (Messages.LOG_WARNINGS ? "true" : "false"));
		} catch (Exception e) {}
		try {
			Messages.LOG_INFO = Boolean.parseBoolean(this.mainConfig.get("log_info"));
			Messages.info("-> log info: " + (Messages.LOG_INFO ? "true" : "false"));
		} catch (Exception e) {}
	}
	
	private void loadConfig() {
		this.mainConfig = new Configuration("config/main.cfg");
		this.databaseConfig = new Configuration("config/database.cfg");
	}
	
	public AnnouncementsManager getAnnouncementsManager() {
		return this.announcementsManager;
	}
}
