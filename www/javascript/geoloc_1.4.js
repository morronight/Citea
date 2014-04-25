function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}


function getXhr(){
	var xhr = null; 
	if(window.XMLHttpRequest) // Firefox et autres
		xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject){ // Internet Explorer 
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
		}
	else { 
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
		xhr = false; 
	} 
        return xhr;
}
                        

function go()
{
	var heureD = document.getElementById("heureDep").value;
	var minutesD = document.getElementById("minuteDep").value;
	var selDep = document.getElementById('lieuDep');
	var selArr = document.getElementById('lieuArr');
	var ligne = selDep[selDep.selectedIndex].getAttribute("data-ligne");
	var idGareDep = selDep[selDep.selectedIndex].getAttribute("data-IdGare");
	var idGareArr = selArr[selArr.selectedIndex].getAttribute("data-IdGare");
	var	message = document.getElementById('message');
	var selectgares = document.getElementById("lieuDep");
	var xhr_object = null;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			switch(xhr.status)
			{
				case 200:
					document.getElementById('resultat').innerHTML = xhr.responseText;
					var distakm = selectgares.options[selectgares.selectedIndex].id;
					var distarrondie = parseFloat(distakm).toFixed(1);
					document.querySelector(".dist").innerHTML = "Distance : "+distarrondie+" km";
					document.querySelector(".dist").id = distarrondie;
					prec = document.querySelector(".prec");
					if((distarrondie > 0.5) || (prec.id == "faible"))
					{
						var bouttons = document.getElementById("initHoraires").querySelectorAll(".social");
						for(var i = 0;i<bouttons.length;i++)
						{
							bouttons[i].style.backgroundColor = "grey";
							bouttons[i].onclick = "";
						} 
					}
					var d = new Date();
					var minute = d.getMinutes().toString();
					if (minute < 10) { minute = "0" + minute; };
					var dateStr = d.getHours().toString()+'h'+minute+'.';
					message.innerHTML = "Données rafraîchies à "+dateStr;
					break;
				default:
					donneesOffline();
					message.innerHTML = "Mode hors ligne";
					break;
			}
		}
		else
			message.innerHTML = "Entrée dans le mode hors ligne";
	}
	xhr.open("POST","index.php",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("lieuxD="+idGareDep+"&lieuxA="+idGareArr+"&methode=ajax&action=afficher&heuresD="+heureD+"&minutesD="+minutesD+"&ligne="+ligne);
	message.innerHTML = "Chargement en cours des données...";
}

function donneesOffline()
{
	var heureD = document.getElementById("heureDep").value;
	var minutesD = document.getElementById("minuteDep").value;
	var selDep = document.getElementById('lieuDep');
	var selArr = document.getElementById('lieuArr');
	var idGareDep = selDep[selDep.selectedIndex].getAttribute("data-IdGare");
	var idGareArr = selArr[selArr.selectedIndex].getAttribute("data-IdGare");
	
	if(document.getElementById('initHoraires').className == "enLigne")
	{
		loadData();
		document.getElementById('initHoraires').className = "horsLigne";
	}
	var d=new Date();
	d.setHours(heureD);
	d.setMinutes(minutesD);
	d.setSeconds(00);
	timesec = d.getTime() / 1000;
	var mesLignes = document.querySelectorAll('tr');
	var compteur =0;
	var i;
	for(i=0;i<mesLignes.length;i++) 
	{
		if(mesLignes[i].style.display == "")
			mesLignes[i].style.display = "none";
		if((mesLignes[i].getAttribute("data-depart")==idGareDep) && (mesLignes[i].getAttribute("data-arrivee")==idGareArr))
		{
			var test = new Date();
			test.setTime(mesLignes[i].getAttribute("data-heure"));
			var oldD = new Date(1000.*parseInt(mesLignes[i].getAttribute("data-heure")));
			oldD.setDate(d.getDate());
			oldD.setMonth(d.getMonth());
			if(((oldD.getTime()/1000) > (timesec - 300)) && (compteur < 2))
			{
				mesLignes[i].style.display = "";
				if(mesLignes[i].getAttribute("data-heure") > (timesec))
				{
					mesLignes[i].childNodes[1].innerHTML = "Bus précédent"; 
				}
				else
				{
					if(compteur == 0)
						mesLignes[i].childNodes[1].innerHTML = "Prochain bus";
					else
						mesLignes[i].childNodes[1].innerHTML = "Bus d'après";
				}
				compteur++;
			}
		}
		else if((mesLignes[i].id != "trtitres") && (mesLignes[i].id != "trnext") && (mesLignes[i].id != "trsuiv"))
			mesLignes[i].style.display = "none";
		else
			mesLignes[i].style.display = "";
	}
}


