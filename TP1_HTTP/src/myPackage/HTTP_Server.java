package myPackage;

import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.Date;
import java.util.StringTokenizer;

//Each Client Connection will be managed by a dedicated Thread
public class HTTP_Server implements Runnable{
	
	static final File WEB_ROOT = new File(".");
	static final String DEFAULT_FILE = "index.html";
	static final String FILE_NOT_FOUND = "404.html";
	static final String METHOD_NOT_SUPPORTED = "not_supported.html";
	
	//Port to listen connection
	static final int PORT = 80;
	
	//Verbose mode
	static final boolean verbose = true;
	
	//Client Connection via Socket Class
	private Socket connect;
	
	public HTTP_Server(Socket c) {
		connect = c;
	}
	

	public static void main(String[] args) {
		try {
			ServerSocket serverConnect = new ServerSocket(PORT);
			System.out.println("Server started. \nListening for connections on port : " + PORT + " ...\n");
			
			//We listen until user halts server execution
			while(true) {
				HTTP_Server myServer = new HTTP_Server(serverConnect.accept());
				
				if(verbose) {
					System.out.println("Connection opened @ " + new Date());
				}
				
				//Create dedicated thread to manage client connection
				Thread thread = new Thread(myServer);
				thread.start();
			}
		} catch (IOException e) {
			System.err.println("Server connection error : " + e.getMessage());
		}
	}


	@Override
	public void run() {
		//We manage our particular client connection
		BufferedReader in = null;
		PrintWriter out = null;
		BufferedOutputStream dataOut = null;
		String fileRequested = null;
		String method = null;
		
		try {
			//We read characters from the client via the input stream on the socket
			in = new BufferedReader(new InputStreamReader(connect.getInputStream()));
			
			//We get character output stream to client (for headers)
			out = new PrintWriter(connect.getOutputStream());
			
			//Get binary output stream to client (for request)
			dataOut = new BufferedOutputStream(connect.getOutputStream());
			
			//Get first line of the request from the client and split it in several tokens in an array of strings
			String[] input = in.readLine().split("\\s");
			
			//We get the HTTP method of the client
			method = input[0];
			System.out.println("Method : " + method + ".");
			
			//We get file requested
			fileRequested = input[1];
			System.out.println("File requested : " + input[1]);
			
			//We support only GET and HEAD methods, we check
			if(!method.equals("GET") && !method.equals("HEAD")) {
				if (verbose)
					System.out.println("501 Not Implemented : " + method + " method.");
				
				//We return the not supported file to the client
				File file = new File(WEB_ROOT, METHOD_NOT_SUPPORTED);
				int fileLength = (int) file.length();
				String contentMimeType = "text/html";
				byte[] fileData = readFileData(file, fileLength);
				
				//We send HTTP Headers with data to client
				out.println("HTTP/1.1 501 Not Implemented");
				out.println("Server: Java HTTP Server from AJEDDIG Faris & GOYON Airy : 1.0");
				out.println("Date: " + new Date());
				out.println("Content-type: " + contentMimeType);
				out.println("Content-length: " + fileLength);
				out.println(); //requested blank line between headers and content
				out.flush(); //flush character output stream buffer
				
				//File
				dataOut.write(fileData, 0, fileLength);
				dataOut.flush();
				
			} else {
				//GET or HEAD method
				if(fileRequested.endsWith("/"))
					fileRequested += DEFAULT_FILE;
				
				File file = new File(WEB_ROOT, fileRequested);
				int fileLength = (int) file.length();
				String content = getContentType(fileRequested);
				
				if(method.equals("GET")) { //GET method so we return content
					byte[] fileData = readFileData(file, fileLength);
					
					//Send HTTP Headers
					out.println("HTTP/1.1 200 OK");
					out.println("Server: Java HTTP Server from GOYON Airy : 1.0");
					out.println("Date: " + new Date());
					out.println("Content-type: " + content);
					out.println("Content-length: " + fileLength);
					out.println(); //Blank line between headers and content, very important !
					out.flush(); //Flush character output stream buffer
					
					dataOut.write(fileData, 0, fileLength);
					dataOut.flush();
				}
				
				if(verbose)
					System.out.println("File " + fileRequested + " of type " + content + " returned.");
			}
							
			} catch (FileNotFoundException fnfe) {
				try {
					fileNotFound(out, dataOut, fileRequested);
				} catch(IOException ioe) {
					System.err.println("Error with file not found exception : " + ioe.getMessage());
				}
				
			
		} catch (IOException ioe) {
			System.err.println("Server error : " + ioe);
		} finally {
			try {
				in.close(); //Close character input stream
				out.close();
				dataOut.close();
				connect.close(); //We close socket connection
			} catch (Exception e) {
				System.err.println("Error closing stream : " + e.getMessage());
			}
			
			if(verbose)
				System.out.println("Connection closed.\n");
		}
	}
	
	private byte[] readFileData(File file, int fileLength) throws IOException {
		FileInputStream fileIn = null;
		byte[] fileData = new byte[fileLength];
		try {
			fileIn = new FileInputStream(file);
			fileIn.read(fileData);
		} finally {
			if (fileIn != null)
				fileIn.close();
		}
		return fileData;		
	}
	
	//Return supported MIME Types
	private String getContentType(String fileRequested) {
		if(fileRequested.endsWith(".htm") || fileRequested.endsWith(".html"))
				return "text/html";
		else
			return "text/plain";
	}
	
	private void fileNotFound(PrintWriter out, OutputStream dataOut, String fileRequested) throws IOException {
		File file = new File(WEB_ROOT, FILE_NOT_FOUND);
		int fileLength = (int) file.length();
		String content = "text/html";
		byte[] fileData = readFileData(file, fileLength);
		
		out.println("HTTP/1.1 404 Not Found");
		out.println("Server: Java HTTP Server from GOYON Airy : 1.0");
		out.println("Date: " + new Date());
		out.println("Content-type: " + content);
		out.println("Content-length: " + fileLength);
		out.println(); //Blank line between headers and content, very important !
		out.flush(); //Flush character output stream buffer
		
		dataOut.write(fileData, 0, fileLength);
		dataOut.flush();
		
		if (verbose)
			System.out.println("File " + fileRequested + " not found.");
	}
}
