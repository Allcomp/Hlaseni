package cz.allcomp.announcement;

import java.io.File;
import java.io.IOException;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

import javax.sound.sampled.LineUnavailableException;
import javax.sound.sampled.UnsupportedAudioFileException;

import cz.allcomp.shs.database.SqlCommands;
import cz.allcomp.shs.database.StableMysqlConnection;
import cz.allcomp.shs.logging.Messages;
import cz.allcomp.shs.util.Time;

public class AnnouncementsManager implements Runnable {
	
	private boolean running, shouldStop;
	private Thread routineThread;
	
	private StableMysqlConnection database;
	
	private List<Announcement> announcements;
	private List<Recording> recordings;
	private List<Tune> tunes;
	
	private final String webPath;
	private final long tuneRecordingPause;
	private final int databaseUpdateTicks;
	private Announcements announcementsObject;
	
	private int defaultTuneLiveAnnouncement;
	
	private boolean playing;
	
	private int indexPointer;
	
	public AnnouncementsManager(StableMysqlConnection database, Announcements announcementsObject, String webPath, long tuneRecordingPause, int databaseUpdateTicks) {
		this.routineThread = new Thread(this);
		this.running = false;
		this.shouldStop = true;
		this.playing = false;
		this.database = database;
		this.announcementsObject = announcementsObject;
		this.announcements = new ArrayList<>();
		this.recordings = new ArrayList<>();
		this.tunes = new ArrayList<>();
		this.indexPointer = 0;
		if(!webPath.endsWith("/"))
			webPath += "/";
		this.webPath = webPath;
		this.tuneRecordingPause = tuneRecordingPause;
		this.databaseUpdateTicks = databaseUpdateTicks;
		this.defaultTuneLiveAnnouncement = -1;
	}

	@Override
	public void run() {
		if(this.running) {
			Messages.warning("AnnouncementsManager has already started.");
			return;
		}
		
		this.shouldStop = false;
		this.running = true;
		
		Messages.info("Initializing default database settings...");
		try {
			this.databaseInit();
		} catch (SQLException e2) {
			Messages.warning(Messages.getStackTrace(e2));
		}
		
		Messages.info("AnnouncementsManager started.");
		GPIOManager gpioManager = this.announcementsObject.getGPIOManager();
		
		int loopCounter = -1;
		while(!this.shouldStop) {
			loopCounter++;
			
			try {
				Thread.sleep(1000);
			} catch (InterruptedException e) {
				Messages.warning(Messages.getStackTrace(e));
			}
			
			if(loopCounter % this.databaseUpdateTicks == 0)
				try {
					this.reloadData();
				} catch (SQLException e1) {
					Messages.error(Messages.getStackTrace(e1));
				}
			
			if(this.playing)
				continue;
			
			while(true) {
				if(this.announcements.size() == 0)
					break;
				if(this.indexPointer > this.announcements.size()-1)
					break;
				Announcement a = this.announcements.get(this.indexPointer);
				if(a.getTime() < Time.getTime().getTimeStamp())
					this.indexPointer++;
				else
					break;
			}
			
			if(this.indexPointer > this.announcements.size()-1)
				continue;
			
			Announcement a = this.announcements.get(this.indexPointer);
			
			if(a.getTune() != 0)
				if(this.getTune(a.getTune()) == null) {
					Messages.warning("<AnnouncementsManager> The announcement '" + a.getName() + "' contains invalid tune!");
					this.indexPointer++;
					continue;
				}
			
			Tune tune = this.getTune(a.getTune());
			File tuneFile = null;
			String tuneFilePath = "";
			if(tune != null) {
				tuneFilePath = this.webPath + "tunes/" + tune.getFile();
				tuneFile = new File(tuneFilePath);
				
				if(!tuneFile.exists()) {
					this.indexPointer++;
					continue;
				}
			}
			
			if(this.getRecording(a.getRecording()) == null) {
				Messages.warning("<AnnouncementsManager> The announcement '" + a.getName() + "' contains invalid recording!");
				this.indexPointer++;
				continue;
			}
			
			String recordingFilePath = this.webPath + "records/" + this.getRecording(a.getRecording()).getFile();
			File recordingFile = new File(recordingFilePath);
			
			if(!recordingFile.exists()) {
				this.indexPointer++;
				continue;
			}
			
			double tuneDurationSecs = 0;
			if(tune != null)
				try {
					tuneDurationSecs = SoundsManager.getWavFileDuration(tuneFilePath);
				} catch (LineUnavailableException | IOException | UnsupportedAudioFileException e) {
					Messages.warning(Messages.getStackTrace(e));
					this.indexPointer++;
					continue;
				}
			
			long startTime = 
					a.getTime() 
					- (long)(tuneDurationSecs*1000) - 
					this
					.tuneRecordingPause - 
					gpioManager
					.getPowerPause() - 
					gpioManager
					.getEnablePause();
			if(tune == null)
				startTime += this.tuneRecordingPause;
			
			long printTime = (startTime - Time.getTime().getTimeStamp())/1000;
			if(printTime > 0)
				Messages.info("Announcement '"+a.getName()+"' will start in " + (printTime+(gpioManager.getPowerPause()+gpioManager.getEnablePause())/1000) + " seconds.");
			else
				Messages.info("Announcement '"+a.getName()+"' will start in the moment.");
			
			if(startTime - Time.getTime().getTimeStamp() < 1000) {
				this.indexPointer++;
				final String finalTunePath = tuneFilePath;
				new Thread(()->{
					this.playing = true;
					Messages.info("<AnnouncementsManager> Enabling amplifier...");
					gpioManager.useAmplifier();
					Messages.info("<AnnouncementsManager> Starting to play '" + a.getName() + "'...");
					if(tune != null) {
						SoundsManager.playWavFile(finalTunePath);
						try {
							Thread.sleep(this.tuneRecordingPause);
						} catch (Exception e) {
							Messages.warning(Messages.getStackTrace(e));
						}
					}
					SoundsManager.playWavFile(recordingFilePath);
					Messages.info("<AnnouncementsManager> Playing finished.");
					Messages.info("<AnnouncementsManager> Disabling amplifier...");
					gpioManager.unuseAmplifier();
					Messages.info("<AnnouncementsManager> Amplifier disabled.");
					this.playing = false;
				}).start();
			}
		}
		
		Messages.info("AnnouncementsManager stopped.");
		this.running = false;
	}
	
