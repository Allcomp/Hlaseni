/**
 * Copyright (c) 2015, Samuel Trávníček
 * All rights reserved.
 * 
 * Any use, modification or implementation of this source code in your 
 * own or third-party applications without permission is prohibited.
 */

package cz.allcomp.shs.util;

import java.sql.Date;
import java.sql.Timestamp;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;

import cz.allcomp.shs.logging.Messages;

public class Time {
	
	public static Time getTime() {
		Timestamp ts = new Timestamp(System.currentTimeMillis());
		return new Time((long)(ts.getTime()));
	}
	
	public static Time getTime(long timeStamp) {
		return new Time(timeStamp);
	}
	
	public static Time getTime(int day, int month, int year, 
			int hour, int minutes, int seconds) {
		DateFormat sdf = new SimpleDateFormat(
				TimeUnit.DAY.getMark()+"."+TimeUnit.MONTH.getMark()+"."
				+TimeUnit.YEAR.getMark()+"."+TimeUnit.HOUR.getMark()+"."
				+TimeUnit.MINUTE.getMark()+"."+TimeUnit.SECOND.getMark());
		try {
			java.util.Date date = sdf.parse(day+"."+month+"."+year+"."+hour+"."+minutes+"."+seconds);
			return new Time(date.getTime());
		} catch (ParseException e) {
			Messages.error(Messages.getStackTrace(e));
			return null;
		}
	}
	
	private long timeStamp;
	
	private Time(long timeStamp) {
		this.timeStamp = timeStamp;
	}
	
	public long getTimeStamp() {
		return this.timeStamp;
	}
	
	public int getByTimeUnit(TimeUnit timeUnit) {
		Date date = new Date(timeStamp);
		SimpleDateFormat sdf = new SimpleDateFormat(timeUnit.getMark());
		String formattedDate = sdf.format(date);
		return Integer.parseInt(formattedDate);
	}
	
	public int getDayOfWeek() {
		return this.getByTimeUnit(TimeUnit.DAY_OF_WEEK);
	}
	
	public int getDay() {
		return this.getByTimeUnit(TimeUnit.DAY);
	}
	
	public int getMonth() {
		return this.getByTimeUnit(TimeUnit.MONTH);
	}
	
	public int getYear() {
		return this.getByTimeUnit(TimeUnit.YEAR);
	}
	
	public int getHour() {
		return this.getByTimeUnit(TimeUnit.HOUR);
	}
	
	public int getMinutes() {
		return this.getByTimeUnit(TimeUnit.MINUTE);
	}
	
	public int getSeconds() {
		return this.getByTimeUnit(TimeUnit.SECOND);
	}
}