function loadData()
{
	var xhr_object = null;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if((xhr.readyState == 4) && (xhr.status == 200)){
			go();
			document.getElementById('initHoraires').innerHTML = xhr.responseText;
		}
	}
	xhr.open("GET","dataHorsLigne.php",true);
	xhr.send(); 
}


function cookieCalc(CD,CA,lat2,lon2)
{
	var selectgaresA = document.getElementById("lieuArr");
	var nombreOption = selectgaresA.options.length;
	var distMax = -1;
	for(i = 1; i < nombreOption; i++)
	{
		if ((selectgaresA.options[i].value == CD) || (selectgaresA.options[i].value == CA))
		{
			var dista;
			var dist2;
			var lat1 = selectgaresA.options[i].getAttribute("data-lat");
			var lon1 = selectgaresA.options[i].getAttribute("data-lon");
			if (lat1==lat2 && lon1==lon2)
				dist2 = 0;
			else
			{
				var dist = Math.sin(deg2rad(lat1)) * Math.sin(deg2rad(lat2)) +  Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.cos(deg2rad(lon1-lon2));
				var dist1 = Math.acos(dist); 
				dist2 = rad2deg(dist1);
			}
			dista = dist2 * 111190;
			if ((distMax < 0) || (dista > distMax))
			{
				distMax = dista;
				selectgaresA.selectedIndex = i;
			}
		}
	}
}



function deg2rad (angle) {
    return (angle / 180) * Math.PI;
}

function rad2deg (angle) {
    return angle * 57.29577951308232; // angle / Math.PI * 180
}



function showLocation(position) {

	if(navigator.onLine)
	{
		//Heure now()
		var now = new Date();
		var hr = now.getHours();
		var min = now.getMinutes();
		var sec = now.getSeconds();
		var selectHeure = document.getElementById("heureDep");
		var selectMinute = document.getElementById("minuteDep");
		selectHeure.value= hr;
		selectMinute.value= min;

		//Precision

		if (position.coords.accuracy > 10000)
		{
			document.querySelector(".prec").innerHTML = "Precision : faible (≥ 10km près)";
			document.querySelector(".prec").id="faible";
		}
		if ((position.coords.accuracy > 1000) && (position.coords.accuracy < 10000))
		{
			document.querySelector(".prec").innerHTML = "Precision : moyenne (≥ 1km près)";
			document.querySelector(".prec").id="faible";
		}
		if (position.coords.accuracy < 1000)
			document.querySelector(".prec").innerHTML = "Précision : bonne (≤ 1km près)";
	//IdLigne = option[option.selectedIndex].getAttribute("data-IdLigne");

		var selectgares = document.getElementById("lieuDep");
		var selectgaresA = document.getElementById("lieuArr");
		var nombreOption = selectgares.options.length;
		var lat1 = selectgares[selectgares.selectedIndex].getAttribute("data-lat");
		var lon1 = selectgares[selectgares.selectedIndex].getAttribute("data-lon");
		var lat2 = position.coords.latitude;
		var lon2 = position.coords.longitude;
	  
		if(selectgares.selectedIndex == 0)
		{
			if (lat1==lat2 && lon1==lon2) return 0;
			var dist = Math.sin(deg2rad(lat1)) * Math.sin(deg2rad(lat2)) +  Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.cos(deg2rad(lon1-lon2));
			var dist1 = Math.acos(dist); 
			var dist2 = rad2deg(dist1);
		  
			if (dist2>0) var distMin = dist2 * 111190;
			var numSelect = 1;
			for(i=1;i<nombreOption;i++)
			{
				var lat1 = selectgares.options[i].getAttribute("data-lat");
				var lon1 = selectgares.options[i].getAttribute("data-lon");
				if (lat1==lat2 && lon1==lon2) return 0;
				var dist = Math.sin(deg2rad(lat1)) * Math.sin(deg2rad(lat2)) +  Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.cos(deg2rad(lon1-lon2));
				var dist1 = Math.acos(dist); 
				var dist2 = rad2deg(dist1);

				if (dist2>0)  var dista = dist2 * 111190;
				selectgares.options[i].id = dista / 1000;
				if (dista < distMin)
				{
					distMin = dista;
					var nomGareProche = selectgares.options[i].label;
					var numSelect = i;
				}
			}
			selectgares.selectedIndex=numSelect;
		}
		else
		{
			var lat1 = selectgares.options[selectgares.selectedIndex].getAttribute("data-lat");
			var lon1 = selectgares.options[selectgares.selectedIndex].getAttribute("data-lon");
			if (lat1==lat2 && lon1==lon2) return 0;
			var dist = Math.sin(deg2rad(lat1)) * Math.sin(deg2rad(lat2)) +  Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.cos(deg2rad(lon1-lon2));
			var dist1 = Math.acos(dist); 
			var dist2 = rad2deg(dist1);
			if (dist2>0)  var dista = dist2 * 111190;
		}
		var cookGareDepart = readCookie("LieuDepart");
		var cookGareArrivee = readCookie("LieuArrivee");
	
		if((cookGareDepart != null) && (cookGareArrivee != null))
		{
			cookieCalc(cookGareDepart,cookGareArrivee,lat2,lon2);
		}


		if ((nomGareProche != "Rovaltain") && (selectgaresA.selectedIndex==0) && (cookGareDepart == null))
			selectgaresA.selectedIndex=4;

		if ((selectgaresA.selectedIndex==4) && (nomGareProche == "Rovaltain") && (cookGareDepart == null))
			selectgaresA.selectedIndex=1;

		go();

		return nomGareProche;
		return 0;
	}
}

