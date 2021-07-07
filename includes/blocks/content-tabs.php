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


<section id="<?= $section_id; ?>" class="<?= $className; ?> cover relative <?= $classSpace ;?> <?php if($invert) { ?> inverted <?php } ?> <?php if($narrow) { ?> is-narrow <?php } ?>" style=" <?php if($content_background_color) { ?> background-color: <?= $content_background_color ?>; <?php } ?> <?php if($content_background_image) { ?> background-image: url(<?= $content_background_image ?>); <?php } ?> ">
  <div class="container zindex-2">
      <div class="columns">
          <div class="column is-12">

            <!--Heading-->
            <?php if($heading) { ?>
            <div class=" <?php if($invert) { ?> inverted <?php } ?>">
              <h2 class="headingLarge"  style="text-align: <?= $heading_alignment ;?>;"><?= $heading ?></h2>
            </div>
             <?php } ?>

             <!--ACCESSIBLE TABS-->
             <div class="tabsBlock" id="<?= $tab_id ?>">
               <div class="tabs-tabs" role="tablist">
                 <?php if( have_rows('tabs') ): ?>
                 <?php
                 while ( have_rows( 'tabs' ) ): the_row();
                 //Column Content
                 $tab_heading = get_sub_field( 'tab_heading' );
                 $tabID=preg_replace('/\s+/', '', $tab_heading);
                 ?>

                 <button class="relative" role="tab" aria-selected="false"  aria-controls="<?= $tabID ;?>_tab" id="<?= $tabID ;?>"><?= $tab_heading ;?></button>

                 <?php endwhile; ?>
                 <?php endif; ?>
               </div>

               <div id="<?= $tab_content_id ?>" class="tabsPanel">
                   <?php if( have_rows('tabs') ): ?>
                   <?php
                   while ( have_rows( 'tabs' ) ): the_row();

                   //Column Content
                     $tab_content = get_sub_field( 'tab_content' );
                     $tab_heading = get_sub_field( 'tab_heading' );
                     $tab_heading = get_sub_field( 'tab_heading' );
                     $tabID=preg_replace('/\s+/', '', $tab_heading);
                   ?>
                   <div class="tabPanel" tabindex="0" role="tabpanel" id="<?= $tabID ;?>_tab" aria-labelledby="<?= $tabID ;?>" hidden>
                    <?= $tab_content ;?>
                   </div>
                   <?php endwhile; ?>
                   <?php endif; ?>
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

jQuery('.tabs-tabs button').first().attr('aria-selected', 'true');
jQuery('.tabsPanel .tabPanel').first().removeAttr('hidden');

