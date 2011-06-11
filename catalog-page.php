<?php
/*
Plugin Name: Catalog Page
Plugin URI: http://www.vincenzolarosa.it/news/wp-vlr/catalog-page.html
Description: Crea facilmente la pagina per il tuo catalogo..
Author: Vincnzo La Rosa
Version: 0.1.2
Author URI: http://www.vincenzolarosa.it 
*/
/*funzione per creare la tabella*/
function vlr_catalog_page_table()
{
	$args=array('post_type'=>'servizi','posts_per_page'=>15);
        $loop=new WP_Query($args);
        $text="<script src=\"http://connect.facebook.net/en_US/all.js#appId=220228844661602&amp;xfbml=1\"></script>
	<table border=\"0\">";
	while ($loop->have_posts()):$loop->the_post();
            global $more;   
            $more=0;
            /*Ottiene i dati del costum post*/
            $catalog_id=get_the_ID();
            if (has_post_thumbnail()) {
                $catalog_img=get_the_post_thumbnail($catalog_id,array(140,140));
            }else{
                $catalog_img="<a href=\"http://www.vincenzolarosa.it\" target=\"_blank\"><img src=\"http://www.vincenzolarosa.it/wp-content/uploads/2011/06/not_found.gif\" width=\"150\" height=\"150\"/></a>";
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
            <td><p align=\"right\"><div id=\"fb-root\"></div>
            <fb:like href=\"".$catalog_permalink."\" send=\"true\" layout=\"button_count\" width=\"70\" show_faces=\"false\"></fb:like>
            <a href=\"http://meemi.com/meme/".$catalog_title." - ".$catalog_permalink."\" target=\"_blank\"><img src=\"http://meemi.com/stc/i/button/blue_this.png\" /></a>
			<a href=\"".$catalog_permalink."\" rel=\"bookmark\" target=\"_blank\"title=\"Dettagli: ".$catalog_title."\">Dettagli</a></p></td>
            </tr>";
	endwhile;
	$text.="</table>";
	return $text;
}
function vlr_catalog_page_post_type()
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
add_action('init','vlr_catalog_page_post_type'); /*aggiunge il tipo di post */
add_shortcode('catalog_page', 'vlr_catalog_page_table'); /*aggiunge lo short code [catalog_page]*/
?>