function CookiesOffline(){
	var cookGareDepart = readCookie("LieuDepart");
	var cookGareArrivee = readCookie("LieuArrivee");
	if((cookGareDepart != null) && (cookGareArrivee != null))
	{
		var selectgaresA = document.getElementById("lieuArr");
		var selectgaresD = document.getElementById("lieuDep");
		selectgaresA.selectedIndex = cookGareDepart;
		selectgaresD.selectedIndex = cookGareArrivee;
	}
}

function HeureOffline(){
	var now = new Date();
	var hr = now.getHours();
	var min = now.getMinutes();
	var sec = now.getSeconds();
	var selectHeure = document.getElementById("heureDep");
	var selectMinute = document.getElementById("minuteDep");
	selectHeure.value= hr;
	selectMinute.value= min;
}


function errorHandler(err) {
	if(err.code == 1) {
		document.querySelector(".dist").innerHTML = "Geolocalisation non prise en charge";
		document.querySelector(".dist").innerHTML = "";
		CookiesOffline();
		HeureOffline();
		go();
	}else if( err.code == 2) {
		document.querySelector(".dist").innerHTML = "Geolocalisation non prise en charge";
		document.querySelector(".dist").innerHTML = "";
	}
}

function getLocation(){
		if(navigator.geolocation){
		// timeout at 60000 milliseconds (60 seconds)
		var options = {timeout:60000};
		navigator.geolocation.getCurrentPosition(showLocation,errorHandler,options);
		}else{
			alert("Désolé, votre navigateur ne supporte pas la navigation!");
		}
}


function refreshSelect() { 

	var now = new Date();
	var hr = now.getHours();
	var min = now.getMinutes();
	var sec = now.getSeconds();
	var selectHeure = document.getElementById("heureDep");
	var selectMinute = document.getElementById("minuteDep");
	selectHeure.value= hr;
	selectMinute.value= min;
	go();
}

function FormSignaler()
{
	var xhr_object = null;
	var xhr = getXhr();
	var mail = document.getElementById("mail").value;
	var telephone = document.getElementById("telephone").value;
	var busId = document.getElementById("busid").value;
	var heureD = document.getElementById("heureDep").value;
	var minutesD = document.getElementById("minuteDep").value;
	var selDep = document.getElementById('lieuDep');
	var idGareDep = selDep.options[selDep.selectedIndex].value;
	var selArr = document.getElementById('lieuArr');
	var idGareArr = selDep.options[selArr.selectedIndex].value;
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("formSign").style.display='none';
			alert("Passage signalé, merci.")
		}
	}

	xhr.open("POST","index.php",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("idBus="+busId+"&mail="+mail+"&telephone="+telephone+"&lieuxD="+idGareDep+"&methode=ajax&action=signaler");
}

