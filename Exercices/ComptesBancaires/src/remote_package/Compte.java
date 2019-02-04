package remote_package;

import java.util.Date;

public class Compte {
	private Position position;
	
	//Constructeur
	public Compte(double _soldeInitial) {
		position = new Position(_soldeInitial);
	}
	
	//Ajoute la somme _somme au compte
	public void ajouter(double _somme) {
		 if(_somme > 0) {
			 position.solde += _somme;
			 position.derniereOperation = new Date();
		 } else {
			 System.out.println("Opération impossible, veuillez entrer une somme supérieure à 0");
		 }
	}
	
	//Retire la somme _somme du compte
	public void retirer(double _somme) {
		if(_somme > 0 && (position.solde - _somme) >= 0) {
			 position.solde -= _somme;
			 position.derniereOperation = new Date();
		 } else {
			 System.out.println("Opération impossible. Veuillez entrer une somme supérieure à 0 ou vérifier que cette opération est possible");
		 }
	}
	
	//Renvoie la position courante du compte
	public Position position() {
		return this.position;
	}
}
