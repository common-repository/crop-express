<details open>
	<summary><h3>Presets</h3></summary>
	<div>
		<form action="" method="post" id="presets-form">
			<fieldset>
				<?php foreach ( $default_presets as $i => $preset ) : ?>
					<div class="preset">
						<div class="preset-preview"
						     style="
								     border-radius: <?php echo 'default-circle' === $preset['id'] ? 50 : 0 ?>%;
								     width: <?php echo $preset['aspect_ratio'] > 1 ? $preset['aspect_ratio'] * 5 : 5 ?>rem;
								     height: <?php echo $preset['aspect_ratio'] < 1 ? 5 / ( $preset['aspect_ratio'] * 5 ) * 5 : 5 ?>rem;
								     "
						></div>
						<h4><?php echo $preset['label'] ?></h4>
						<label class="switch">
							<input type="checkbox"
							       name="default_presets[]"
							       value="<?php echo esc_attr( $preset['id'] ) ?>"
								<?php checked( isset( $presets[ $preset['id'] ] ) ) ?>
							>
							<span class="slider"></span>
						</label>
					</div>
				<?php endforeach; ?>
				<?php foreach ( $presets as $i => $preset ) :
					if ( isset( $default_presets[ $preset['id'] ] ) ) {
						continue;
					}
					?>
					<div class="preset preset-custom">
						<input type="hidden" name="custom_presets[<?php echo $preset['id'] ?>][id]"
						       value="<?php echo esc_attr( $preset['id'] ) ?>"/>
						<div class="preset-preview"
						     style="
								     border-radius: <?php echo 'default-circle' === $preset['id'] ? 50 : 0 ?>%;
								     width: <?php echo $preset['aspect_ratio'] > 1 ? $preset['aspect_ratio'] * 5 : 5 ?>rem;
								     height: <?php echo $preset['aspect_ratio'] < 1 ? 5 / ( $preset['aspect_ratio'] * 5 ) * 5 : 5 ?>rem;
								     "
						></div>
						<h4><input type="text" name="custom_presets[<?php echo $preset['id'] ?>][label]"
						           value="<?php echo esc_attr( $preset['label'] ) ?>"/></h4>
						<p>
							<label style="text-align: center" for="numerator">Aspect Ratio:</label>
							<span class="aspect-ratio">
								<input type="number" name="custom_presets[<?php echo $preset['id'] ?>][numerator]"
								       id="numerator" min=".001" step="0.001"
								       value="<?php echo esc_attr( $preset['numerator'] ) ?>"
								/>
								/<input type="number" name="custom_presets[<?php echo $preset['id'] ?>][denominator]"
								        id="denominator" min=".001"
								        step="0.001" value="<?php echo esc_attr( $preset['denominator'] ) ?>"/>
							</span>
						</p>
						<p>
							<button type="button"
							        class="button button-secondary preset-remove-button"
							        style="width: auto; margin: 0 auto;">
								Remove
							</button>
						</p>
					</div>
				<?php endforeach; ?>
				<div class="preset preset-add-new-form">
					<h3>Add your own preset</h3>
					<p>
						<label for="label">Name:</label>
						<input type="text" name="preset[label]" id="label"/>
					</p>
					<p>
						<label for="numerator">Aspect Ratio:</label>
						<span class="aspect-ratio">
								<input type="number" name="preset[numerator]" id="numerator" min=".001" step="0.001"
								/>
								/<input type="number" name="preset[denominator]" id="denominator" min=".001"
								        step="0.001"/>
							</span>
					</p>
					<p>
						<button
								type="submit"
								value="preset-add"
								name="action"
								class="button button-primary"
								style="background: #888; border-color: #888;">
							Add
						</button>
					</p>

				</div>
			</fieldset>
			<button type="submit" class="button button-primary">
				Update
			</button>
			<?php wp_nonce_field( Cropexpress()->plugin_data()['prefix'] . '-presets-update' ) ?>
		</form>
	</div>
</details>
<script>
	jQuery(function ($) {
		$('body').on(
			'click',
			'.preset-remove-button',
			function () {
				$(this).closest('.preset').remove();
			}
		);
	});
</script>