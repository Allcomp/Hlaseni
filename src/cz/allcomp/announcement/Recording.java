package cz.allcomp.announcement;

import cz.allcomp.shs.util.Time;

public class Recording {

	private final int id;
	private final String name;
	private final String description;
	private final String file;
	private final long time;
	
	public Recording(int id, String name, String description, String file, long time) {
		super();
		this.id = id;
		this.name = name;
		this.description = description;
		this.file = file;
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

	public String getFile() {
		return file;
	}

	public Time getTime() {
		return Time.getTime(this.time);
	}
}
