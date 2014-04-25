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
	
	
	
	
	function envoi_form()
	{
	select = document.getElementById('form2');
	var form = document.getElementById('form2');	
	var formData = new FormData(form);
	var xhr = new XMLHttpRequest();
	var url = 'Admin';
	
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
		   alert('envoi du formulaire effectué');
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
	  xhr.open("POST", url, true);
	  xhr.send(formData);	
	  return false;
	 }
	 
	}
	
	
	function form_js()
	{
	var form = document.getElementById('form2');	
		var formData = new FormData(form);
		formData.elements["Token"].value = token;
		var xhrForm = new XMLHttpRequest();
		// Add any event handlers here...
		xhrForm.open('POST', Admin, true);
		xhrForm.send(formData);
		
	
	}
	
	
	function submit_form2()
	{
	 var xhr = new XMLHttpRequest();
	 var url = 'Administration';
	 url += '?action=get_token';
	 

	 if (xhr)
	 {
	  xhr.addEventListener
	  (
	   'readystatechange'
	   , function getToken()
	   {
		if (xhr.readyState == 4)
		{
		 switch (xhr.status)
		 {
		  case 0:
		   return false;
		   break;
		  case 200:
		   var token = xhr.responseText;
		    document.getElementById('form2_Token').value = token;
			var form = document.getElementById('form2');	
			var formData = new FormData(form);
			var xhrForm = new XMLHttpRequest();
			var urlForm = 'Admin';
	
	 if (xhrForm)
	 {
	  xhrForm.addEventListener
	  (
	   'readystatechange'
	   , function form_js()
	   {
		if (xhrForm.readyState == 4)
		{
		 switch (xhrForm.status)
		 {
		  case 0:
		   return false;
		   break;
		  case 200:
		   alert(xhrForm.responseText);
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
	  xhrForm.open("POST", urlForm, true);
	  xhrForm.send(formData);	
	  return false;
	 }
			
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
			} , true
	  
	  );
	  xhr.open("GET", url, true);
	  xhr.send();
	  return false;
	 }
	 
	
	}
	
	function submit_form1()
	{
	 var xhr = new XMLHttpRequest();
	 var url = 'Administration';
	 url += '?action=get_token';
	 

	 if (xhr)
	 {
	  xhr.addEventListener
	  (
	   'readystatechange'
	   , function getToken()
	   {
		if (xhr.readyState == 4)
		{
		 switch (xhr.status)
		 {
		  case 0:
		   return false;
		   break;
		  case 200:
		   var token = xhr.responseText;
		    document.getElementById('form1_Token').value = token;
			var form = document.getElementById('form1');	
			var formData = new FormData(form);
			var xhrForm = new XMLHttpRequest();
			var urlForm = 'Admin';
	
	 if (xhrForm)
	 {
	  xhrForm.addEventListener
	  (
	   'readystatechange'
	   , function form_js()
	   {
		if (xhrForm.readyState == 4)
		{
		 switch (xhrForm.status)
		 {
		  case 0:
		   return false;
		   break;
		  case 200:
		   var message = xhrForm.responseText;
		   if (message == 0)
		   {
		   alert("Echec de l'enregistrement des modifications du bus");
		   }
		   else
		   {
		   alert("Les modifications du bus ont été enregistrées");
		   form.innerHTML = xhrForm.responseText;
		   }
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
	  xhrForm.open("POST", urlForm, true);
	  xhrForm.send(formData);	
	 }
			
		   break;
		  case 500:
		   alert("Erreur lors de la récupération des données.");
		   break;
		  default:
		   alert("Erreur lors de l'envoi (" + xhr.status + ")");
		   break;
		 
		}  
	   }
			} , true
	  
	  );
	  xhr.open("GET", url, true); 
	  xhr.send();
	  
	 }
	 return false;
	
	}
	
	