$(document).ready(function(){
	//Rode balk verwijderen
	$("div#subnav_full_color").hide();
	//alle divs met class subnav verbergen tijdens het laden
	$("div.subnav").hide();
	//De active nav zoeken
	var currentNav = $("ul.nav li a.selected");
	if(currentNav){
		ShowSubNav(currentNav);
	}
	


	
	//wanneer er op een link in de ul met class nav wordt geklikt
	$("ul.nav li a").click(function() {
		ShowSubNav(this);		
	});
});

function ShowSubNav(selectedNav){
	//title ophalen van het huidige item
	var title = $(selectedNav).attr('title');
	if($( window ).width() < 678)
	{
		//is mobile
		
		//Als de huidige subnav al getoond wordt, verbergen
		//Anders alle divs met class subnav verbergen, en de huidige tonen
		if($("div#" + title).css('display') !== 'none') {
			//alle divs met class subnav verbergen
			$("div.subnav").hide();
		}
		else
		{
			//alle divs met class subnav verbergen
			$("div.subnav").hide();	
			//div zoeken met een id die gelijk is aan de title van het huidige item (= is de subnav)
			//deze tonen
			//en toevoegen in de html na de huidige link
			$("div#" + title).show().insertAfter($(selectedNav));
		}
	} 
	else
	{
		//div zoeken met een id die gelijk is aan de title van het huidige item (= is de subnav)
		//deze tonen
		if($("div#" + title).css('display') !== 'none'){
			$("div#subnav_full_color").hide();
			$("div.subnav").hide();
		} else
		{
			$("div#subnav_full_color").show();
			$("div.subnav").hide();
			$("div#" + title).show();
		}
	}
}