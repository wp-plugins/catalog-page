<?php
/*
Plugin Name: Catalog Page
Plugin URI: http://wordpress.org/extend/plugins/catalog-page/
Description: Crea facilmente la pagina per il tuo catalogo..
Author: Vincenzo La Rosa
Version: 1.1.2.1
Author URI: http://www.vincenzolarosa.it 
*/
/*option default*/
add_option('catalog_page_ndisplay','10');
add_option('catalog_page_fbbutton','1');
add_option('catalog_page_fbsend','1');
add_option('catalog_page_meemibutton','1');
add_option('catalog_page_dettagli','1');
add_option('catalog_page_img_w','140');
add_option('catalog_page_img_h','140');
add_option('catalog_page_css','');
/*end option default*/

function catalog_page_table(){
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
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args=array('post_type'=>'servizi','posts_per_page'=>$catalog_page_ndispaly,'paged'=>$paged);
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
            <td><p align=\"left\">".$catalog_content."</p></td>
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
			$text.="  <a href=\"".$catalog_permalink."\" rel=\"bookmark\" title=\"Dettagli: ".$catalog_title."\"><img src=\"".$dir."/img/button-dettagli.gif\" /></a>";
			}
			$text.="</p></td></tr>";
		endwhile;
	$text.="</table>";
	//page navigation code
	global $wp_rewrite;
	$pages = '';
	$max = $loop->max_num_pages;
	if (!$current = get_query_var('paged')) $current = 1;
	$a['base'] = ($wp_rewrite->using_permalinks())?user_trailingslashit(trailingslashit(remove_query_arg('s',get_pagenum_link(1))).'page/%#%/', 'paged' ) : @add_query_arg('paged','%#%');
	if( !empty($loop->query_vars['s']) ) $a['add_args'] = array( 's' => get_query_var( 's' ) );
	$a['total'] = $max;
	$a['current'] = $current;
	$total = 1; //1 - display the text "Page N of N", 0 - not display
	$a['mid_size'] = 5; //how many links to show on the left and right of the current
	$a['end_size'] = 1; //how many links to show in the beginning and end
	$a['prev_text'] = '&laquo; '.__('Indietro','catalog_page'); //text of the "Previous page" link
	$a['next_text'] = __('Avanti','catalog_page').' &raquo;'; //text of the "Next page" link
	if ($max > 1) $text.= '<div class="navigation">';
	if ($total == 1 && $max > 1) $pages = '<span class="pages">'.__('Pagina','catalog_page'). $current .' '.__('di','catalog_page').' '.$max.'</span>'."\r\n";
	$text.= $pages."<br />".paginate_links($a);
	if ($max > 1) $text.='</div>';
	//end page navigation code
	
	return $text;
}
function catalog_page_post_type(){
	register_post_type('Servizi', array('label' => 'Servizi','description' => 'Raccolta di tutti i servizi e prodotti offerti.','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => 'servizi'),'query_var' => true,'supports' => array('title','editor','comments','thumbnail','author',),'labels' => array (
  'name' => 'Servizi',
  'singular_name' => __('Servizi','catalog_page'),
  'menu_name' => __('Servizi','catalog_page'),
  'add_new' => __('Nuovo Servizio','catalog_page'),
  'add_new_item' => __('Aggiungi nuovo servizio','catalog_page'),
  'edit' => __('Modifica','catalog_page'),
  'edit_item' => __('Modifica servizio','catalog_page'),
  'new_item' => __('Nuovo servizio','catalog_page'),
  'view' => __('Mostra','catalog_page'),
  'view_item' => __('Mostra servizio','catalog_page'),
  'search_items' => __('Cerca Servizio','catalog_page'),
  'not_found' => __('Servizio non trovato','catalog_page'),
  'not_found_in_trash' => __('Nessun servizio trovato nel cestino','catalog_page'),
  'parent' => 'Parent Servizi',
),) );	
}
function catalog_page_menu(){   
	add_submenu_page('edit.php?post_type=servizi','Catalog Page Opzioni', __('Opzioni','catalog_page'), 'manage_options', 'opzioni', 'catalog_page_option');
}
function catalog_page_option(){
	$action=$_POST['action'];
	if ($action=='update' ) {
		if (get_option('catalog_page_fbbutton')!=$_POST['catalog_page_fbbutton'])
		{
			update_option('catalog_page_fbbutton',$_POST['catalog_page_fbbutton']);
		}
		if (get_option('catalog_page_fbsend')!=$_POST['catalog_page_fbsend'])
		{
			update_option('catalog_page_fbsend',$_POST['catalog_page_fbsend']);
		}
		if (get_option('catalog_page_meemibutton')!=$_POST['catalog_page_meemibutton'])
		{
			update_option('catalog_page_meemibutton',$_POST['catalog_page_meemibutton']);
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
		if (get_option('catalog_page_css')!=$_POST['catalog_page_css'])
		{
			update_option('catalog_page_css',$_POST['catalog_page_css']);
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
	<h2>Catalog Page <? _e('Opzioni','catalog_page'); ?></h2>
		<form method="post" action="options.php">
			<? _e('Quanti servizi e prodotti vuoi mostrare per ogni pagina?','catalog_page'); ?>
			<select name="catalog_page_ndisplay">
				<option value="-1" <?if (get_option('catalog_page_ndisplay')=='-1'){echo "selected=\"selected\"";}?>>Tutti</option>
				<?
				for ($i=1;$i<17;$i++){
					echo "<option value=\"".$i."\"";
					if ($i==get_option('catalog_page_ndisplay')){
						echo " selected=\"selected\"";
					}
					echo ">".$i."</option>";				
				}?>
			</select>
			<br />
			<h3>Css Page Navi</h3>
			<p><? _e('Per personalizzare la barra di navigazione puoi usare i seguente tag <code>navigation</code> riferito all\'elemnto','catalog_page');?> <b>div</b><br />
			<? _e('e','catalog_page');?> <code>pages</code> <? _e('riferito invece all\'elemento','catalog_page');?> <b>span</b>
			</p>
			<textarea name="catalog_page_css" rows="3" cols="30">
			<? echo get_option('catalog_page_css'); ?>
			</textarea>
			<br />
			<h3><? _e('Dimensione Immagine','catalog_page');?></h3>
			<table border="0">
			<tr>
			<td><? _e('Larghezza','catalog_page');?></td><td><input type="text" name="catalog_page_img_w" value="<? echo get_option('catalog_page_img_w');?>" size="5"/></td>
			<td><? _e('Altezza','catalog_page');?></td><td><input type="text" name="catalog_page_img_h" value="<? echo get_option('catalog_page_img_h'); ?>" size="5"/></td>
			</tr>
			</table>
			<h3><? _e('Social Button','catalog_page');?></h3>
			<table border="0">
			<tr>
			<td><input type="checkbox" name="catalog_page_fbbutton" value="1" <? if (get_option('catalog_page_fbbutton')=='1'){?> checked="true" <?}?> value="Like Button">  <?_e('Like Button','catalog_page');?></td>
			<td><input type="checkbox" name="catalog_page_fbsend" value="1" <? if (get_option('catalog_page_fbsend')=='1'){?> checked="true" <?}?> value="Send Button">  <?_e('Send Button','catalog_page');?></td>
			<td><input type="checkbox" name="catalog_page_meemibutton" value="1" <? if (get_option('catalog_page_meemibutton')=='1'){?> checked="true" <?}?> value="Meemi Button">  <?_e('Meemi Button','catalog_page');?></td>
			<td><input type="checkbox" name="catalog_page_dettagli" value="1" <? if (get_option('catalog_page_dettagli')=='1'){?> checked="true" <?}?> value="Dettagli Button">  <?_e('Mostra dettagli','catalog_page');?></td>
			</tr>
			</table>
			<p class="submit"><input type="submit" value="<?php _e('Save') ?>" class="button-primary" name="catalog_page_save"/></p>
			<?php wp_nonce_field('update-options'); ?>
			<input type="hidden" name="page_options" value="catalog_page_css,catalog_page_dettagli,catalog_page_fbbutton,catalog_page_fbsend,catalog_page_meemibutton,catalog_page_ndisplay,catalog_page_img_h,catalog_page_img_w">
			<input type="hidden" name="action" value="update" />
		</form>
	<h2><? _e('Informazioni Plugin','catalog_page');?></h2>
	<p><? _e('Usa lo shortcode <code>[catalog_page]</code> per inserire la lista dei servizi nella pagina.','catalog_page');?></p>
	<h2><? _e('Support Plugin','catalog_page');?></h2>
	<p><table border="0"><tr>
	<td><?_e('Clicca Mi Piace','catalog_page');?></td>
	<td><fb:like href="http://www.facebook.com/vincenzolarosa.it" send="false" layout="button_count" width="150" show_faces="false" font="verdana"></fb:like></td>
	<td><?_e('Seguimi su twitter','catalog_page');?></td>
	<td><a href="http://twitter.com/enzolarosa" class="twitter-follow-button" data-lang="it">Seguimi</a></td>
	</tr>
	<tr>
	<td><script type='text/javascript'><!--//<![CDATA[
   var m3_u = (location.protocol=='https:'?'https://netad.it/www/delivery/ajs.php':'http://netad.it/www/delivery/ajs.php');
   var m3_r = Math.floor(Math.random()*99999999999);
   if (!document.MAX_used) document.MAX_used = ',';
   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
   document.write ("?zoneid=14");
   document.write ('&amp;cb=' + m3_r);
   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
   document.write ("&amp;loc=" + escape(window.location));
   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
   if (document.context) document.write ("&context=" + escape(document.context));
   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
   document.write ("'><\/scr"+"ipt>");
//]]>--></script><noscript><a href='http://netad.it/www/delivery/ck.php?n=a9f4d9fd&amp;cb=57567' target='_blank'><img src='http://netad.it/www/delivery/avw.php?zoneid=14&amp;cb=91246356&amp;n=a9f4d9fd' border='0' alt='' /></a></noscript>
</td>
	</tr>
	</table></p>
</div>
<?
}
function catalog_style_css(){
	echo "<!--Catalog Page Css -->
<style type=\"text/css\">
".get_option('catalog_page_css')."
</style>
<!--End Catalog Page Css-->";

}
add_action('wp_head', 'catalog_style_css');
add_action('admin_menu', 'catalog_page_menu');
add_action('init','catalog_page_post_type'); /*aggiunge il tipo di post */
add_shortcode('catalog_page', 'catalog_page_table'); /*aggiunge lo short code [catalog_page]*/
?>