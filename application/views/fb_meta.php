
	<!-- Metatags para redes sociales  -->
	<meta property="og:title" content="<?=@$seo_title;?>"> <!-- Título del sitio -->
	<meta property="og:site_name" content="Buenas Vibras Viajes"> <!-- Nombre sección -->
	<meta property="og:url" content="<?=current_url();?>">  <!-- URL del sitio -->
	<meta property="og:description" content="<?=@$seo_description;?>"> <!-- Descripción de la sección -->
	<meta property="og:type" content="website">
	<meta property="og:image" content="<?=@$seo_image;?>"> <!-- Ruta de imagen a mostrar al compartir la URL -->
	<meta property="fb:app_id" content="<?=$this->config->item('fb_id');?>"> <!-- Facebook ID -->
	<!-- -->

	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@buenas_vibras_v" />
	<meta name="twitter:title" content="<?=@$seo_title;?>" />
	<meta name="twitter:description" content="<?=@$seo_description;?>" />
	<meta name="twitter:image" content="<?=@$seo_image;?>" />
	<meta name="twitter:image:src" content="<?=@$seo_image;?>">
	<meta name="twitter:url" content="<?=current_url();?>" />	
	<meta name="twitter:domain" content="<?=base_url();?>" />

	<script async src="https://apis.google.com/js/client.js"> </script>
	<script>
	function load()
	{
		gapi.client.setApiKey('<?=$this->config->item("gapi_key");?>'); //get your ownn Browser API KEY
		gapi.client.load('urlshortener', 'v1',function(){});
	}
	window.onload = load;	
	</script>