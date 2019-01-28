package myPackage;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.Scanner;

public class HTTP_Client {
	
	public static void closeStreams(PrintWriter htmlFile, Socket sendSocket) {
			htmlFile.close();
			try {
				sendSocket.close();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
	}
	
	public static void main(String[] args) {
		Scanner sc = new Scanner(System.in);
		System.out.println("Veuillez entrer l'URL que vous souhaitez récupérer :");
		String URL = sc.nextLine();
		
		try {
			
			Socket sendSocket = new Socket("" + URL, 80);
			
			PrintWriter out = new PrintWriter(
			          new BufferedWriter(
			            new OutputStreamWriter(
			              sendSocket.getOutputStream())),true);
			
			out.println("GET / HTTP/1.0\r\n");
			out.println("Host: " + URL + "\r\n");
			out.println("\r\n");
			
			PrintWriter htmlFile = new PrintWriter("dump.html", "UTF-8");
			System.out.println("Fichier créé !");
					
			BufferedReader in = new BufferedReader(new InputStreamReader(sendSocket.getInputStream()));
			String response;
			String line = "<!doctype";
			String debut = in.readLine();
			String regex = debut.split("\\s")[0].toLowerCase();
			
			
			
			switch(debut.split("\\s")[1]) {
				case "200":
					break;
					
				case "400":
					System.out.println("Erreur 400 - Requête incorrecte");
					closeStreams(htmlFile, sendSocket);
				return;
				
				case "401":
					System.out.println("Erreur 401 - La page à laquelle vous essayez d'accéder est protegee par le serveur");
					closeStreams(htmlFile, sendSocket);
				return;
				
				case "403":
					System.out.println("Erreur 403 - L'acces à la page vous est interdit");
					closeStreams(htmlFile, sendSocket);
				return;
				
				case "404":
					System.out.println("Erreur 404 - Le fichier que vous avez demandé est introuvable");
					closeStreams(htmlFile, sendSocket);
				return;
				
				default:
					System.out.println("Erreur " + debut.split("\\s")[1] + " - Veuillez réesayer");
				
			}
			
			while(!line.matches(regex)) { //Tant qu'on est dans le header
				System.out.println(debut); //Affiche la ligne courante
				debut = in.readLine();
				regex = debut.split("\\s")[0].toLowerCase(); // Premier mot de la ligne courant en minuscule
			}
			
			System.out.println(debut); //Afficher la premiere ligne du fichier retourné dans la sortie standard du client
			htmlFile.println(debut); //Afficher la premiere ligne du fichier retourné par le serveur dans le fichier sauvegard� par le client
			
			while((response = in.readLine()) != null)
			{
				htmlFile.println(response); //Sauvegarde la page renvoyée par le serveur
				System.out.println(response);
				
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
