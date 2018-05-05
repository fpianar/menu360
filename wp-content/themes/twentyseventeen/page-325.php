<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
require_once("func.php");

$stop = array("A","AD","AFTER","AI","AL","ALL","ALLA","ALLE","ALLO","ALSO","AN","AND","AS","AT","B","BE","BECAUSE","BEFORE","BETWEEN","BIG","BUT","BY","C","CI","COL","CON","D","DA","DAGLI","DAI","DAL","DALL","DALLA","DALLE","DALLO","DE","DEGLI","DEI","DEL","DELL","DELLA","DELLE","DELLO","DI","E","ED","ET","F","FOR","FRA","FROM","G","GLI","H","HOWEVER","I","IF","IL","IN","INTO","J","K","L","LA","LE","LO","M","ME","MI","MORE","N","NE","NEGLI","NEL","NELL","NELLA","NELLO","NON","O","OF","OPPURE","OR","OTHER","OUT","OVER","P","PER","Q","R","S","SAW","SI","SINCE","SU","SUCH","SUGLI","SUI","SUL","SULL","SULLA","SULLE","T","TE","THAN","THAT","THE","THEIR","THEM","THERE","THESE","THEY","THIS","THOSE","TI","TO","TRA","U","UN","UNA","UNDER","UNO","UPON","V","VI","WE","WERE","WHEN","WHERE","WHETHER","WHICH","WHO","WITH","WITHIN","WITHOUT","X","Y","Z");
$keyQ = "";
$breadcrumb = "<strong>".__("Sei in",CURRENT_THEME)."</strong>";
$template = get_stylesheet_directory_uri();

if(is_numeric($_REQUEST['s']) || is_numeric($_REQUEST['nome'])){
				
	$args = array(
	'numberposts'	=> 1,
	'post_type'		=> 'locale',
	'meta_query'	=> array(
		'relation'		=> 'AND',
		array(
			'key'		=> 'numero_locale',
			'value'		=> !empty($_REQUEST['s'])?$_REQUEST['s']:$_REQUEST['nome'],
			'compare'	=> '='
		)
		
	)
	
);

$the_query = new WP_Query( $args );
 if( $the_query->have_posts() ):  
	while ( $the_query->have_posts() ) { 
		$pt = $the_query->posts; 
		header("location:".get_the_permalink($pt[0]->ID));
 		wp_reset_query();	
		exit();
	}
endif;
}			
			
get_header(); 
?>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<style type="text/css">
.carousel {
    margin-bottom: 0;
    padding: 0 40px 30px 40px;
}
#send1,#newsearch{width:100%; margin: 5px auto}
/* The controlsy */
.carousel-inner .row{ text-align:center}
.carousel-inner .col-md-1{margin:0 5px;padding:0; display:inline-block;float:none}
.carousel-control {
	left: -12px;
    height: 40px;
	width: 40px;
    background: none repeat scroll 0 0 #222222;
    border: 4px solid #FFFFFF;
    border-radius: 23px 23px 23px 23px;
    margin-top:20px;
}
.carousel-control.right {
	right: -12px;
}

/* The indicators */
.carousel-indicators {
	right: 50%;
	top: auto;
	bottom: -10px;
	margin-right: -19px;
		display:none
}
/* The colour of the indicators */
.carousel-indicators li {
	background: #cecece;
}
.carousel-indicators .active {
background: #428bca;
}
</style>
<?php      
global $query_string,$wp_query;




function distance($lat1, $lon1, $lat2, $lon2, $unit="K") {
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
      return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
  } else {
      return $miles;
  }
}