function FormAlerter()
{
	var xhr_object = null;
	var xhr = getXhr();
	var mail = document.getElementById("mail").value;
	var telephone = document.getElementById("telephone").value;
	var busId = document.getElementById("busid").value;
	var nature = document.getElementById("nature").options[document.getElementById("nature").selectedIndex].value;
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("formAlert").style.display='none';
			alert("Alerte envoyé, merci.")

		}
	}

	xhr.open("POST","index.php",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("idBus="+busId+"&mail="+mail+"&telephone="+telephone+"&lieuxD="+idGareDep+"&nature="+nature+"&methode=ajax&action=alerter");
}

function FormContact()
{
	var xhr_object = null;
	var xhr = getXhr();
	var mail = document.getElementById("mailContact").value;
	var telephone = document.getElementById("telephoneContact").value;
	var busId = document.getElementById("busid").value;
	var textArea = document.getElementById("textarea").value;
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			alert("Mail envoyé, merci.")
			document.getElementById("formContact").style.display='none';
		}
	}

	xhr.open("POST","index.php",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("idBus="+busId+"&mail="+mail+"&telephone="+telephone+"&text="+textArea+"&methode=ajax&action=contact");
}

function difheure(heuredeb,heurefin){
	hd=heuredeb.split(":");
	hf=heurefin.split(":");
	hd[0]=eval(hd[0]);hd[1]=eval(hd[1]);hd[2]=eval(hd[2]);
	hf[0]=eval(hf[0]);hf[1]=eval(hf[1]);hf[2]=eval(hf[2]);
	if(hf[2]<hd[2]){hf[1]=hf[1]-1;hf[2]=hf[2]+60;}
	if(hf[1]<hd[1]){hf[0]=hf[0]-1;hf[1]=hf[1]+60;}
	if(hf[0]<hd[0]){hf[0]=hf[0]+24;}
	return (hf[0]-hd[0]) + ":" + (hf[1]-hd[1]) + ":" + (hf[2]-hd[2]);
}

function RetardIndic()
{
	var now = new Date();
	var hr = now.getHours();
	var min = now.getMinutes();
	var sec = now.getSeconds();
	var heureNow = hr+":"+min+":"+sec;
	var heureTest = document.getElementById('heureSign').value;
	if (heureNow < heureTest)
		estRetard = "Ce bus n'est pas en retard";
	else
	{
		var retard = difheure(heureTest,heureNow);
		//var retmin=retard.substr(2,2);
		var estRetard = "Le bus a potentiellement "+retard+" min de retard";
	}
	return estRetard;
}

function DisplayRetard(busId,heureD)
{
	document.getElementById('formSign').style.display='block';
	document.getElementById('busid').value=busId;
	document.getElementById('heureSign').value= heureD;
	test = RetardIndic();
	document.getElementById('retardIndic').innerHTML = test;
	if(document.getElementById('formRecherche').style.display='block')
		document.getElementById('formRecherche').style.display='none';
	if(document.getElementById('res').style.display='block')
		document.getElementById('res').style.display='none';
	document.querySelector('footer').style.display='none';
}

function DisplayAlerte(busId)
{
	document.getElementById('formAlert').style.display='block';
	document.getElementById('busid').value=busId; 
	if(document.getElementById('formRecherche').style.display='block')
		document.getElementById('formRecherche').style.display='none';
	if(document.getElementById('res').style.display='block')
		document.getElementById('res').style.display='none';
	document.querySelector('footer').style.display='none';
}

function DisplayContact()
{
	document.getElementById('formContact').style.display='block';
	if(document.getElementById('formRecherche').style.display='block')
		document.getElementById('formRecherche').style.display='none';
	if(document.getElementById('res').style.display='block')
		document.getElementById('res').style.display='none';
	if(document.getElementById('formSign').style.display='block')
		document.getElementById('formSign').style.display='none';
	if(document.getElementById('formAlert').style.display='block')
		document.getElementById('formAlert').style.display='none';
	document.querySelector('footer').style.display='none';
}

