		</main>
		<footer class="site-footer">
			<?php wp_nav_menu( array(
				'container' => false,
				'menu' => 'Project Nav'
			) );?>
			<a href="#subscribe">Subscribe</a>
			<div class="subform">
				<div class="close-subform"></div>
				<form id="subscribe">
					<input id="name" type="text" placeholder="First & Last Name">
					<input id="email" type="email" placeholder="Email Address">
					<button type="submit">Submit</button>
				</form>	
			</div>		
		</footer>
	<?php wp_footer(); ?>
	</body>
</html>
