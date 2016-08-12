package cz.allcomp.announcement;

import java.io.File;
import java.io.IOException;

import javax.sound.sampled.AudioSystem;
import javax.sound.sampled.Clip;
import javax.sound.sampled.LineUnavailableException;
import javax.sound.sampled.UnsupportedAudioFileException;

import cz.allcomp.shs.logging.Messages;

public class SoundsManager {
	
	public static double getWavFileDuration(String path) throws LineUnavailableException, IOException, UnsupportedAudioFileException {
		/*File file = new File(path);
		AudioInputStream audioInputStream = AudioSystem.getAudioInputStream(file);
		AudioFormat format = audioInputStream.getFormat();
		long frames = audioInputStream.getFrameLength();
		double durationInSeconds = (frames+0.0) / format.getFrameRate();
		return durationInSeconds;*/
		File file = new File(path);
		Clip clip = AudioSystem.getClip();
		clip.open(AudioSystem.getAudioInputStream(file));
		double duration = (double)clip.getMicrosecondLength()/1000;
		duration /= 1000.0;
		clip.drain();
		clip.close();
		return duration;
	}
	
	/*public static void playWavFile(String path) throws LineUnavailableException, IOException, UnsupportedAudioFileException, InterruptedException {
		File file = new File(path);
		Clip clip = AudioSystem.getClip();
		clip.open(AudioSystem.getAudioInputStream(file));
		FloatControl gainControl = (FloatControl) clip.getControl(FloatControl.Type.MASTER_GAIN);
		double gain = 1;   
		float dB = (float) (Math.log(gain) / Math.log(10.0) * 20.0);
		gainControl.setValue(dB);
		BooleanControl muteControl = (BooleanControl) clip
		        .getControl(BooleanControl.Type.MUTE);
		    muteControl.setValue(true);

		    muteControl.setValue(false);
		clip.start();
		Thread.sleep(clip.getMicrosecondLength()/1000);
		clip.drain();
		clip.close();
	}*/
	
	public static void playWavFile(String path) {
		Runtime r = Runtime.getRuntime();
		try {
			r.exec("omxplayer " + path);
			Thread.sleep((long)(getWavFileDuration(path)*1000));
		} catch (IOException e) {
			Messages.error("Could not play file " + path + "!");
			Messages.error(Messages.getStackTrace(e));
		} catch (InterruptedException e) {
			Messages.error(Messages.getStackTrace(e));
		} catch (LineUnavailableException e) {
			Messages.error("Could not play file " + path + "!");
			Messages.error(Messages.getStackTrace(e));
		} catch (UnsupportedAudioFileException e) {
			Messages.error("Could not play file " + path + "!");
			Messages.error(Messages.getStackTrace(e));
		}
	}
}