function DisplayAll()
{
	document.getElementById('formRecherche').style.display='block';
	document.getElementById('res').style.display='block';
	document.querySelector('footer').style.display='block';
}

function DisplayMonRovaltain()
{
	if(document.getElementById('monrov').style.display=='none')
	{
		document.getElementById('monrov').style.display='block';
		if(document.getElementById('formRecherche').style.display='block')
			document.getElementById('formRecherche').style.display='none';
		if(document.getElementById('res').style.display='block')
			document.getElementById('res').style.display='none';
		if(document.getElementById('formSign').style.display='block')
			document.getElementById('formSign').style.display='none';
		if(document.getElementById('formAlert').style.display='block')
			document.getElementById('formAlert').style.display='none';
		if(document.getElementById('resultat').style.display='block')
			document.getElementById('resultat').style.display='none';
		if(document.getElementById('titre').style.display='block')
			document.getElementById('titre').style.display='none';
		document.querySelector('footer').style.display='none';
	}
	else
	{
		document.getElementById('monrov').style.display='none';
		document.getElementById('formRecherche').style.display='block';
		document.getElementById('res').style.display='block';
		document.getElementById('resultat').style.display='block';
		document.getElementById('titre').style.display='block';
		document.querySelector('footer').style.display='block';
	}
}

