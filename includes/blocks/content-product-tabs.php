<?php
//Background
$content_background_color = get_field( 'background_color' );
$content_background_image = get_field( 'background_image' );
$size = 'big-feature';
if ($content_background_image) {
  $content_background_image = $content_background_image[ 'sizes' ][ $size ];
}

//Content
$heading = get_field('heading');
$heading_alignment = get_field('heading_alignment');
$brand_about = get_field('brand_about');
$brand_logo = get_field('brand_logo');
if ( $brand_logo) {
  $image_alt = $brand_logo['alt'];
  $sizeImg = 'full'; // (thumbnail, medium, large, full or custom size)
  $brand_logo = $brand_logo[ 'sizes' ][ $size ];
}

//Style
$content_aligment = get_field( 'block_content_alignment' );
$content_spacing = get_field( 'content_spacing' );
$invert = get_field( 'title_invert' );
$narrow = get_field( 'narrow' );

//Overlay
$show_overlay = get_field( 'show_overlay' );
$overlay_type= get_field( 'overlay_type' );
$overlay_color= get_field( 'overlay_color' );
$gradient_color_1= get_field( 'gradient_color_1' );
$gradient_color_2= get_field( 'gradient_color_2' );
$gradient_direction= get_field( 'gradient_direction' );
$overlay_opacity= get_field( 'overlay_opacity' );

$classSpace = 'is-' . $content_spacing;

// Create class attribute allowing for custom "className"
 $className = 'tabHolder';
 if( !empty($block['className']) ) {
     $className .= ' ' . $block['className'];
 }

 // Create id attribute allowing for custom "anchor" value.
 $section_id = 'tabHolder-' . $block['id'];
 if( !empty($block['anchor']) ) {
     $section_id = $block['anchor'];
 }

?>

<!--give each tab block a random id-->
<?php
$tab_id = 'tabs_' . rand();
$tab_content_id = 'tab-content_' . rand();
?>

<!-- TABS -->


<section id="<?= $section_id; ?>" class="<?= $className; ?> productTabs cover relative <?= $classSpace ;?> <?php if($invert) { ?> inverted <?php } ?> <?php if($narrow) { ?> is-narrow <?php } ?>" style=" <?php if($content_background_color) { ?> background-color: <?= $content_background_color ?>; <?php } ?> <?php if($content_background_image) { ?> background-image: url(<?= $content_background_image ?>); <?php } ?> ">
  <div class="container zindex-2">
      <div class="columns is-mobile is-multiline">

        <?php if($brand_logo) { ?>
          <div class="column is-3-mobile is-1-desktop">
            <img src="<?= $brand_logo; ?>" class="brandLogo" alt="<?=  $image_alt ?>" loading="lazy" />
          </div>
        <?php } ?>

          <div class="column is-9-mobile is-4-desktop">
            <?php if($heading) { ?>
              <div class=" <?php if($invert) { ?> inverted <?php } ?>">
                <h2 class="headingLarge"  style="text-align: <?= $heading_alignment ;?>;"><?= $heading ?></h2>
              </div>
             <?php } ?>
            <?php if($brand_about) { ?>
              <?= $brand_about ;?>
            <?php } ?>
          </div>


          <div class="column is-12-mobile is-6-desktop">

            <div class="columns productTab">

              <div class="column is-4">
                <!--tabs-->
                  <div class="tabs" id="<?= $tab_id ?>">
                    <ul class="tabs-tabs" role="tablist">
                      <?php if( have_rows('tabs') ): ?>
                      <?php
                      while ( have_rows( 'tabs' ) ): the_row();

                      //Column Content
                      $tab_heading = get_sub_field( 'tab_heading' );
                      ?>
                      <li data-tab="<?= $tab_heading ;?>" role="tab">
                        <a href="#" onclick="return false;"><?= $tab_heading ;?></a>
                      </li>
                      <?php endwhile; ?>
                      <?php endif; ?>
                    </ul>
                  </div>
              </div>

              <div class="column is-8">
                <div id="<?= $tab_content_id ?>">
                  <?php if( have_rows('tabs') ): ?>
                  <?php
                  while ( have_rows( 'tabs' ) ): the_row();

                  //Column Content
                    $tab_content = get_sub_field( 'tab_content' );
                    $tab_heading = get_sub_field( 'tab_heading' );
                    $tab_button = get_sub_field('product_link');

                  //Button setup copied from content-hero.php
                    if ($tab_button) {
                      $tab_button_target = $tab_button[ 'target' ] ? $tab_button[ 'target' ] : '_self';
                    }

                  ?>
                  <div class="tab-content" data-content="<?= $tab_heading ;?>" aria-hidden="true" role="tabpanel" style="text-align: <?= $content_aligment ;?>">
                    <h4><?= $tab_heading ;?></h4>
                    <?= $tab_content ;?>


                    <?php if($tab_button) { ?>
                    <div class="buttonHolder">
                      <a class="button" href="<?= $tab_button['url']; ?>" target="<?= esc_attr($tab_button_target); ?>">
                          <?= esc_html($tab_button['title']); ?>
                      </a>
                    </div>
                    <?php } ?>
                  </div>
                  <?php endwhile; ?>
                  <?php endif; ?>
                </div>
              </div>

            </div>


    </div>
  </div><!--end columns-->
  </div><!--end container-->

  <!--overlay-->
  <?php if($show_overlay) { ?>
  <div class="overlay" style="<?php if ($overlay_type === 'color') { ?> background-color: <?= $overlay_color ?>; opacity: <?= $overlay_opacity ?>;<?php } ?> <?php if ($overlay_type === 'gradient') { ?>background-image: linear-gradient(to <?= $gradient_direction ?>, <?= $gradient_color_1 ?> , <?= $gradient_color_2 ?>); opacity: <?= $overlay_opacity ?>; <?php } ?> "></div>
  <?php } ?>
  <!--end overlay-->

</section>

<script>
  jQuery(document).ready(function() {
    jQuery('#<?= $tab_id ?> .tabs-tabs').each(function(index) {
  	jQuery(this).children('li').first().addClass('is-active');
  });

    jQuery('#<?= $tab_content_id ?>').each(function(index) {
  	jQuery(this).children('').first().addClass('is-active');
    jQuery(this).children('').first().attr('aria-hidden', 'false');
  });

  jQuery('#<?= $tab_id ?> li').on('click', function() {
  var tab = $(this).data('tab');
  console.log(tab);
  jQuery('#<?= $tab_id ?> li').removeClass('is-active');
  jQuery(this).addClass('is-active');

  jQuery('#<?= $tab_content_id ?> .tab-content').removeClass('is-active');
  jQuery('#<?= $tab_content_id ?> .tab-content').attr('aria-hidden', 'true');
  jQuery('#<?= $tab_content_id ?> [data-content="' + tab + '"]').addClass('is-active');
  jQuery('#<?= $tab_content_id ?> [data-content="' + tab + '"]').attr('aria-hidden', 'false');
  });
  });
</script>
<!-- End Tabs -->
