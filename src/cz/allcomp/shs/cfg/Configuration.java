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

package cz.allcomp.shs.cfg;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import cz.allcomp.shs.files.FileHandler;

/**
 * File configuration writer and reader.
 * 
 * @author Samuel Trávníček
 *
 */
public class Configuration {
	
	/**
	 * constant containing index and value separator
	 */
	public static final String INDEX_VALUE_SEPARATOR = "=";
	
	
	/**
	 * Path to configuration file
	 */
	private String file;
	
	/**
	 * HashMap containing keys and values of configuration
	 */
	private HashMap<String, String> values = new HashMap<String, String>();
	
	/**
	 * Creates configuration file if not exists yet and loads keys and values to HashMap.
	 * 
	 * @param file
	 */
	public Configuration(String file) {
		this.file = file;
		FileHandler.createFileIfNotExists(file);
		this.loadValues();
	}
	
	/**
	 * @return File path of configuration
	 */
	public String getFile() {
		return this.file;
	}

	/**
	 * Loads values and keys to HashMap.
	 */
	private void loadValues() {
		List<String> lines = FileHandler.readFileToList(this.file);
		
		for(String line : lines) {
			String[] indexValueArray = line.split(Configuration.INDEX_VALUE_SEPARATOR);
			String index = "";
			String value = "";
			if(indexValueArray.length > 1) {
				index = indexValueArray[0];
				value = indexValueArray[1];
			}
			this.values.put(index, value);
		}
	}
	
	/**
	 * Adds or changes a value of the key.
	 * 
	 * @param index Key
	 * @param value Value
	 */
	public void set(String index, String value) {
		this.values.put(index, value);
	}
	
	/**
	 * @param index Key
	 * @return Value under the key
	 */
	public String get(String index) {
		return this.values.get(index);
	}
	
	/**
	 * Deletes key and value from configuration under the specified key.
	 * 
	 * @param index Key
	 */
	public void delete(String index) {
		this.values.remove(index);
	}
	
	public void save() {
		List<String> lines = new ArrayList<String>();
		Iterator<Entry<String, String>> iterator = values.entrySet().iterator();
		while(iterator.hasNext()) {
			Map.Entry<String, String> pair = (Map.Entry<String, String>)iterator.next();
			lines.add(pair.getKey() + Configuration.INDEX_VALUE_SEPARATOR + pair.getValue());
		}
		FileHandler.writeToFile(file, lines);
	}
}