function DisplayCansii()
{
	if(document.getElementById('cansii').style.display=='none')
	{
		document.getElementById('cansii').style.display='block';
		if(document.getElementById('formRecherche').style.display='block')
			document.getElementById('formRecherche').style.display='none';
		if(document.getElementById('res').style.display='block')
			document.getElementById('res').style.display='none';
		if(document.getElementById('formSign').style.display='block')
			document.getElementById('formSign').style.display='none';
		if(document.getElementById('formAlert').style.display='block')
			document.getElementById('formAlert').style.display='none';
		if(document.getElementById('resultat').style.display='block')
			document.getElementById('resultat').style.display='none';
		if(document.getElementById('titre').style.display='block')
			document.getElementById('titre').style.display='none';
		document.querySelector('footer').style.display='none';
	}
	else
	{
		document.getElementById('cansii').style.display='none';
		document.getElementById('formRecherche').style.display='block';
		document.getElementById('res').style.display='block';
		document.getElementById('resultat').style.display='block';
		document.getElementById('titre').style.display='block';
		document.querySelector('footer').style.display='block';
	}
}

   function afficher(ma_balise,etat)
	{
		if(etat=='cache')
		{
			document.getElementById(ma_balise).style.display= 'none';
		}
		else
		{
			document.getElementById(ma_balise).style.display = 'inline';
		}
	}
	function aff_Ajout()
	{
	var x=document.getElementById("ChoixType").selectedIndex;
		if (x == 0)
		{ 
		document.getElementById('ajout2').style.display= 'inline';
		document.getElementById('modif2').style.display= 'none';
		}
		if (x == 1)
		{
		document.getElementById('ajout2').style.display= 'none';
		}
	}
	function select_default(ma_balise,etat)
	{
		(document.getElementById(ma_balise).value=etat).selected=true;
	}
		
	   function ajout_modif(etat,ma_balise,ma_balise2)
        {
            if(etat=='cache')
            {
                document.getElementById(ma_balise).style.display= 'block';
				document.getElementById(ma_balise2).style.display= 'none';
            }
            else
            {
                document.getElementById(ma_balise).style.display = 'none';
				document.getElementById(ma_balise2).style.display= 'block';
			}
        }
	
	function affHorBus(selectElmt)
	{
		var xhr = new XMLHttpRequest();
		var BusId = selectElmt.value;
		var url ='Admin.php';
		select = document.getElementById('form1');	
		url += '?BusId='+BusId+'&action=recup_bus&methode=ajax';
		 if (xhr)
		 {
		  xhr.addEventListener
		  (
		   'readystatechange'
		   , function()
		   {
			if (xhr.readyState == 4)
			{
			 switch (xhr.status)
			 {
			  case 0:
			   return false;
			   break;
			  case 200:
			   select.innerHTML = xhr.responseText;
			   return false;
			   break;
			  case 500:
			   alert("Erreur lors de la récupération des données.");
			   break;
			  default:
			   alert("Erreur lors de l'envoi (" + xhr.status + ")");
			   break;
		 }
		}  
	   }
	   , true
	  );
	  xhr.open("GET", url, true);
	  xhr.send();
	 }
	 return false;
	}
	
	
	
	function selectHoraire(option)
	{
	 var xhr = new XMLHttpRequest();
	 var formData = new FormData();
	 var url = 'Admin.php';
	
	select = document.getElementById('choix3');
	IdLigne = option[option.selectedIndex].getAttribute("data-IdLigne");
	Sens = option[option.selectedIndex].getAttribute("data-Sens");
	heureMin = option[option.selectedIndex].getAttribute("data-heureMin");
	heureMax = option[option.selectedIndex].getAttribute("data-heureMax");
	url += '?IdLigne='+IdLigne+'&Sens='+Sens+'&action=ajoutselect&methode=ajax';
	
	if (heureMin != '')
		url += '&heureMin='+heureMin;
	if (heureMax != '')
		url += '&heureMax='+heureMax;
	 if (xhr)
	 {
	  xhr.addEventListener
	  (
	   'readystatechange'
	   , function()
	   {
		if (xhr.readyState == 4)
		{
		 switch (xhr.status)
		 {
		  case 0:
		   return false;
		   break;
		  case 200:
		   select.innerHTML = xhr.responseText;
		   return false;
		   break;
		  case 500:
		   alert("Erreur lors de la récupération des données.");
		   break;
		  default:
		   alert("Erreur lors de l'envoi (" + xhr.status + ")");
		   break;
		 }
		}  
	   }
	   , true
	  );
	  xhr.open("GET", url, true);
	  xhr.send();
	 }
	 return false;
	}
	
	function selectLigneAjout(option)
	{
	 var xhr = new XMLHttpRequest();
	 var formData = new FormData();
	 var url = 'Admin.php';
	
	select = document.getElementById('form2');
	IdLigne = option[option.selectedIndex].getAttribute("data-IdLigne");
	Sens = option[option.selectedIndex].getAttribute("data-Sens");
	url += '?IdLigne='+IdLigne+'&Sens='+Sens+'&action=form_ajout&methode=ajax';
	
	 if (xhr)
	 {
	  xhr.addEventListener
	  (
	   'readystatechange'
	   , function()
	   {
		if (xhr.readyState == 4)
		{
		 switch (xhr.status)
		 {
		  case 0:
		   return false;
		   break;
		  case 200:
		   select.innerHTML = xhr.responseText;
		   return false;
		   break;
		  case 500:
		   alert("Erreur lors de la récupération des données.");
		   break;
		  default:
		   alert("Erreur lors de l'envoi (" + xhr.status + ")");
		   break;
		 }
		}  
	   }
	   , true
	  );
	  xhr.open("GET", url, true);
	  xhr.send();
	 }
	 return false;
	}
	
	

	function cacher_afficher(balise,etat)
	{ 
		if (etat == 'afficher')
			{document.getElementById(balise).style.display = 'block';}
			
		
		if (etat == 'cacher')
			{document.getElementById(balise).style.display = 'none';}
			
	}
	
	function Ajout_Form(depart)
	{
	 var xhr = new XMLHttpRequest();
	 var depart = document.getElementById(depart).value; 
	 var url = 'Admin.php';
	 select = document.getElementById('form2');
	 url += '?depart='+depart+'&action=form_ajout&methode=ajax';
	 

	 if (xhr)
	 {
	  xhr.addEventListener
	  (
	   'readystatechange'
	   , function()
	   {
		if (xhr.readyState == 4)
		{
		 switch (xhr.status)
		 {
		  case 0:
		   return false;
		   break;
		  case 200:
		   select.innerHTML = xhr.responseText;
		   return false;
		   break;
		  case 500:
		   alert("Erreur lors de la récupération des données.");
		   break;
		  default:
		   alert("Erreur lors de l'envoi (" + xhr.status + ")");
		   break;
		 }
		}  
	   }
	   , true
	  );
	  xhr.open("GET", url, true);
	  xhr.send();
	 }
	 return false;
	
	}
	
	function envoi_form()
	{
	document.getElementById('form_verif').submit(); 
	}
	
	function confirmation()
	{
		return confirm("Validez-vous les modifications en cours ?");
	}
	