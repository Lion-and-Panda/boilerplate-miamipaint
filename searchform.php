<?php
/* ------------------------------------------------------------------------- *
 *  Search form
/* ------------------------------------------------------------------------- */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="search1" class="visually-hidde">Search</label>
	<input type="search" class="search-field search-query form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<input type="submit" id="searchsubmit" class="visually-hidde" value="Search" />
<!-- 	<button type="submit" class="search-submit">Search</span></button> -->
</form>
