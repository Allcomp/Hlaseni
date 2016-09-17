package cz.allcomp.announcement;

public class Announcement {

	private final int id;
	private final String name;
	private final String description;
	private final int tune;
	private final int recording;
	private final long time;
	
	public Announcement(int id, String name, String description, int tune, int recording, long time) {
		super();
		this.id = id;
		this.name = name;
		this.description = description;
		this.tune = tune;
		this.recording = recording;
		this.time = time;
	}

	public int getId() {
		return id;
	}

	public String getName() {
		return name;
	}

	public String getDescription() {
		return description;
	}

	public int getTune() {
		return tune;
	}

	public int getRecording() {
		return recording;
	}

	public long getTime() {
		return time;
	}
}
