package remote_package;

import java.io.DataInputStream;
import java.rmi.Naming;

public class BanqueClient {
	
	
	public static void main(String[] args) {
		try {		
			DataInputStream in = new DataInputStream(System.in);
			System.out.println("Client started\n");
			
			Banque remoteBank = (Banque)Naming.lookup("rmi://localhost:8989/MaBanque");
			
			System.out.println("1 - Creer un compte\n");
			System.out.println("2 - Ajouter de l'argent\n");
			System.out.println("3 - Retirer de l'argent\n");
			System.out.println("4 - Position d'un compte\n");
			System.out.println("5 - Quitter\n");
			
			int choice = Integer.parseInt(in.readLine());
			String nomCompte;
			float montant;
			
			while(choice != 5) {
				switch(choice) {
					case 1:
					System.out.println("Veuillez entrer le nom du compte\n");
					nomCompte = in.readLine();
					System.out.println("Veuillez saisir le montant a virer sur le compte\n");
					montant = Float.valueOf(in.readLine()).floatValue();
					remoteBank.creerCompte(nomCompte, montant);
					break;
					
					case 2:
					System.out.println("Veuillez entrer le nom du compte\n");
					nomCompte = in.readLine();
					System.out.println("Veuillez indiquer le montant à ajouter sur le compte\n");
					montant = Float.valueOf(in.readLine()).floatValue();
					remoteBank.ajouter(nomCompte, montant);
					break;
					
					case 3:
					System.out.println("Veuillez entrer le nom du compte\n");
					nomCompte = in.readLine();
					System.out.println("Veuillez indiquer le montant a soustraire du compte\n");
					montant = Float.valueOf(in.readLine()).floatValue();
					remoteBank.retirer(nomCompte, montant);
					break;
										
					case 4:
					System.out.println("Veuillez entrer le nom du compte\n");
					nomCompte = in.readLine();
					Position p = remoteBank.position(nomCompte);
					System.out.println("Position au " + p.derniereOperation + " du compte " + nomCompte + ": " + p.solde + "\n");
					break;
				}
				System.out.println("1 - Creer un compte\n");
				System.out.println("2 - Ajouter de l'argent\n");
				System.out.println("3 - Retirer de l'argent\n");
				System.out.println("4 - Position d'un compte\n");
				System.out.println("5 - Quitter\n");
				  
				choice = Integer.parseInt(in.readLine());
			}
		}
		catch (Exception e) {
			e.printStackTrace();
		}
	}
}
