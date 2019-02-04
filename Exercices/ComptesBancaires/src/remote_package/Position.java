package remote_package;

import java.util.Date;
import java.io.Serializable;

public class Position implements Serializable {
	
	public double solde;
	public Date derniereOperation;

	//ctor
	public Position(double _solde) {
		this.solde = _solde;
		this.derniereOperation = new Date();
	}
	
	public static void main(String[] args) {
		
	}

}
