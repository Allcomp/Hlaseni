package cz.allcomp.announcement;

import com.pi4j.io.gpio.GpioController;
import com.pi4j.io.gpio.GpioFactory;
import com.pi4j.io.gpio.GpioPinDigitalInput;
import com.pi4j.io.gpio.GpioPinDigitalOutput;
import com.pi4j.io.gpio.PinPullResistance;
import com.pi4j.io.gpio.PinState;
import com.pi4j.io.gpio.RaspiPin;
import com.pi4j.io.gpio.event.GpioPinDigitalStateChangeEvent;
import com.pi4j.io.gpio.event.GpioPinListenerDigital;

import cz.allcomp.shs.logging.Messages;

public class GPIOManager {
	private final GpioController gpio;

	private final GpioPinDigitalOutput pinAmplifierPower;
	private final GpioPinDigitalOutput pinAmplifierEnable;
	private final GpioPinDigitalOutput pinLed;
	private final GpioPinDigitalInput pinButton;
	
	private PinState lastButtonState;
	
	private final AnnouncementsManager announcementManager;
	
	private final long powerPause, enablePause;
	
	private boolean enableLiveAnnouncement, buttonBlocked;
	
	public GPIOManager(long powerPause, long enablePause, AnnouncementsManager announcementManager) {
		this.gpio = GpioFactory.getInstance();
		this.pinAmplifierPower = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_00, "AmplifierPower", PinState.LOW);
		this.pinAmplifierEnable = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_02, "AmplifierEnable", PinState.LOW);
		this.pinLed = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_03, "LED", PinState.LOW);
		this.pinButton = gpio.provisionDigitalInputPin(RaspiPin.GPIO_12, PinPullResistance.PULL_UP);
		this.pinAmplifierPower.setShutdownOptions(true, PinState.LOW);
		this.pinAmplifierEnable.setShutdownOptions(true, PinState.LOW);
		this.pinLed.setShutdownOptions(true, PinState.LOW);
		this.pinButton.setShutdownOptions(true);
		this.powerPause = powerPause;
		this.enablePause = enablePause;
		this.lastButtonState = PinState.HIGH;
		this.announcementManager = announcementManager;
		this.enableLiveAnnouncement = false;
		this.buttonBlocked = false;
		
		this.pinButton.addListener(new GpioPinListenerDigital() {
           
			@Override
            public void handleGpioPinDigitalStateChangeEvent(GpioPinDigitalStateChangeEvent event) {
                // display pin state on console
                System.out.println("<GPIOManager> Pin state change: " + event.getPin() + " = " + event.getState());
                
                if(event.getPin() instanceof GpioPinDigitalInput) {
                	GpioPinDigitalInput pin = (GpioPinDigitalInput)event.getPin();
                	
                	if(pin.equals(pinButton)) {
                		if(event.getState() == PinState.LOW)
                			lastButtonState = PinState.LOW;
                		
                		if(event.getState() == PinState.HIGH) {
                			if(lastButtonState == PinState.LOW)
                				buttonClicked();
                			lastButtonState = PinState.HIGH;
                		}
                	}
                }
            }

        });
	}
	
	private void buttonClicked() {
		if(this.buttonBlocked)
			return;
		if(!this.enableLiveAnnouncement) {
			this.buttonBlocked = true;
			this.enableLiveAnnouncement = true;
			Messages.info("<GPIOManager> Starting live output...");
			this.announcementManager.setPlaying(true);
			Messages.info("<GPIOManager> Enabling amplifier...");
			this.useAmplifier();
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
	}
	
	public void useAmplifier() {
		this.powerOnAmplifier();
		try {
			Thread.sleep(this.powerPause);
		} catch (InterruptedException e) {
			Messages.warning(Messages.getStackTrace(e));
		}
		this.enableAmplifier();
		try {
			Thread.sleep(this.enablePause);
		} catch (InterruptedException e) {
			Messages.warning(Messages.getStackTrace(e));
		}
	}
	
	public void unuseAmplifier() {
		this.disableAmplifier();
		this.powerOffAmplifier();
	}
	
	private void powerOnAmplifier() {
		pinAmplifierPower.high();
	}
	
	private void powerOffAmplifier() {
		pinAmplifierPower.low();;
	}
	
	private void enableAmplifier() {
		pinAmplifierEnable.high();
	}
	
	private void disableAmplifier() {
		pinAmplifierEnable.low();
	}
	
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
