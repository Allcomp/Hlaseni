package cz.allcomp.announcement;

import java.io.IOException;

import com.pi4j.io.gpio.GpioController;
import com.pi4j.io.gpio.GpioFactory;
import com.pi4j.io.i2c.I2CBus;
import com.pi4j.io.i2c.I2CDevice;
import com.pi4j.io.i2c.I2CFactory;
import com.pi4j.io.i2c.I2CFactory.UnsupportedBusNumberException;

import cz.allcomp.shs.logging.Messages;

public class GPIOManager {
	public static final byte AMPLIFIER_POWER_ON_DISABLED = 0b00100000;
	public static final byte AMPLIFIER_POWER_ON_ENABLED = 0b01100000;
	public static final byte AMPLIFIER_POWER_OFF = 0b00000000;
	
	private final GpioController gpio;

	//private final GpioPinDigitalOutput pinAmplifierPower;
	//private final GpioPinDigitalOutput pinAmplifierEnable;//, pinAmplifierEnable2;
	//private final GpioPinDigitalOutput pinLed;
	//private final GpioPinDigitalInput pinButton;
	
	//private final PCF8574GpioProvider i2cRegister;
	
	//private PinState lastButtonState;
	
	//private Announcements announcements;
	
	private final long powerPause, enablePause;
	private final I2CDevice i2cRegister;
	
	//private Process currentProcess;
	
	//private boolean enableLiveAnnouncement, buttonBlocked;
	
	public GPIOManager(long powerPause, long enablePause, Announcements announcements) throws UnsupportedBusNumberException, IOException {
		this.gpio = GpioFactory.getInstance();
		
		//this.pinAmplifierPower = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_00, "AmplifierPower", PinState.LOW);
		//this.pinAmplifierEnable = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_02, "AmplifierEnable", PinState.LOW);
		//this.i2cRegister = new PCF8574GpioProvider(I2CBus.BUS_1, PCF8574GpioProvider.PCF8574A_0x3F);
		
		//this.pinAmplifierPower = gpio.provisionDigitalOutputPin(this.i2cRegister, PCF8574Pin.GPIO_05);
		//this.pinAmplifierEnable = gpio.provisionDigitalOutputPin(this.i2cRegister, PCF8574Pin.GPIO_06);
		//this.pinAmplifierEnable2 = gpio.provisionDigitalOutputPin(this.i2cRegister, PCF8574Pin.GPIO_05);
		
		//this.pinLed = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_03, "LED", PinState.LOW);
		//this.pinButton = gpio.provisionDigitalInputPin(RaspiPin.GPIO_12, PinPullResistance.PULL_UP);
		//this.pinAmplifierPower.setShutdownOptions(true, PinState.LOW);
		//this.pinAmplifierEnable.setShutdownOptions(true, PinState.LOW);
		//this.pinLed.setShutdownOptions(true, PinState.LOW);
		//this.pinButton.setShutdownOptions(true);
		this.powerPause = powerPause;
		this.enablePause = enablePause;
		//this.lastButtonState = PinState.HIGH;
		//this.announcements = announcements;
		//this.enableLiveAnnouncement = false;
		//this.buttonBlocked = false;
		//this.currentProcess = null;
		
		final I2CBus bus = I2CFactory.getInstance(I2CBus.BUS_1);
		this.i2cRegister = bus.getDevice(0x3F);
		
		/*this.pinButton.addListener(new GpioPinListenerDigital() {
           
			@Override
            public void handleGpioPinDigitalStateChangeEvent(GpioPinDigitalStateChangeEvent event) {
                // display pin state on console
                Messages.info("<GPIOManager> Pin state change: " + event.getPin() + " = " + event.getState());
                
                if(event.getPin() instanceof GpioPinDigitalInput) {
                	GpioPinDigitalInput pin = (GpioPinDigitalInput)event.getPin();
                	
                	if(pin.equals(pinButton)) {
                		if(event.getState() == PinState.LOW)
                			lastButtonState = PinState.LOW;
                		
                		if(event.getState() == PinState.HIGH) {
                			if(lastButtonState == PinState.LOW)
								try {
									if(startedPlayingLive)
										shouldBreakLive = true;
									buttonClicked();
								} catch (InterruptedException | LineUnavailableException | IOException
										| UnsupportedAudioFileException e) {
									Messages.warning(Messages.getStackTrace(e));
								}
                			lastButtonState = PinState.HIGH;
                		}
                	}
                }
            }

        });*/
	}