function in_arrayi($needle, $haystack) {
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

if(is_numeric($_REQUEST['ric'])){
	$cats = $_REQUEST["ric"];
	$nowcat = get_the_category_by_ID($_REQUEST["ric"]);
	$cts = get_categories(array('child_of'=>$_REQUEST["ric"],'hide_empty'=> 0,'orderby'=> 'name','exclude' => array(1) )); 
	foreach((array)  $cts as $k=>$v){$cats.= ",".$v->term_id;}									
		 $args = array(
		"cat"=>array($cats)
		);			
	}			

$keyQ .= !empty($_REQUEST['s'])? $_REQUEST['s']:"";		
$keyQ .= !empty($nowcat)? " + " . $nowcat:"";	
$keyQ .= !empty($_POST["citta"])? " + " . $_POST["citta"]:"";				
$keyQ .= !empty($_POST["prodotto"])? " + " . $_POST["prodotto"]:"";


if(is_numeric($_REQUEST["portata"])){
	$pr = decodetype("Food Tipologie");
	$keyQ .= !empty($_REQUEST["portata"])? " + " . $pr[$_REQUEST["portata"]]:"";
}
				
$breadcrumb .= !empty($nowcat)? "&nbsp;>&nbsp;".$nowcat:"";
$breadcrumb .= !empty($_POST["citta"])? "&nbsp;>&nbsp;".$_POST["citta"]:"";
$breadcrumb .= !empty($_POST["servizi"])? "&nbsp;>&nbsp;[ ".str_replace("-"," ",implode(",&nbsp;",$_POST["servizi"])) ." ] ":"";



ob_start();
			$args = array();
			$categorie = array();
			$tag = "";
			
			foreach((array)$posted_data['tipologia_att'] as $v ){
				$categorie[] = get_cat_ID(trim($v));				
			}
			if(is_numeric($_REQUEST['tipologia'])){
						
						$args = array(
							 'tax_query' => array(
										array(
										'taxonomy' => 'category',
										'field'    => 'id',
										'terms' => $_REQUEST['tipologia'])
										)
									); 
						
			}

			if(is_numeric($_REQUEST['ric'])){
						$cats = $_REQUEST["ric"];
						$cts = get_categories(array('child_of'=>$_REQUEST["ric"],'hide_empty'=> 0,'orderby'=> 'name','exclude' => array(1) )); 
						foreach((array)  $cts as $k=>$v){$cats.= ",".$v->term_id;}
									
						 $args = array(
								"cat"=>array($cats)
							);			
			}				
							
			if(!empty($_REQUEST['s'])){
				global $wpdb;	
				$key = @explode(" ",urldecode($_REQUEST['s']));
				foreach((array)$key as $v){
					if(!in_array($v,$stop)){
						$tag .= $v;
					}
				}
				
			}
					
			if(!empty($_REQUEST['nome'])){
				//$args["s"] = urldecode($_REQUEST['nome']);
				global $wpdb;	
				$key = @explode(" ",urldecode($_REQUEST['nome']));				
				if($key[0] == ""){$key[0] = urldecode($_REQUEST['nome']);}
				$tag .=  str_replace(" ","-",strtolower($_REQUEST['nome'])); 
			}				
				
			if(!empty($_REQUEST['citta'])){
				$pr = !empty($tag) ? "+":"";
				$tag .=  $pr. urldecode($_REQUEST['citta']);      
			}

			if(!empty($_POST['prodotto'])){	
			global $wpdb;	
			$meta = $wpdb->get_results( "SELECT * FROM ".$wpdb->postmeta." WHERE meta_key='menu' 
				AND LOWER(meta_value) LIKE LOWER('%".trim($_POST['prodotto'])."%')" );
			$posts = array(9999999);
			$portate = array();
			foreach((object) $meta as $k=>$v){
				$posts[] = $v->post_id; 
				$df = @unserialize($v->meta_value);
				foreach((array) $df as $k1=>$v1){
					$low1 = strtolower(trim($v1->titolo));
					$low2 = strtolower(trim($v1->descrizione));					
					$src = strtolower(trim($_POST['prodotto']));

				if(strpos($low1,$src) !== false || 
				   strpos($low1,$src)  !== false){
						$v1->post_id = $v->post_id;
						$portate[] = $v1;		
					}
				}
				
			}
			
			$args["post__in"] = $posts;		
			};

			if(!empty($_POST['portata'])){	
			global $wpdb;	
			$cat = decodetype("Food Tipologie"); 
			
			$sql = sprintf("select * from ".$wpdb->postmeta." WHERE  meta_key='menu' 
				AND LOWER(meta_value) LIKE  LOWER('%s%s%s')", '%s:5:"tabid";', serialize($_POST['portata']), '%');
			$meta = $wpdb->get_results($sql);
			
			$posts = array(9999999);
			$portate = array();
			foreach((object) $meta as $k=>$v){
				$posts[] = $v->post_id; 
				$df = @unserialize($v->meta_value);
				foreach((array) $df as $k1=>$v1){
					$v1->post_id = $v->post_id;
					$portate[] = $v1;		
				}
				
			}
			
			$args["post__in"] = $posts;		
			};
			
						
			if(count($_REQUEST['servizi']) > 0){
				$pr = !empty($tag) ? "+":"";
				$pretag = @implode(",",$_REQUEST['servizi']);
				$tag .= $pr . $pretag;
			}
			if(!empty($tag)){
			   $args['tag']   = $tag;					
			}
			
			$args['posts_per_page']   = -1;
			$args['order']			= 'ASC';
			$args['orderby']		= 'title';
			$args['post_type']        = 'locale';
			//$args['paged']          = get_query_var( 'paged' );
			$args['post_status']      = 'publish';
			$args['suppress_filters'] = true;
	
			function bar_get_nearby( $lat, $lng, $limit = 50, $distance = 1000, $unit = 'km' ) {
				// radius of earth; @note: the earth is not perfectly spherical, but this is considered the ‘mean radius’
					if( $unit == 'km' ) { 
						$radius = 6371.009; 
					}elseif ( $unit == 'mi' ) { 
						$radius = 3958.761; 
					};
				
				// latitude boundaries
				$maxLat = (float) $lat + rad2deg( $distance / $radius );
				$minLat = (float) $lat - rad2deg( $distance / $radius );
				
				// longitude boundaries (longitude gets smaller when latitude increases)
				$maxLng = ( float ) $lng + rad2deg( $distance / $radius) / cos( deg2rad( ( float ) $lat ) );
				$minLng = ( float ) $lng - rad2deg( $distance / $radius) / cos( deg2rad( ( float ) $lat ) );
				
				$max_min_values = array(
				'max_latitude' => $maxLat,
				'min_latitude' => $minLat,
				'max_longitude' => $maxLng,
				'min_longitude' => $minLng
				);
				
				return $max_min_values;
				}

			if(!empty($_COOKIE["gmw_lat"]) && !empty($_COOKIE["gmw_lng"])){
					
					$search_lat =  $_COOKIE["gmw_lat"];
					$search_lng =  $_COOKIE["gmw_lng"];
					$pre =  new WP_Query( $args );	
					
					$nm  = array();
					
					if($_SERVER['REMOTE_ADDR'] == "94.126.56.175"){			
					    print_r($args);
						print_r($pre->request);	
						
					}
					
					if ( $pre->have_posts() ) {
						while ( $pre->have_posts() ) {
							$pre->the_post();
							$nm[] = get_the_id();
						}
						if(count($nm) == 0){ $nm[] = "9999999";}
						$ads2 = " AND post_id IN(".@implode(",",$nm).")";
						wp_reset_postdata();
					}else{
						$ads2 = " AND post_id IN(999999)";						
					} 
					
					
					$sql = "SELECT
							  A.*, (
								3959 * acos (
								  cos ( radians({$search_lat}) )
								  * cos( radians( A.lat ) )
								  * cos( radians( A.lng ) - radians({$search_lng}) )
								  + sin ( radians({$search_lat}) )
								  * sin( radians( A.lat ) )
								)
							  ) AS distance,
							B.*    
							FROM gtw3_acf_google_map_search_geodata AS A
							INNER JOIN 
							gtw3_posts AS B
							ON A.post_id = B.ID 
							WHERE 1 {$ads2}
							HAVING distance < 2000
							ORDER BY distance
							LIMIT 0 , 50;";
				
					//$sql   = $sql_query . $sql_join . $sql_where . $sql_group . $sql_order . $sql_limit;
					$inpost = $wpdb->get_results($sql, OBJECT);	
					$count = count($inpost);
					foreach((array)$inpost as $v4){		 
						 $outs = "";
						 $d = get_field("dove_siamo",$v4->ID);
						 $cat = get_post_meta($v4->ID,'pretitolo');				 
						 $indirizzo = isset($d['address']) ? $d['address']:"";	
						 
						 $distanza = distance($_COOKIE["gmw_lat"],$_COOKIE["gmw_lng"], $v4->lat, $v4->lng, "K");

							 
						 foreach((array) $portate as $k3=>$v3){	

							if($v4->ID == $v3->post_id){
								$outs = "<div class=\"rowportata\">{$v3->titolo} <span class=\"prezzo\">&#8364;&nbsp;{$v3->prezzo}</span></div>";					
								break;
							}
						 }
						$cats2       = wp_get_post_categories($v4->ID);		
						$adscat = array();
						foreach((array)$cats2 as $k=>$v){
							$adscat[]= get_cat_name($v);
						}
						
						 echo '<div class="rowres" style="border-bottom:1px solid #ddd;padding:20px;font-size:14px">
									'.$outs.'
									<div class="categoria-res">'.@implode("-",$adscat).'</div>
									<div style="font-size:16px;line-height:normal;font-weight:bold"><a href="'.get_the_permalink($v4->ID).'">'.get_the_title($v4->ID).'</a></div>
									<div style="font-size:11px">'.$indirizzo.'<br /><strong>Distanza : </strong>'.round($distanza, 2).'&nbsp;Km</div>
									<div class="read-res"><a href="'.get_the_permalink($v4->ID).'"> &raquo; '.__( 'Leggi tutto', 'twentyseventeen' ).'</a></div>
							  </div>';				 
						 

			 
						
					}
				
			}
			if(empty($_COOKIE["gmw_lat"])){
			$myposts =  new WP_Query( $args );
			
			if($_SERVER['REMOTE_ADDR'] == "94.126.56.175"){			
				print_r($myposts->request);	
			}	
			$count = count($myposts->posts);
	
	
			 if ( $myposts->have_posts() ):
       		 while ( $myposts->have_posts() ) : $myposts->the_post(); 			 
			 	 $outs = "";
				 $d = get_field("dove_siamo",$myposts->ID);
				 $cat = get_post_meta($post->ID,'pretitolo');				 
				 $indirizzo = isset($d['address']) ? $d['address']:"";				 
				 foreach((array) $portate as $k3=>$v3){
					if($post->ID == $v3->post_id){
						$outs = "<div class=\"rowportata\">{$v3->titolo} <span class=\"prezzo\">&#8364;&nbsp;{$v3->prezzo}</span></div>";					
						break;
					}
				 }
			
				$cats2       = wp_get_post_categories($post->ID);		
				$adscat = array();
				foreach((array)$cats2 as $k=>$v){
					$adscat[]= get_cat_name($v);
				}
				
				 echo '<div class="rowres" style="border-bottom:1px solid #ddd;padding:20px;font-size:14px">
				 			'.$outs.'
				 			<div class="categoria-res">'.@implode("-",$adscat).'</div>
							<div style="font-size:16px;line-height:normal;font-weight:bold"><a href="'.get_the_permalink().'">'.get_the_title().'</a></div>
							<div class="">'.$indirizzo.'</div>
							<div class="read-res"><a href="'.get_the_permalink().'"> &raquo; '.__( 'Leggi tutto', 'twentyseventeen' ).'</a></div>
					  </div>';				 
			 endwhile;
			 endif;				
		}

			if($count == 0 && count($inpost) == 0){ echo "<div class=\"noresult\">".__("Nessun risultato trovato per la ricerca",CURRENT_THEME)."</div>";}			


$out = ob_get_clean();
$argsservice = array('child_of'=>196,'hide_empty'=> 0,'orderby'=> 'name','exclude' => array(1) );
$cats = get_categories($argsservice); 		
$icons = decodetypeAll("Categorie");

$args['child_of'] = 187; // Extra
$second = get_categories($args);
$all = array_merge( $cats, $second );





// inizio desktop
if(!Scheda::isMobileDev()){?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        	<div class="row"><div class="wpb_column vc_column_container vc_col-sm-12">
            <div id="breadcrumb"><?php echo $breadcrumb ?></div>
            <h2 style="font-size: 24px;color: #000;text-align: center;padding-left:20px;padding-top:0" class="vc_custom_heading  no-mobile"><?php echo __("RISULTATI RICERCA",CURRENT_THEME)?> <div class="subsearch"><?php echo $keyQ?></div></h2>
            
            
            <div id="Carousel" class="carousel slide">
                 
                <ol class="carousel-indicators">
                    <li data-target="#Carousel" data-slide-to="0" class="active"></li>
                    <li data-target="#Carousel" data-slide-to="1"></li>
                    <li data-target="#Carousel" data-slide-to="2"></li>
                </ol>
                 
                <!-- Carousel items -->
                <div class="carousel-inner">  
                <div class="item active">
                	<div class="row">
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="2"><img src="/wp-content/uploads/2018/05/primi-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="3"><img src="/wp-content/uploads/2018/05/carne-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="4"><img src="/wp-content/uploads/2018/05/pesce-2-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="14"><img src="/wp-content/uploads/2018/05/panini-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="0"><img src="/wp-content/uploads/2018/05/pizze-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                 	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="7"><img src="/wp-content/uploads/2018/05/dolci-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="10,19,20"><img src="/wp-content/uploads/2018/05/vini-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail"  data-id="18"><img src="/wp-content/uploads/2018/05/aperitivi-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>

                	</div><!--.row-->
                </div><!--.item-->
                 
                </div><!--.carousel-inner-->
                  <a data-slide="prev" href="#Carousel" class="left carousel-control">‹</a>
                  <a data-slide="next" href="#Carousel" class="right carousel-control">›</a>
              </div><!--.Carousel-->
            
            
      </div></div>
        	<div class="vc_row wpb_row vc_row-fluid">
		<div class="row wpb_row vc_row-fluid">
        <div class="wpb_column vc_column_container vc_col-sm-3">
		<form name="cerca" id="cerca" method="post" action="https://www.menu360.it/cerca/?ricerca=1" style="max-width:90%">
<?php 

		echo "<div class=\"serviceicon\"><a href=\"#no-go\" id=\"servizi\">
		<img src=\"/wp-content/uploads/2018/04/servizi.gif\" alt=\"".__("Servizi",CURRENT_THEME)."\" /></a>";	
		echo "<a href=\"#no-go\" id=\"close_servizi\" style=\"display:none\">
		<img src=\"". get_stylesheet_directory_uri() ."/images/close.png\" alt=\"".__("Close",CURRENT_THEME)."\" /></a>";		
		
		echo "<div class=\"cerca subservizi\" style=\"display:none\">";
		echo "<ul>";
		foreach((array)  $all as $k=>$v){
			$fnd = "";
			foreach((array)  $icons as $k1=>$v1){
			if($v1[0] == $v->term_id){
				echo "<li><a href=\"javascript:;\" data-id=\"{$v1[0]}\">{$v->name}</a>&nbsp;<input type=\"checkbox\" name=\"servizi[]\" value=\"{$v->slug}\" id=\"check-{$v1[0]}\" /> 
				</li>";
				$fnd = $v1;break;
				}
			}
		};	
		
		echo '</ul></div></div>';
	?>	    		
	<span style="font-size:14px;color:rgba(43, 108, 163, 1);font-weight:bold;padding-right:10px" class="no-mobile"><?php echo __("Altri","twentyseventeen")?></span>
    <input name="s" class="wp_autosearch_input" type="text"  value="<?php echo $value; ?>" style="width: 95%;" placeholder="<?php echo __("Cerca un LOCALE","twentyseventeen")?>" autocomplete="off"  value="<?php echo $_REQUEST['s']?>" />
	<button style="display: none;" class="wp_autosearch_submit"></button>
	<input type="text" name="prodotto" id="prodotto" placeholder="<?php echo __("Cerca un Piatto/Vino/etc","twentyseventeen")?>" value="<?php echo $_REQUEST['prodotto']?>" />
    <input type="text" name="citta" id="citta" placeholder="<?php echo __("Città","twentyseventeen")?>" value="<?php echo $_REQUEST['citta']?>" />
    <input type="hidden" name="ric" id="ric" value="<?php echo $_REQUEST["ric"] ?>" />
    <input type="hidden" name="portata" id="portata" value="<?php echo $_REQUEST["portata"] ?>" />
    <input type="submit" name="invia" value="CERCA" id="send1" class="btn_blu" /> 
	<?php 
	if(is_numeric($_GET["ric"])){
		echo "<input type=\"hidden\" name=\"categoria\" value=\"{$_GET[ric]}\" />";	
	}	
	?>
    <div class="slider-wrapper theme-default">
            <div id="slider" class="nivoSlider">
            <?php 
			foreach((array) $slideE as $v){
				$image_attributes = wp_get_attachment_image_src( $v,'full');				
				if(exists($image_attributes[0]) !== false){
					echo '<img src="'.$template."/image.php?img=".$image_attributes[0].'&width=980&height=560" data-thumb="'.$image_attributes[0].'" alt="" />';
				}
			}	
			
			?>
            </div>
        </div>  
	<input type="button" name="newsearch" value="NUOVA RICERCA" id="newsearch" class="btn_blu" />        
    
</form>      
        </div>
		<div class="wpb_column vc_column_container vc_col-sm-9">        
        	<div class="res">
            <?php echo $out?>    
            </div>
        </div>
        </div>
	</div>
</div>
</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->
<script type="text/javascript">
jQuery(document).ready(function(){
 	jQuery('#Carousel').carousel({
        interval: 5000
    })
	jQuery('.carousel-inner .thumbnail').on("click",function(){
		jQuery('#portata').val(jQuery(this).data("id"));
		jQuery('#cerca').submit();
	})
	jQuery('#numero1,#citta1,#tipologia1').remove()
	jQuery('.wp_autosearch_input').attr('placeholder','NOME o NUMERO del locale')
	
    jQuery('#send1').on("click",function(event){
		 event.preventDefault();		
		 jQuery("#nome2").val(jQuery("INPUT[name='s']").val());		
		 jQuery("#citta2").val(jQuery("#citta").val());		 
		 jQuery("#prodotto2").val(jQuery("#prodotto").val());
		 jQuery("#cerca").submit();
	 })
	 
jQuery("#newsearch").on("click",function(){
	window.location = "/?p=325&newsearch=1";
})

jQuery('#close_servizi').on('click',function(){
	jQuery(".subservizi").hide( "slow");
	jQuery('#servizi').show();
	jQuery('#close_servizi').hide();	
})
jQuery("#servizi").on("click",function(){
	jQuery(".subservizi").show( "slow" );
	jQuery('#servizi').hide();
	jQuery('#close_servizi').show();	
})

jQuery(".subservizi A").on("click",function(){
	if(jQuery("#check-" + jQuery(this).data("id")).prop("checked") == true){
		jQuery("#check-" + jQuery(this).data("id")).prop("checked",false);	
	}else{
		jQuery("#check-" + jQuery(this).data("id")).prop("checked",true);		
	}
});
jQuery('.wp_autosearch_input').attr('placeholder','NOME o NUMERO del locale');
})

</script>
<?php } // fine desktop 



// inizio desktop
if(Scheda::isMobileDev()){?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        	<div class="row"><div class="wpb_column vc_column_container vc_col-sm-12">
            <div id="breadcrumb"><?php echo $breadcrumb ?></div>
            </div></div>
        	<div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
            	<div class="vc_column-inner ">
                <div class="wpb_wrapper">	
                <div class="sdvmenu">
                	<a href="javascript:;" class="tipolocale" ><?php _e("Tipo di Locale",CURRENT_THEME)?></a>
                	<a href="javascript:;" class="advsearch" id="servizi"><?php _e("Affina la ricerca",CURRENT_THEME)?></a>                    
                </div>
                <form name="cerca" id="cerca" method="post" action="<?php echo site_url()?>/cerca/?ricerca=1">                	
                	<?php					
					$args = array('child_of'=>182,'hide_empty'=> 0,'orderby'=> 'name','exclude' => array(1) ); //Food
					$cats = get_categories($args); 							
					$args['child_of'] = 183; // Drink
					$second = get_categories($args);
					$all = array_merge( $cats, $second );

                    echo "<div class=\"subservizi\" style=\"display:none\">";
					echo "<ul>";
					
					foreach((array) $all as $k=>$v){
						$fnd = "";
							echo "<li><a href=\"javascript:;\" data-id=\"check-{$v->term_id}\">{$v->name}</a>&nbsp;<input type=\"checkbox\" name=\"servizi[]\" value=\"{$v->slug}\" id=\"check-{$v->term_id}\" /> 
							</li>";
					};	
					
					echo '</ul></div>';
				   
				    ?>
                	<?php					
					$args = array('child_of'=>196,'hide_empty'=> 0,'orderby'=> 'name','exclude' => array(1) ); //Servizi
					$servizi = get_categories($args); 							
                    echo "<div class=\"subadv\" style=\"display:none\">
					  <div class=\"sdvmenu f20 blu centro pad5\">".__("Servizi",CURRENT_THEME)."</div>";
					echo "<ul>";
					
					foreach((array) $servizi as $k=>$v){
						$fnd = "";
							echo "<li><a href=\"javascript:;\" data-id=\"check-{$v->term_id}\">{$v->name}</a>&nbsp;<input type=\"checkbox\" name=\"servizi[]\" value=\"{$v->slug}\" id=\"check-{$v->term_id}\" /> 
							</li>";
					};	
					
					echo '</ul>';
					
					
					$args['child_of'] = 187; //Extra
					$extra = get_categories($args);

					echo "<div class=\"sdvmenu f20 blu centro pad5\">".__("Cibi alternativi",CURRENT_THEME)."</div>
					<ul>";
					
					foreach((array) $extra as $k=>$v){
						$fnd = "";
							echo "<li><a href=\"javascript:;\" data-id=\"check-{$v->term_id}\">{$v->name}</a>&nbsp;<input type=\"checkbox\" name=\"servizi[]\" value=\"{$v->slug}\" id=\"check-{$v->term_id}\" /> 
							</li>";
					};	
					
					echo '</ul>
					</div>';
				    ?>
                    <div id="intsearch">
                    	<div>
                    	 <div class="ct" style="border :1px solid hsl( 232, 50%, 73% ) !important">
                         	<input type="text" name="citta" id="citta" placeholder="<?php echo __("Città","twentyseventeen")?>" 
                            style="width:70% !important;float:left;border:0 !important"  value="<?php echo $_REQUEST["citta"] ?>" />
                            <a href="javascript:;" class="gmw-locate-btn" style="float:right;padding:10px 10px 0 0 "><img src="<?php echo get_stylesheet_directory_uri() ?>/images/gps.png" alt="Attiva GPS" /></a>
                         </div>   
                    	<input type="text" name="prodotto" id="prodotto" placeholder="<?php echo __("Cerca un Piatto/Vino/etc","twentyseventeen")?>" value="<?php echo $_REQUEST["ric"] ?>" />
                        </div>
                        <div>
                        <input type="hidden" name="portata" id="portata" value="<?php echo $_REQUEST["portata"] ?>" />
                        <input type="submit" name="invia" value="OK" id="send1" style="padding: 45px 10px;background-color:rgba(43, 108, 163, 1);margin-left:5px" /> </div>
                    </div>
                    <?php echo is_numeric($_REQUEST["ric"]) ? "<input type=\"hidden\" name=\"ric\" id=\"ric\" value=\"{$_REQUEST[ric]}\" />":""; ?>    
                     
                                              
                </form>
                
               <div id="Carousel" class="carousel slide">
                 
                <ol class="carousel-indicators">
                    <li data-target="#Carousel" data-slide-to="0" class="active"></li>
                    <li data-target="#Carousel" data-slide-to="1"></li>
                    <li data-target="#Carousel" data-slide-to="2"></li>
                </ol>
                 
                <!-- Carousel items -->
                <div class="carousel-inner">  
                <div class="item active">
                	<div class="row">
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="2"><img src="/wp-content/uploads/2018/05/primi-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="3"><img src="/wp-content/uploads/2018/05/carne-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="4"><img src="/wp-content/uploads/2018/05/pesce-2-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="14"><img src="/wp-content/uploads/2018/05/panini-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	</div>
                 </div>   
                  <div class="item">
                  	 <div class="row">
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="0"><img src="/wp-content/uploads/2018/05/pizze-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                 	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="7"><img src="/wp-content/uploads/2018/05/dolci-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail" data-id="10,19,20"><img src="/wp-content/uploads/2018/05/vini-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>
                	  <div class="col-md-1"><a href="#" class="thumbnail"  data-id="18"><img src="/wp-content/uploads/2018/05/aperitivi-100x100.jpg" alt="Image" style="max-width:100%;"></a></div>

                	</div><!--.row-->
                </div><!--.item-->
                 
                </div><!--.carousel-inner-->
                  <a data-slide="prev" href="#Carousel" class="left carousel-control">‹</a>
                  <a data-slide="next" href="#Carousel" class="right carousel-control">›</a>
              </div><!--.Carousel-->
                <div class="res">
		<?php echo $out ?>
	</div>
</div></div></div>
	
</div>     
 
</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->
<script type="text/javascript">
jQuery(document).ready(function(){
	 jQuery('#Carousel').carousel({
        interval: 5000
    })
	jQuery('.carousel-inner .thumbnail').on("click",function(){
		jQuery('#portata').val(jQuery(this).data("id"));
		jQuery('#cerca').submit();
	})
	jQuery('.wp_autosearch_input').attr('placeholder','NOME o NUMERO del locale')
	
	jQuery('.tipolocale').on("click",function(){
		jQuery(".subservizi").toggle();
	})
	
	jQuery('.advsearch').on("click",function(){
		jQuery(".subadv").toggle();
	})	
	jQuery(".subservizi A,.subadv A").on("click",function(){
	if(jQuery("#" + jQuery(this).data("id")).prop("checked") == true){
		jQuery("#" + jQuery(this).data("id")).prop("checked",false);	
	}else{
		jQuery("#" + jQuery(this).data("id")).prop("checked",true);		
	}
});

})
</script>
<?php } // fine desktop ?>

<?php get_footer();
