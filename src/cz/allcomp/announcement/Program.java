package cz.allcomp.announcement;

import java.io.IOException;

import javax.sound.sampled.LineUnavailableException;
import javax.sound.sampled.UnsupportedAudioFileException;

public class Program {

	private static Announcements announcements;
	
	public static void main(String[] args) throws UnsupportedAudioFileException, IOException, LineUnavailableException, InterruptedException {
		announcements = new Announcements();  
		announcements.start();
	}

}
