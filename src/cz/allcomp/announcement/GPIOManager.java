package cz.allcomp.announcement;

import com.pi4j.io.gpio.GpioController;
import com.pi4j.io.gpio.GpioFactory;
import com.pi4j.io.gpio.GpioPinDigitalOutput;
import com.pi4j.io.gpio.PinState;
import com.pi4j.io.gpio.RaspiPin;

import cz.allcomp.shs.logging.Messages;

public class GPIOManager {
	final GpioController gpio;

	final GpioPinDigitalOutput pinAmplifierPower;
	final GpioPinDigitalOutput pinAmplifierEnable;
	
	final long powerPause, enablePause;
	
	public GPIOManager(long powerPause, long enablePause) {
		this.gpio = GpioFactory.getInstance();
		this.pinAmplifierPower = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_00, "AmplifierPower", PinState.LOW);
		this.pinAmplifierEnable = gpio.provisionDigitalOutputPin(RaspiPin.GPIO_02, "AmplifierEnable", PinState.LOW);
		this.pinAmplifierPower.setShutdownOptions(true, PinState.LOW);
		this.pinAmplifierEnable.setShutdownOptions(true, PinState.LOW);
		this.powerPause = powerPause;
		this.enablePause = enablePause;
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
