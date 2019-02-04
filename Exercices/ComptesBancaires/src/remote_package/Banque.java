package remote_package;

import java.rmi.Remote;
import java.rmi.RemoteException;

public interface Banque extends Remote {
	public void creerCompte(String _id, double _soldeInitial) throws RemoteException;
	public void ajouter(String _id, double _somme) throws RemoteException;
	public void retirer(String _id, double _somme) throws RemoteException;
	public Position position(String _id) throws RemoteException;
}