	/*private boolean startedPlayingLive = false;
	private boolean shouldBreakLive = false;
	
	private void buttonClicked() throws InterruptedException, LineUnavailableException, IOException, UnsupportedAudioFileException {
		if(!this.startedPlayingLive) {
			AnnouncementsManager announcementsManager = this.announcements.getAnnouncementsManager();
			
			this.startedPlayingLive = true;
			this.shouldBreakLive = false;

			Messages.info("<GPIOManager> Starting live output...");
			announcementsManager.setPlaying(true);
			Messages.info("<GPIOManager> Enabling amplifier...");
			new Thread(() -> {
				this.useAmplifier();
			});
			for(int i = 0; i < (this.enablePause+this.powerPause)/100; i++) {
				Thread.sleep(100);
				if(this.shouldBreakLive) {
					this.startedPlayingLive = false;
					announcementsManager.setPlaying(false);
					Thread.sleep(this.enablePause+this.powerPause+100-i*100);
					this.unuseAmplifier();
					Messages.info("<GPIOManager> Live output interrupted.");
					return;
				}
			}
			Messages.info("<GPIOManager> Playing tune...");
			long duration = (long)(announcementsManager.getDefaultTuneForLiveAnnouncementDuration()*1000);
			this.currentProcess = announcementsManager.playDefaultTuneForLiveAnnouncement();
			for(int i = 0; i < duration/100; i++) {
				Thread.sleep(100);
				if(this.shouldBreakLive) {
					this.startedPlayingLive = false;
					announcementsManager.setPlaying(false);
					this.currentProcess.destroy();
					Runtime.getRuntime().exec("killall omxplayer.bin");
					this.currentProcess = null;
					Messages.info("<GPIOManager> Live output interrupted.");
					return;
				}
			}
			//this.pinLed.high();
			Messages.info("<GPIOManager> Speaking is now possible.");
			while(!this.shouldBreakLive);
			Messages.info("<GPIOManager> Speaking is not possible anymore.");
			//this.pinLed.low();
			Messages.info("<GPIOManager> Disabling amplifier...");
			this.unuseAmplifier();
			Messages.info("<GPIOManager> Live output ended.");
			announcementsManager.setPlaying(false);
			this.currentProcess = null;
			this.startedPlayingLive = false;
		}
	}*/
	
	/*private void buttonClicked2() throws InterruptedException {
		if(this.buttonBlocked)
			return;
		if(!this.enableLiveAnnouncement) {
			this.buttonBlocked = true;

			this.enableLiveAnnouncement = true;
			Messages.info("<GPIOManager> Starting live output...");
			this.announcementManager.setPlaying(true);
			
			new Thread(() -> {
				Messages.info("<GPIOManager> Enabling amplifier...");
				this.useAmplifier();
			});
			
			for(int i = 0; i < (this.powerPause+this.enablePause)/100; i++) {
				Thread.sleep(100);
			}
			
			Messages.info("<GPIOManager> Playing tune...");
			this.announcementManager.playDefaultTuneForLiveAnnouncement();
			this.pinLed.high();
			Messages.info("<GPIOManager> Speaking is now possible.");
			
			this.buttonBlocked = false;
		} else {
			this.buttonBlocked = true;
			this.enableLiveAnnouncement = false;
			Messages.info("<GPIOManager> Speaking is not possible anymore.");
			this.pinLed.low();
			Messages.info("<GPIOManager> Disabling amplifier...");
			this.unuseAmplifier();
			Messages.info("<GPIOManager> Live output ended.");
			this.announcementManager.setPlaying(false);
			this.buttonBlocked = false;
		}
	}*/
	
	public void useAmplifier() {
		try {
			this.i2cRegister.write(GPIOManager.AMPLIFIER_POWER_ON_DISABLED);
			Messages.info("Amplifier powered.");
			Thread.sleep(this.powerPause);
		} catch (InterruptedException | IOException e) {
			Messages.warning(Messages.getStackTrace(e));
		}
		try {
			this.i2cRegister.write(GPIOManager.AMPLIFIER_POWER_ON_ENABLED);
			Messages.info("Amplifier enabled.");
			Thread.sleep(this.enablePause);
		} catch (InterruptedException | IOException e) {
			Messages.warning(Messages.getStackTrace(e));
		}
	}
	
	public void unuseAmplifier() {
		try {
			this.i2cRegister.write(GPIOManager.AMPLIFIER_POWER_OFF);
		} catch (IOException e) {
			Messages.warning(Messages.getStackTrace(e));
		}
	}
	
	/*public void useAmplifier() {
		this.powerOnAmplifier();
		Messages.info("Amplifier powered.");
		try {
			Thread.sleep(this.powerPause);
		} catch (InterruptedException e) {
			Messages.warning(Messages.getStackTrace(e));
		}
		this.enableAmplifier();
		Messages.info("Amplifier enabled.");
		try {
			Thread.sleep(this.enablePause);
		} catch (InterruptedException e) {
			Messages.warning(Messages.getStackTrace(e));
		}
	}*/
	
	/*public void unuseAmplifier() {
		this.disableAmplifier();
		this.powerOffAmplifier();
	}
	
	private void powerOnAmplifier() {
		pinAmplifierPower.high();
	}
	
	private void powerOffAmplifier() {
		pinAmplifierPower.low();
	}
	
	private void enableAmplifier() {
		pinAmplifierEnable.high();
	}
	
	private void disableAmplifier() {
		pinAmplifierEnable.low();
	}*/
	
	public long getPowerPause() {
		return this.powerPause;
	}
	
	public long getEnablePause() {
		return this.enablePause;
	}
	
	public void shutdown() {
		gpio.shutdown();
	}
}