	private void databaseInit() throws SQLException {
		ResultSet rs = this.database.executeQuery(SqlCommands.LOAD_DEFAULT_TUNE_LIVE_ANNOUNCEMENT);
		while (rs.next()) {
			this.defaultTuneLiveAnnouncement = rs.getInt("value");
			break;
		}
		if(this.defaultTuneLiveAnnouncement == -1) {
			this.defaultTuneLiveAnnouncement = 0;
			this.database.executeUpdate(SqlCommands.INIT_DEFAULT_TUNE_LIVE_ANNOUNCEMENT);
		}
	}
	
	private void reloadData() throws SQLException {
		Messages.info("<AccouncementsManager> Reloading data...");
		
		this.announcements.clear();
		this.recordings.clear();
		this.tunes.clear();
		this.indexPointer = 0;
		
		int startID_tunes = Integer.MAX_VALUE;
		int startID_recordings = Integer.MAX_VALUE;
		
		ResultSet rs = this.database.executeQuery(SqlCommands.LOAD_DEFAULT_TUNE_LIVE_ANNOUNCEMENT);
		while (rs.next()) {
			this.defaultTuneLiveAnnouncement = rs.getInt("value");
			startID_tunes = this.defaultTuneLiveAnnouncement;
			break;
		}
		
		rs = this.database.executeQuery(SqlCommands.LOAD_ANNOUNCEMENTS
				.replace("%time%", (Time.getTime().getTimeStamp()-10000-this.databaseUpdateTicks)+""));
		while(rs.next()) {
			int id = rs.getInt("id");
			String name = rs.getString("name");
			String description = rs.getString("description");
			int tune = rs.getInt("tune");
			int recording = rs.getInt("recording");
			long time = rs.getLong("time");
			if(tune < startID_tunes)
				startID_tunes = tune;
			if(recording < startID_recordings)
				startID_recordings = recording;
			this.announcements.add(new Announcement(id,name,description,tune,recording,time));
		}
		
		rs = this.database.executeQuery(
			SqlCommands.LOAD_RECORDINGS
			.replace("%start_id%", startID_recordings+"")); 
		while(rs.next()) {
			int id = rs.getInt("id");
			String name = rs.getString("name");
			String description = rs.getString("description");
			String file = rs.getString("file");
			long time = rs.getLong("time");
			this.recordings.add(new Recording(id,name,description,file,time));
		}
		
		rs = this.database.executeQuery(
			SqlCommands.LOAD_TUNES
			.replace("%start_id%", startID_tunes+"")); 
		while(rs.next()) {
			int id = rs.getInt("id");
			String name = rs.getString("name");
			String description = rs.getString("description");
			String file = rs.getString("file");
			this.tunes.add(new Tune(id,name,description,file));
		}

		Messages.info("<AccouncementsManager> Loaded " + this.tunes.size() + " tunes.");
		Messages.info("<AccouncementsManager> Loaded " + this.recordings.size() + " recordings.");
		Messages.info("<AccouncementsManager> Loaded " + this.announcements.size() + " announcements.");
		
		Tune deftune = this.getTune(this.defaultTuneLiveAnnouncement);
		if(deftune == null)
			Messages.info("<AccouncementsManager> No tune for live announcement has been set.");
		else
			Messages.info("<AccouncementsManager> Tune '" + deftune.getName() + "' is now set for live announcement.");
	}
	
	public void setPlaying(boolean val) {
		this.playing = val;
	}
	
	public Process playDefaultTuneForLiveAnnouncement() {
		Tune tune = this.getTune(this.defaultTuneLiveAnnouncement);
		if(tune != null) {
			String tuneFilePath = this.webPath + "tunes/" + tune.getFile();
			File tuneFile = new File(tuneFilePath);
			
			if(!tuneFile.exists())
				return null;
			
			return SoundsManager.playWavFileGetRuntime(tuneFilePath);
		}
		return null;
	}
	
	public double getDefaultTuneForLiveAnnouncementDuration() throws LineUnavailableException, IOException, UnsupportedAudioFileException {
		Tune tune = this.getTune(this.defaultTuneLiveAnnouncement);
		if(tune == null)
			return 0;
		String tuneFilePath = this.webPath + "tunes/" + tune.getFile();
		File tuneFile = new File(tuneFilePath);
		
		if(!tuneFile.exists())
			return 0;
		
		return SoundsManager.getWavFileDuration(tuneFilePath);
	}
	
	private Tune getTune(int id) {
		for(Tune t : this.tunes)
			if(t.getId() == id)
				return t;
		return null;
	}
	
	private Recording getRecording(int id) {
		for(Recording r : this.recordings)
			if(r.getId() == id)
				return r;
		return null;
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
		Messages.info("Trying to start AnnouncementsManager...");
		if(!this.running) {
			this.routineThread = null;
			this.routineThread = new Thread(this);
			this.routineThread.start();
		}
	}

	
}
