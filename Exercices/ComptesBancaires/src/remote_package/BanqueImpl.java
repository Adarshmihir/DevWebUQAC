package remote_package;

import java.io.File;
import java.rmi.RemoteException;
import java.rmi.registry.LocateRegistry;
import java.rmi.server.UnicastRemoteObject;
import java.util.Hashtable;

public class BanqueImpl extends UnicastRemoteObject implements Banque {
	//La HashTable qui contient tous les comptes clients
	Hashtable clients;
	
	public BanqueImpl() throws RemoteException {
		super();
		clients = new Hashtable();
	}
	
	public void creerCompte(String _id, double _soldeInitial) throws RemoteException{
		Compte compte = new Compte(_soldeInitial);
		
		clients.put(_id, compte);
	}
	
	public void ajouter(String _id, double _somme) throws RemoteException {
		if(clients.get(_id) != null && _somme > 0) {
			Compte c = (Compte)clients.get(_id);
			c.ajouter(_somme);
		}
	}
	
	public void retirer(String _id, double _somme) throws RemoteException {
		if(clients.get(_id) != null && _somme > 0) {
			Compte c = (Compte)clients.get(_id);
			c.retirer(_somme);
		}
	}
	
	public Position position(String _id) throws RemoteException {
		if(clients.get(_id) != null) {
			Compte c = (Compte)clients.get(_id);
			return c.position();
		}
		else
			return null;
	}
	
	public static void main(String[] args) {
		  // on positionne le gestionnaire de sécurité
		  /// System.setSecurityManager(new RMISecurityManager());

		  try {
			  
			  File f1= new File ("./bin");
			  String codeBase=f1.getAbsoluteFile().toURI().toURL().toString();
			  System.setProperty("java.rmi.server.codebase", codeBase);

			  LocateRegistry.createRegistry(8989);
			  
		    // on crèe la banque et on l'enregistre grâce au Naming
		    BanqueImpl banque = new BanqueImpl();
		    System.out.println("start server");
		    java.rmi.Naming.rebind("rmi://localhost:8989/MaBanque", banque);
		   
			//java.rmi.Naming.rebind("rmi://arabica.info.uqam.ca:31337/MaBanque", banque);
		    System.out.println("L'objet serveur RMI 'MaBanque' est enregistre");
		  } 
		  catch(Exception e) {
		    e.printStackTrace();
		  }
		}
}
