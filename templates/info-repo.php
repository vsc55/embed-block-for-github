<div class="ebg-br-wrapper">
	<div class="%%_CFG_DARK_THEME_%% ebg-br-template-repo">
		<div class="ebg-br-body ebg-br-border ebg-br-background-color">
			<i class="%%_CFG_ICON_TYPE_SOURCE_-_FONT_AWESOME_%% ebg-br-logo fab fa-github"></i>
			<i class="%%_CFG_ICON_TYPE_SOURCE_-_FILE_SVG_%% ebg-br-logo ebg-br-icon-github ebg-br-img-color-auto">&nbsp;</i>
			<div class="ebg-br-col ebg-br-col-sidebar">
				<img class="ebg-br-header-avatar" src="%%_DATA_REPO_OWNER_AVATAR_URL_%%" alt="Avatar" width="150" height="150" />
			</div>
			<div class="ebg-br-col ebg-br-col-main">
				<p class="ebg-br-title">
					<strong>
						<a target="_blank" rel="noopener noreferrer" href="%%_DATA_REPO_HTML_URL_%%">
							%%_DATA_REPO_NAME_%% <i class="%%_CFG_ICON_TYPE_SOURCE_-_FONT_AWESOME_%% fas fa-link"></i><img class="%%_CFG_ICON_TYPE_SOURCE_-_FILE_SVG_%% ebg-br-img-color-auto" src="%%_URL_ICO_LINK_%%" alt="" height="22" width="22">
							<span class="screen-reader-text">(<?php echo esc_html__( 'this link opens in a new window', 'embed-block-for-github' ); ?>)</span>
						</a>
					</strong>
					<em>
						<?php echo esc_html__( 'by', 'embed-block-for-github' ); ?><a target="_blank" rel="noopener noreferrer" href="%%_DATA_REPO_OWNER_HTML_URL_%%">
							%%_DATA_REPO_OWNER_LOGIN_%% <i class="%%_CFG_ICON_TYPE_SOURCE_-_FONT_AWESOME_%% fas fa-link"></i><img class="%%_CFG_ICON_TYPE_SOURCE_-_FILE_SVG_%% ebg-br-img-color-auto" src="%%_URL_ICO_LINK_%%" alt="" height="9" width="9">
							<span class="screen-reader-text">(<?php echo esc_html__( 'this link opens in a new window', 'embed-block-for-github' ); ?>)</span>
						</a>
					</em>
				</p>
				<p class="ebg-br-description %%_DATA_REPO_DESCRIPTION_%_CLASS_HIDE_IS_NULL_%%">
					%%_DATA_REPO_DESCRIPTION_%%
				</p>
				<p class="ebg-br-footer">
					<span class="ebg-br-subscribers">
						<i class="%%_CFG_ICON_TYPE_SOURCE_-_FONT_AWESOME_%% ebg-br-icon fas fa-heart">&nbsp;</i>
						<i class="%%_CFG_ICON_TYPE_SOURCE_-_FILE_SVG_%% ebg-br-icon ebg-br-icon-subscribers ebg-br-img-color-auto">&nbsp;</i>
						<?php echo esc_html( sprintf( _n( '%s Subscriber', '%s Subscribers', $data->subscribers_count, 'embed-block-for-github' ), $data->subscribers_count ) ); ?>
					</span>
					<span class="ebg-br-watchers">
						<i class="%%_CFG_ICON_TYPE_SOURCE_-_FONT_AWESOME_%% ebg-br-icon fas fa-eye">&nbsp;</i>
						<i class="%%_CFG_ICON_TYPE_SOURCE_-_FILE_SVG_%% ebg-br-icon ebg-br-icon-watchers ebg-br-img-color-auto">&nbsp;</i>
						<?php echo esc_html( sprintf( _n( '%s Watcher', '%s Watchers', $data->watchers_count, 'embed-block-for-github' ), $data->watchers_count ) ); ?>
					</span>
					<span class="ebg-br-forks">
						<i class="%%_CFG_ICON_TYPE_SOURCE_-_FONT_AWESOME_%% ebg-br-icon fas fa-code-branch">&nbsp;</i>
						<i class="%%_CFG_ICON_TYPE_SOURCE_-_FILE_SVG_%% ebg-br-icon ebg-br-icon-forks ebg-br-img-color-auto">&nbsp;</i>
						<?php echo esc_html( sprintf( _n( '%s Fork', '%s Forks', $data->forks_count, 'embed-block-for-github' ), $data->forks_count ) ); ?>
					</span>
					<a target="_blank" rel="noopener noreferrer" class="ebg-br-link" href="%%_DATA_REPO_HTML_URL_%%">
						<?php echo esc_html__( 'Check out this repository on GitHub.com', 'embed-block-for-github' ); ?> <i class="%%_CFG_ICON_TYPE_SOURCE_-_FONT_AWESOME_%% fas fa-link"></i><img class="%%_CFG_ICON_TYPE_SOURCE_-_FILE_SVG_%% ebg-br-img-color-auto" src="%%_URL_ICO_LINK_%%" alt="" height="11" width="11">
						<span class="screen-reader-text">(<?php echo esc_html__( 'this link opens in a new window', 'embed-block-for-github' ); ?>)</span>
					</a>
				</p>
			</div>
		</div>
	</div>
	<div class="ebg-br-editmode ebg-br-box-status">
		<div class="egb-br-cache-status">
			<div class="%%_CFG_CACHE_%%">
				<span class="egb-br-status-img-on-off"><?php echo esc_html__( 'Cache', 'embed-block-for-github' ); ?></span>
			</div>
		</div>
		<div class="egb-br-dark_theme-status">
			<div class="%%_CFG_DARK_THEME_%%">
				<span class="egb-br-status-img-on-off"><?php echo esc_html__( 'Dark Theme', 'embed-block-for-github' ); ?></span>
			</div>
		</div>
	</div>
</div>