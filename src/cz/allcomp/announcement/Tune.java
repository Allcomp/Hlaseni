package cz.allcomp.announcement;

public class Tune {
	private final int id;
	private final String name;
	private final String description;
	private final String file;
	
	public Tune(int id, String name, String description, String file) {
		super();
		this.id = id;
		this.name = name;
		this.description = description;
		this.file = file;
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
}
