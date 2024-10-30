<details>
	<summary><h3>Sizes</h3></summary>
	<div>
		<form action="" method="post" id="sizes-form">
			<fieldset>
				<h4>Minimums</h4>
				<div flex gap>
					<p>
						<label for="min_width">Min width:</label>
						<input type="number" name="min_width" id="min_width" min="100"
						       max="5000"
						       value="<?php echo esc_attr( $sizes['min_width'] ) ?>"
						       placeholder="0"/>
					</p>
					<p>
						<label for="min_height">Min height:</label>
						<input type="number" name="min_height" id="min_height" min="100"
						       max="5000"
						       value="<?php echo esc_attr( $sizes['min_height'] ) ?>"
						       placeholder="0"/>
					</p>
				</div>
				<h4>Maximums</h4>
				<div flex gap>
					<p>
						<label for="max_width">Max width:</label>
						<input type="number" name="max_width" id="max_width" min="100"
						       max="5000"
						       value="<?php echo esc_attr( $sizes['max_width'] ) ?>"
						       placeholder="5000"/>
					</p>
					<p>
						<label for="max_height">Max height:</label>
						<input type="number" name="max_height" id="max_height" min="100"
						       max="5000"
						       value="<?php echo esc_attr( $sizes['max_height'] ) ?>"
						       placeholder="5000"/>
					</p>
				</div>
				<h4>Defaults</h4>
				<div flex gap>
					<p>
						<label for="width">Width:</label>
						<input type="number" name="width" id="width" min="100" max="5000"
						       value="<?php echo esc_attr( $sizes['width'] ) ?>"
						       placeholder="5000"/>
					</p>
					<p>
						<label for="height">Height:</label>
						<input type="number" name="height" id="height" min="100" max="5000"
						       value="<?php echo esc_attr( $sizes['height'] ) ?>"
						       placeholder="5000"/>
					</p>
				</div>
			</fieldset>
			<p>
				<button type="submit" class="button button-primary">
					Update
				</button>
			</p>
			<?php wp_nonce_field( Cropexpress()->plugin_data()['prefix'] . '-sizes-update' ) ?>
		</form>
	</div>
</details>
