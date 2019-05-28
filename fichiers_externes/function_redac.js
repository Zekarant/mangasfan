function categorie(name){
	name = name.toUpperCase();

	switch(name){
		case "PERSONNAGE":
		case "PERSONNAGES":
			return "#4F7BFF";
		break;

		case "ACTUALITE":
			return "#FFD93E";
		break;

		case "SOLUCE":
			return "#86B300";
		break;

		case "ASTUCE":
		case "ASTUCES":
			return "#B76FFF";
		break;

		default:
			return "#8CC4E1";
	}
}