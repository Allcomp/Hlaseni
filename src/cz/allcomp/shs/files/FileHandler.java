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

package cz.allcomp.shs.files;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.Arrays;
import java.util.List;

import cz.allcomp.shs.logging.Messages;

/**
 * Class for reading and writing to files
 * 
 * @author Samuel Trávníček
 *
 */
public class FileHandler {
	
	/**
	 * Checks if the specified file exists.
	 * 
	 * @param path Path to file
	 * @return true if exists, false if not
	 */
	public static boolean fileExists(String path) {
		return new File(path).exists();
	}
	
	/**
	 * Creates new file with specified name if not exists yet.
	 * 
	 * @param path Path to file
	 */
	public static void createFileIfNotExists(String path) {
		File file = new File(path);
		if(!file.exists()) {
			try {
				file.createNewFile();
			} catch (IOException e) {
				Messages.error("Error occured while creating file " + path);
				Messages.error(Messages.getStackTrace(e));
			}
		}
	}
	
	/**
	 * Writes a content to the file
	 * 
	 * @param path Path to file
	 * @param content Content to write
	 */
	public static void writeToFile(String path, String content) {
		try {
			FileWriter fileWriter = new FileWriter(path);
			BufferedWriter bufferedWriter = new BufferedWriter(fileWriter);
			bufferedWriter.write(content);
			bufferedWriter.close();
			fileWriter.close();
		} catch (IOException e) {
			Messages.error("Error occured while writing to file " + path);
			Messages.error(Messages.getStackTrace(e));
		}
	}
	
	/**
	 * Writes a content to the file
	 * 
	 * @param path Path to file
	 * @param lines string lists of lines to write
	 */
	public static void writeToFile(String path, List<String> lines) {
		try {
			FileWriter fileWriter = new FileWriter(path);
			BufferedWriter bufferedWriter = new BufferedWriter(fileWriter);
			
			for(String line : lines) {
				bufferedWriter.write(line);
				bufferedWriter.newLine();
			}
			
			bufferedWriter.close();
			fileWriter.close();
		} catch (IOException e) {
			Messages.error("Error occured while writing to file " + path);
			Messages.error(Messages.getStackTrace(e));
		}
	}
	
	/**
	 * Reads content from file
	 * 
	 * @param path Path to file
	 * @return content of file in one string
	 */
	public static String readFileToString(String path) {
		String line = "";
		
		try {
			FileReader fileReader = new FileReader(path);
			BufferedReader bufferedReader = new BufferedReader(fileReader);
			StringBuilder stringBuilder = new StringBuilder();
			String lineSeparator = System.getProperty("line.separator");
			
			while((line = bufferedReader.readLine()) != null) {
				stringBuilder.append(line);
				stringBuilder.append(lineSeparator);
			}
			
			bufferedReader.close();
			fileReader.close();
			
			return stringBuilder.toString();
		} catch (FileNotFoundException e) {
			Messages.error("Error occured while reading file " + path);
			Messages.error(Messages.getStackTrace(e));
			return "";
		} catch (IOException e) {
			Messages.error("Error occured while reading file " + path);
			Messages.error(Messages.getStackTrace(e));
			return "";
		}
	}
	
	/**
	 * Reads content from file
	 * 
	 * @param path Path to file
	 * @return content of file in string list of lines
	 */
	public static List<String> readFileToList(String path) {
		String line = "";
		
		try {
			FileReader fileReader = new FileReader(path);
			BufferedReader bufferedReader = new BufferedReader(fileReader);
			StringBuilder stringBuilder = new StringBuilder();
			String lineSeparator = System.getProperty("line.separator");
			
			while((line = bufferedReader.readLine()) != null) {
				stringBuilder.append(line);
				stringBuilder.append(lineSeparator);
			}
			
			String content = stringBuilder.toString();
			
			bufferedReader.close();
			fileReader.close();
			
			return Arrays.asList(content.split(lineSeparator));
		} catch (FileNotFoundException e) {
			Messages.error("Error occured while reading file " + path);
			Messages.error(Messages.getStackTrace(e));
			return null;
		} catch (IOException e) {
			Messages.error("Error occured while reading file " + path);
			Messages.error(Messages.getStackTrace(e));
			return null;
		}
	}
}
