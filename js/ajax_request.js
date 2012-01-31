	//---------------------- function loadContent -------------------------------------------------
                function loadContent(contentURL, targetElementId)
		{
		    if (contentURL != 0)
		    {
		        if (window.ActiveXObject)
		        {
		        	httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
		        }
		        else
		        {
		        	httpRequest = new XMLHttpRequest();
		        }
		        httpRequest.open("GET", contentURL, true);
		        httpRequest.onreadystatechange = function() {processRequest(targetElementId)};
		        httpRequest.send(null);
		    }
		    else
		    {
		    	document.getElementById("content").innerHTML = "";		        
		    }
		}
	//---------------------- function processRequest --------------------------------------------------
		function processRequest(targetElementId)
		{
			if (httpRequest.readyState == 4)
			{
				if(httpRequest.status==200 || window.location.href.indexOf("http")==-1)
			    {
                                if (targetElementId != 0)
                                {
                                    var content = document.getElementById(targetElementId);
                                    content.innerHTML = httpRequest.responseText;
                                }
			    }
			    else
			    {
			        alert("Chyba pri nacitani obsahu do elemntu id "+targetElementId+", odpověď serveru "+ httpRequest.status +":"+ httpRequest.statusText);
			    }
			}
		}
