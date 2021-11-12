		</main>
		<footer class="site-footer">
			<?php wp_nav_menu( array(
				'container' => false,
				'menu' => 'Project Nav'
			) );?>
			<a href="#subscribe">Subscribe</a>
			<div class="subform">
				<div class="close-subform"></div>
				<form action="https://udf.us5.list-manage.com/subscribe/post?u=340b36fec9e8d11359e3786e8&amp;id=9cb3f0e66c" method="post" id="subscribe">
					<input id="name" type="text" name="NAME" id="mce-NAME" placeholder="First & Last Name">
					<input id="email" type="email" name="EMAIL" id="mce-EMAIL" placeholder="Email Address">
					<div style="position: absolute; left: -3000px;"><input type="text" name="b_340b36fec9e8d11359e3786e8_9cb3f0e66c" tabindex="-1" value=""></div>
					<button type="submit">Submit</button>
				</form>	
			</div>		
		</footer>
	<?php wp_footer(); ?>
	</body>
</html>
