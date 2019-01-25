package myPackage;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.net.InetAddress;
import java.net.Socket;
import java.net.UnknownHostException;

public class HTTP_Client {
	
	public static void main(String[] args) {
		// TODO Auto-generated method stub
		try {
			Socket sendSocket = new Socket(InetAddress.getByName("google.ca"), 80);
			
			PrintWriter out = new PrintWriter(
			          new BufferedWriter(
			            new OutputStreamWriter(
			              sendSocket.getOutputStream())),true);
			
			out.println("GET / HTTP/1.0\r\n");
			out.println("Host: google.ca\r\n");
			out.println("\r\n");
			
			PrintWriter htmlFile = new PrintWriter("dump.html", "UTF-8");
			System.out.println("Fichier créé !");
			
			//out.flush() to check ????
					
			BufferedReader in = new BufferedReader(new InputStreamReader(sendSocket.getInputStream()));
			String response;
			
			while((response = in.readLine()) != null)
			{
				htmlFile.println(response);
			}
				
			
			System.out.println("Fichier édité !");
			
			htmlFile.close();
			sendSocket.close();
			
		} catch (UnknownHostException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
