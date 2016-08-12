/**
 * Copyright (c) 2015, Václav Vilímek
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 	- Redistributions of source code must retain the above copyright notice, this list 
 * 	  of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright , this list 
 *    of conditions and the following disclaimer in the documentation and/or other materials 
 *    provided with the distribution.
 *  - Neither the name of the ALLCOMP a.s. nor the of its contributors may be used to endorse 
 *    or promote products from this software without specific prior written permission.
 *    
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL VÁCLAV VILÍMEK BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

package cz.allcomp.shs.logging;

import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.logging.Level;
import java.util.logging.Logger;

public class Messages {
	
	public static String LOG_PATH = "log/output.log";

	public static boolean SHOW_INFO = false;
	public static boolean SHOW_WARNINGS = false;
	public static boolean SHOW_ERRORS = false;

	public static boolean LOG_INFO = false;
	public static boolean LOG_WARNINGS = false;
	public static boolean LOG_ERRORS = false;
	
	public static void log(String message) {
		log(MessageType.INFO, message);
	}
	
	public static void info(String message) {
		log(message);
	}
	
	public static void warning(String message) {
		log(MessageType.WARNING, message);
	}
	
	public static void error(String message) {
		log(MessageType.ERROR, message);
	}
	
	public static void log(MessageType type, String message) {
		DateFormat format = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
		Date date = new Date();
		
		String msg = "";
		
		if(type == MessageType.ERROR) {
			msg = ("[" + format.format(date) + "][" + type.toString() + "]: " + message);
			if(SHOW_ERRORS)
				System.err.println(msg);
		} else {
			msg = ("[" + format.format(date) + "][" + type.toString() + "]: " + message);
			if(type == MessageType.WARNING)
				if(SHOW_WARNINGS)
					System.out.println(msg);
			if(type == MessageType.INFO)
				if(SHOW_INFO)
					System.out.println(msg);
		}
				
		if(LOG_PATH != null) {
            try {
                PrintWriter vystup = new PrintWriter(new FileOutputStream(LOG_PATH, true));
                if(type == MessageType.ERROR) {
                	if(LOG_ERRORS)
                		vystup.write(msg + "\n");
                } else if(type == MessageType.WARNING) {
                	if(LOG_WARNINGS)
                		vystup.write(msg + "\n");
                } else if(type == MessageType.INFO) {
                	if(LOG_INFO)
                		vystup.write(msg + "\n");
                } else
            		vystup.write(msg + "\n");
                	
                vystup.close();
            } catch (FileNotFoundException ex) {
                Logger.getLogger(Messages.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
	}
    
    public static String getStackTrace(Exception e) {
        StringWriter sw = new StringWriter();
        PrintWriter pw = new PrintWriter(sw);
        e.printStackTrace(pw);
        return sw.toString();
    }
}
