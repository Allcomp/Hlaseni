/**
 * Copyright (c) 2015, Samuel Trávníček
 * All rights reserved.
 * 
 * Any use, modification or implementation of this source code in your 
 * own or third-party applications without permission is prohibited.
 */

package cz.allcomp.shs.util;

/**
 * Enumerator of time units with DateFormat marks
 * 
 * @author Samuel Trávníček
 * @see java.text.DateFormat
 * 
 */
public enum TimeUnit {
	UNKNOWN("unknown"), SECOND("ss"), MINUTE("m"), HOUR("HH"), DAY("dd"), MONTH("MM"), YEAR("y"),
	DAY_OF_WEEK("u"); //DAY_OF_WEEK 1 - monday, 7 - sunday
	
	private String mark;
	
	private TimeUnit(String mark) {
		this.mark = mark;
	}
	
	public String getMark() {
		return this.mark;
	}
}