/*
*   This content is licensed according to the W3C Software License at
*   https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
*/
(function () {
  var tablist = document.querySelectorAll('[role="tablist"]')[0];
  var tabs;
  var panels;
  var delay = determineDelay();

  generateArrays();

  function generateArrays () {
    tabs = document.querySelectorAll('[role="tab"]');
    panels = document.querySelectorAll('[role="tabpanel"]');
  };

  // For easy reference
  var keys = {
    end: 35,
    home: 36,
    left: 37,
    up: 38,
    right: 39,
    down: 40,
    delete: 46
  };

  // Add or substract depending on key pressed
  var direction = {
    37: -1,
    38: -1,
    39: 1,
    40: 1
  };

  // Bind listeners
  for (i = 0; i < tabs.length; ++i) {
    addListeners(i);
  };

  function addListeners (index) {
    tabs[index].addEventListener('click', clickEventListener);
    tabs[index].addEventListener('keydown', keydownEventListener);
    tabs[index].addEventListener('keyup', keyupEventListener);

    // Build an array with all tabs (<button>s) in it
    tabs[index].index = index;
  };

  // When a tab is clicked, activateTab is fired to activate it
  function clickEventListener (event) {
    var tab = event.target;
    activateTab(tab, false);
  };

  // Handle keydown on tabs
  function keydownEventListener (event) {
    var key = event.keyCode;

    switch (key) {
      case keys.end:
        event.preventDefault();
        // Activate last tab
        activateTab(tabs[tabs.length - 1]);
        break;
      case keys.home:
        event.preventDefault();
        // Activate first tab
        activateTab(tabs[0]);
        break;

      // Up and down are in keydown
      // because we need to prevent page scroll >:)
      case keys.up:
      case keys.down:
        determineOrientation(event);
        break;
    };
  };

  // Handle keyup on tabs
  function keyupEventListener (event) {
    var key = event.keyCode;

    switch (key) {
      case keys.left:
      case keys.right:
        determineOrientation(event);
        break;
      case keys.delete:
        determineDeletable(event);
        break;
    };
  };

  // When a tablistâ€™s aria-orientation is set to vertical,
  // only up and down arrow should function.
  // In all other cases only left and right arrow function.
  function determineOrientation (event) {
    var key = event.keyCode;
    var vertical = tablist.getAttribute('aria-orientation') == 'vertical';
    var proceed = false;

    if (vertical) {
      if (key === keys.up || key === keys.down) {
        event.preventDefault();
        proceed = true;
      };
    }
    else {
      if (key === keys.left || key === keys.right) {
        proceed = true;
      };
    };

    if (proceed) {
      switchTabOnArrowPress(event);
    };
  };

  // Either focus the next, previous, first, or last tab
  // depening on key pressed
  function switchTabOnArrowPress (event) {
    var pressed = event.keyCode;

    for (x = 0; x < tabs.length; x++) {
      tabs[x].addEventListener('focus', focusEventHandler);
    };

    if (direction[pressed]) {
      var target = event.target;
      if (target.index !== undefined) {
        if (tabs[target.index + direction[pressed]]) {
          tabs[target.index + direction[pressed]].focus();
        }
        else if (pressed === keys.left || pressed === keys.up) {
          focusLastTab();
        }
        else if (pressed === keys.right || pressed == keys.down) {
          focusFirstTab();
        };
      };
    };
  };

  // Activates any given tab panel
  function activateTab (tab, setFocus) {
    setFocus = setFocus || true;
    // Deactivate all other tabs
    deactivateTabs();

    // Remove tabindex attribute
    tab.removeAttribute('tabindex');

    // Set the tab as selected
    tab.setAttribute('aria-selected', 'true');

    // Get the value of aria-controls (which is an ID)
    var controls = tab.getAttribute('aria-controls');

    // Remove hidden attribute from tab panel to make it visible
    document.getElementById(controls).removeAttribute('hidden');

    // Set focus when required
    if (setFocus) {
      tab.focus();
    };
  };

  // Deactivate all tabs and tab panels
  function deactivateTabs () {
    for (t = 0; t < tabs.length; t++) {
      tabs[t].setAttribute('tabindex', '-1');
      tabs[t].setAttribute('aria-selected', 'false');
      tabs[t].removeEventListener('focus', focusEventHandler);
    };

    for (p = 0; p < panels.length; p++) {
      panels[p].setAttribute('hidden', 'hidden');
    };
  };

  // Make a guess
  function focusFirstTab () {
    tabs[0].focus();
  };

  // Make a guess
  function focusLastTab () {
    tabs[tabs.length - 1].focus();
  };

  // Detect if a tab is deletable
  function determineDeletable (event) {
    target = event.target;

    if (target.getAttribute('data-deletable') !== null) {
      // Delete target tab
      deleteTab(event, target);

      // Update arrays related to tabs widget
      generateArrays();

      // Activate the closest tab to the one that was just deleted
      if (target.index - 1 < 0) {
        activateTab(tabs[0]);
      }
      else {
        activateTab(tabs[target.index - 1]);
      };
    };
  };

  // Deletes a tab and its panel
  function deleteTab (event) {
    var target = event.target;
    var panel = document.getElementById(target.getAttribute('aria-controls'));

    target.parentElement.removeChild(target);
    panel.parentElement.removeChild(panel);
  };

  // Determine whether there should be a delay
  // when user navigates with the arrow keys
  function determineDelay () {
    var hasDelay = tablist.hasAttribute('data-delay');
    var delay = 0;

    if (hasDelay) {
      var delayValue = tablist.getAttribute('data-delay');
      if (delayValue) {
        delay = delayValue;
      }
      else {
        // If no value is specified, default to 300ms
        delay = 300;
      };
    };

    return delay;
  };

  //
  function focusEventHandler (event) {
    var target = event.target;

    setTimeout(checkTabFocus, delay, target);
  };

  // Only activate tab on focus if it still has focus after the delay
  function checkTabFocus (target) {
    focused = document.activeElement;

    if (target === focused) {
      activateTab(target, false);
    };
  };
}());
</script>
<!-- End Tabs -->
