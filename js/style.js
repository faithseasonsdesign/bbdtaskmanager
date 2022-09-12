function navigationButton(){
    $('#nav-button').click(function(){
        $("#sm-nav-list-wrapper").slideToggle(400);
    });
}


function styling(){

    //design btn press
	$('.design-btn').click(function(){
		$('.design-projects').css("display","block");
		$('.design-projects').set("class","web-project-content design-projects row ");
		$('.dev-projects').css("display","none");
	});
   
}
navigationButton();
styling();
