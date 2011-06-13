<?php
/*
Plugin Name: Catalog Page
Plugin URI: http://wordpress.org/extend/plugins/catalog-page/
Description: Crea facilmente la pagina per il tuo catalogo..
Author: Vincenzo La Rosa
Version: 1.0.4
Author URI: http://www.vincenzolarosa.it 
*/
/*option default*/
add_option('catalog_page_ndisplay','10');
add_option('catalog_page_fbbutton','1');
add_option('catalog_page_fbsend','1');
add_option('catalog_page_twbutton','0');
add_option('catalog_page_meemibutton','1');
add_option('catalog_page_dettagli','1');
add_option('catalog_page_img_w','140');
add_option('catalog_page_img_h','140');
/*end option default*/

function catalog_page_table()
{
	/*Read option*/
	$catalog_page_ndispaly=get_option('catalog_page_ndisplay');
	$catalog_page_fbbutton=get_option('catalog_page_fbbutton');
	$catalog_page_fbsend=get_option('catalog_page_fbsend');
	$catalog_page_twbutton=get_option('catalog_page_twbutton');
	$catalog_page_meemibutton=get_option('catalog_page_meemibutton');
	$catalog_page_img_w=get_option('catalog_page_img_w');
	$catalog_page_img_h=get_option('catalog_page_img_h');
	$catalog_page_dettagli=get_option('catalog_page_dettagli');
	/*End read*/
	$dir = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	$args=array('post_type'=>'servizi','posts_per_page'=>$catalog_page_ndispaly);
    $loop=new WP_Query($args);
    if (($catalog_page_fbbutton=='1')||($catalog_page_fbsend=='1')){
		$text.="<script src=\"http://connect.facebook.net/en_US/all.js#appId=220228844661602&amp;xfbml=1\"></script><div id=\"fb-root\"></div>";
	}
	$text.="<table border=\"0\">";
	while ($loop->have_posts()):$loop->the_post();
            global $more;   
            $more=0;
            /*Ottiene i dati del costum post*/
            $catalog_id=get_the_ID();
            if (has_post_thumbnail()) {
                $catalog_img=get_the_post_thumbnail($catalog_id,array($catalog_page_img_h,$catalog_page_img_w));
            }else{
                $catalog_img="<img src=\"".$dir."/img/404.gif\" width=\"".$catalog_page_img_w."\" height=\"".$catalog_page_img_h."\"/>";/*Immagine da far scegliere*/
            }
            $catalog_permalink=get_permalink($catalog_id);
            $catalog_title=get_the_title($catalog_id);  
            $catalog_content=get_the_content('');
            /*----------------------------*/
            $text.="<tr><td rowspan='4'>".$catalog_img."</td></tr><tr><td>
            <p align=\"center\"><b><a href=\"".$catalog_permalink."\" 
            title=\"".$catalog_title."\">".$catalog_title."</a></b>
            </p></td></tr><tr>
            <td><p align=\"center\">".$catalog_content."</p></td>
            </tr><tr>
            <td><p align=\"right\">";
			if ($catalog_page_fbbutton=='1'){
				$text.="<fb:like href=\"".$catalog_permalink."\" width=\"10\" show_faces=\"false\" layout=\"button_count\" send=\"false\"></fb:like>  ";
			}
			if ($catalog_page_fbsend=='1'){
				$text.="<fb:send href=\"".$catalog_permalink."\"></fb:send>  ";
			}
			if ($catalog_page_meemibutton=='1'){
				$text.=" <a href=\"http://meemi.com/meme/".$catalog_title." - ".$catalog_permalink."\" target=\"_blank\"><img src=\"http://meemi.com/stc/i/button/blue_this.png\" /></a>";
			}
			if ($catalog_page_twbutton=='1'){
				$text.="twbutton";
			}
			if ($catalog_page_dettagli=='1'){
			$text.="  <a href=\"".$catalog_permalink."\" rel=\"bookmark\" title=\"Dettagli: ".$catalog_title."\"><img src=\"".$dir."/img/button-dettagli.gif\" /></a></p></td>";
			}
			$text.="</tr>";
	endwhile;
	$text.="</table><br />";
	return $text;
}
function catalog_page_post_type()
{
	register_post_type('Servizi', array(	'label' => 'Servizi','description' => 'Raccolta di tutti i servizi e prodotti offerti da VincenzoLaRosa.it','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => 'servizi'),'query_var' => true,'supports' => array('title','editor','comments','thumbnail','author',),'labels' => array (
  'name' => 'Servizi',
  'singular_name' => 'Servizi',
  'menu_name' => 'Servizi',
  'add_new' => 'Nuovo Servizio',
  'add_new_item' => 'Aggiungi Nuovo Servizio',
  'edit' => 'Modifica',
  'edit_item' => 'Modifica Servizio',
  'new_item' => 'Nuovo Servizio',
  'view' => 'Mostra',
  'view_item' => 'Mostra servizio',
  'search_items' => 'Cerca Servizio',
  'not_found' => 'Servizio non trovato',
  'not_found_in_trash' => 'Nessun servizio trovato nel cestino',
  'parent' => 'Parent Servizi',
),) );	
}
function catalog_page_menu()
{   
	add_submenu_page('edit.php?post_type=servizi','Catalog Page Opzioni', 'Opzioni', 'manage_options', 'opzioni', 'catalog_page_option');
	//add_action( 'admin_init', 'catalog_page_register_mysettings' );
}
function catalog_page_option()
{
	$action=$_POST['action'];
	if ($action=='update' ) {
		if (get_option('catalog_page_fbbutton')!=$_POST['catalog_page_fbbutton'])
		{
			update_option('catalog_page_fbbutton',$_POST['fbbutton']);
		}
		if (get_option('catalog_page_fbsend')!=$_POST['catalog_page_fbsend'])
		{
			update_option('catalog_page_fbsend',$_POST['fbsend']);
		}
		if (get_option('catalog_page_meemibutton')!=$_POST['catalog_page_meemibutton'])
		{
			update_option('catalog_page_meemibutton',$_POST['meemibutton']);
		}
		if (get_option('catalog_page_ndisplay')!=$_POST['catalog_page_ndisplay'])
		{
		update_option('catalog_page_ndisplay',$_POST['catalog_page_ndisplay']);
		}
		if (get_option('catalog_page_img_h')!=$_POST['catalog_page_img_h'])
		{
		update_option('catalog_page_img_h',$_POST['catalog_page_img_h']);
		}
		if (get_option('catalog_page_img_w')!=$_POST['catalog_page_img_w'])
		{
		update_option('catalog_page_img_w',$_POST['catalog_page_img_w']);
		}
		if (get_option('catalog_page_dettagli')!=$_POST['catalog_page_dettagli'])
		{
		update_option('catalog_page_dettagli',$_POST['catalog_page_dettagli']);
		}
?>
<div id="message" class="updated fade">
  <p><strong>
    <?php _e('Options saved.'); ?>
    </strong></p>
</div>
<? } ?>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=174427065949905&amp;xfbml=1"></script><script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<div class="wrap">
	<h2>Catalog Page Opzioni</h2>
		<form method="post" action="options.php">
			Quanti servizi e prodotti vuoi mostrare per ogni pagina? 
			<select name="catalog_page_ndisplay">
				<option value="-1" <?if (get_option('catalog_page_ndisplay')=='-1'){echo "selected=\"selected\"";}?>>Tutti</option>
				<? 
				for ($i=1;$i<17;$i++){
				echo "<option value=\"".$i."\"";
				if ($i==get_option('catalog_page_ndisplay')){
					echo " selected=\"selected\"";
				}
				echo ">".$i."</option>";				
				}
				?>
			</select>
			<br />
			<h3>Dimensione Immagine</h3>
			<table border="0">
			<tr>
			<td>Larghezza</td><td><input type="text" name="catalog_page_img_w" value="<? echo get_option('catalog_page_img_w');?>" size="5"/></td>
			<td>Altezza</td><td><input type="text" name="catalog_page_img_h" value="<? echo get_option('catalog_page_img_h'); ?>" size="5"/></td>
			</tr>
			</table>
			<h3>Button</h3>
			<table border="0">
			<tr>
			<td><input type="checkbox" name="catalog_page_fbbutton" value="1" <? if (get_option('catalog_page_fbbutton')=='1'){?> checked="true" <?}?> value="Like Button">  Like Button</td>
			<td><input type="checkbox" name="catalog_page_fbsend" value="1" <? if (get_option('catalog_page_fbsend')=='1'){?> checked="true" <?}?> value="Send Button">  Send Button</td>
			<td><input type="checkbox" name="catalog_page_meemibutton" value="1" <? if (get_option('catalog_page_meemibutton')=='1'){?> checked="true" <?}?> value="Meemi Button">  Meemi Button</td>
			<td><input type="checkbox" name="catalog_page_dettagli" value="1" <? if (get_option('catalog_page_dettagli')=='1'){?> checked="true" <?}?> value="Dettagli Button">  Mostra dettagli</td>
			</tr>
			</table>
			<p class="submit"><input type="submit" value="<?php _e('Save') ?>" class="button-primary" name="catalog_page_save"/></p>
			<?php wp_nonce_field('update-options'); ?>
			<input type="hidden" name="page_options" value="catalog_page_dettagli,catalog_page_fbbutton,catalog_page_fbsend,catalog_page_meemibutton,catalog_page_ndisplay,catalog_page_img_h,catalog_page_img_w">
			<input type="hidden" name="action" value="update" />
		</form>
	<h2>Plugin Info</h2>
	<p>Usa lo shortcode <code>[catalog_page]</code> per inserire la lista dei servizi nella pagina.</p>
	<h2>Support Plugin</h2>
	<p>
	<table border="0">
	<tr>
	<td>Clicca Mi Piace</td>
	<td align="center"><fb:like href="http://www.facebook.com/vincenzolarosa.it" send="false" layout="button_count" width="150" show_faces="true" font="verdana"></fb:like></td>
	<td>Seguimi su twitter</td>
	<td align="center"><a href="http://twitter.com/enzolarosa" class="twitter-follow-button" data-lang="it">Seguimi</a></td>
	</tr>
	</table>
	</p>
	<h2>Chi usa il plugin</h2>
	<p><fb:facepile href="www.facebook.com/vincenzolarosa.it" width="600" max_rows="1"></fb:facepile></p>
</div>
<?
}


add_action('admin_menu', 'catalog_page_menu');
add_action('init','catalog_page_post_type'); /*aggiunge il tipo di post */
add_shortcode('catalog_page', 'catalog_page_table'); /*aggiunge lo short code [catalog_page]*/
?>