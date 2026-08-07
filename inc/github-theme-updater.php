<?php
/**
 * GitHub updater integration for the theme.
 *
 * @package Wedding_Elegant_Wedding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provides WordPress theme update data from GitHub Releases or the main branch.
 */
final class WEW_GitHub_Theme_Updater {
	/**
	 * Theme slug.
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * GitHub repository in owner/name format.
	 *
	 * @var string
	 */
	private $repo;

	/**
	 * GitHub branch fallback.
	 *
	 * @var string
	 */
	private $branch;

	/**
	 * Theme object.
	 *
	 * @var WP_Theme
	 */
	private $theme;

	/**
	 * Constructor.
	 *
	 * @param string $slug   Theme slug.
	 * @param string $repo   GitHub repo owner/name.
	 * @param string $branch Branch fallback.
	 */
	public function __construct( $slug, $repo, $branch = 'main' ) {
		$this->slug   = $slug;
		$this->repo   = $repo;
		$this->branch = $branch;
		$this->theme  = wp_get_theme( $slug );

		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_update' ) );
		add_filter( 'themes_api', array( $this, 'theme_info' ), 20, 3 );
		add_filter( 'upgrader_source_selection', array( $this, 'fix_source_directory' ), 10, 4 );
		add_filter( 'http_request_args', array( $this, 'authorize_github_downloads' ), 10, 2 );
		add_action( 'upgrader_process_complete', array( $this, 'clear_cache_after_update' ), 10, 2 );
	}

	/**
	 * Inject the theme update payload into the WordPress update transient.
	 *
	 * @param object $transient Update transient.
	 * @return object
	 */
	public function check_for_update( $transient ) {
		if ( ! is_object( $transient ) || empty( $transient->checked[ $this->slug ] ) ) {
			return $transient;
		}

		$remote = $this->get_remote_theme();

		if ( empty( $remote['version'] ) || empty( $remote['package'] ) ) {
			return $transient;
		}

		$current_version = $transient->checked[ $this->slug ];
		$payload         = array(
			'theme'        => $this->slug,
			'new_version'  => $remote['version'],
			'url'          => $remote['url'],
			'package'      => $remote['package'],
			'requires'     => $remote['requires'],
			'requires_php' => $remote['requires_php'],
		);

		if ( version_compare( $remote['version'], $current_version, '>' ) ) {
			$transient->response[ $this->slug ] = $payload;
		} else {
			$transient->no_update[ $this->slug ] = $payload;
		}

		return $transient;
	}

	/**
	 * Provide theme details in the update modal.
	 *
	 * @param false|object|array $result Current result.
	 * @param string             $action API action.
	 * @param object             $args   API args.
	 * @return false|object|array
	 */
	public function theme_info( $result, $action, $args ) {
		if ( 'theme_information' !== $action || empty( $args->slug ) || $this->slug !== $args->slug ) {
			return $result;
		}

		$remote    = $this->get_remote_theme();
		$changelog = ! empty( $remote['changelog'] ) ? $remote['changelog'] : __( 'Lihat repository GitHub untuk catatan perubahan.', 'wedding-elegant-wedding' );

		return (object) array(
			'name'          => $this->theme->get( 'Name' ),
			'slug'          => $this->slug,
			'version'       => ! empty( $remote['version'] ) ? $remote['version'] : $this->theme->get( 'Version' ),
			'author'        => $this->theme->get( 'Author' ),
			'homepage'      => 'https://github.com/' . $this->repo,
			'preview_url'   => home_url( '/' ),
			'requires'      => ! empty( $remote['requires'] ) ? $remote['requires'] : '6.0',
			'requires_php'  => ! empty( $remote['requires_php'] ) ? $remote['requires_php'] : '7.4',
			'download_link' => ! empty( $remote['package'] ) ? $remote['package'] : '',
			'sections'      => array(
				'description' => esc_html( $this->theme->get( 'Description' ) ),
				'changelog'   => wp_kses_post( wpautop( $changelog ) ),
			),
		);
	}

	/**
	 * Keep the installed folder name stable when GitHub archives extract to repo-tag folders.
	 *
	 * @param string      $source        Extracted source path.
	 * @param string      $remote_source Remote source path.
	 * @param WP_Upgrader $upgrader      Upgrader instance.
	 * @param array       $hook_extra    Upgrader context.
	 * @return string
	 */
	public function fix_source_directory( $source, $remote_source, $upgrader, $hook_extra = array() ) {
		if ( empty( $hook_extra['theme'] ) || $this->slug !== $hook_extra['theme'] ) {
			return $source;
		}

		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			return $source;
		}

		$source = trailingslashit( $source );

		if ( $wp_filesystem->exists( $source . $this->slug . '/style.css' ) ) {
			return trailingslashit( $source . $this->slug );
		}

		if ( basename( untrailingslashit( $source ) ) === $this->slug && $wp_filesystem->exists( $source . 'style.css' ) ) {
			return $source;
		}

		if ( ! $wp_filesystem->exists( $source . 'style.css' ) ) {
			return $source;
		}

		$desired = trailingslashit( $remote_source ) . $this->slug;

		if ( $wp_filesystem->exists( $desired ) ) {
			$wp_filesystem->delete( $desired, true );
		}

		if ( $wp_filesystem->move( $source, $desired, true ) ) {
			return trailingslashit( $desired );
		}

		return $source;
	}

	/**
	 * Add GitHub authorization headers when a token is configured.
	 *
	 * @param array  $args HTTP args.
	 * @param string $url  Request URL.
	 * @return array
	 */
	public function authorize_github_downloads( $args, $url ) {
		$token = $this->get_token();

		if ( '' === $token ) {
			return $args;
		}

		$repo_url = 'https://github.com/' . $this->repo;
		$api_url  = 'https://api.github.com/repos/' . $this->repo;

		if ( 0 !== strpos( $url, $repo_url ) && 0 !== strpos( $url, $api_url ) ) {
			return $args;
		}

		if ( empty( $args['headers'] ) || ! is_array( $args['headers'] ) ) {
			$args['headers'] = array();
		}

		$args['headers']['Authorization'] = 'Bearer ' . $token;
		$args['headers']['Accept']        = 'application/vnd.github+json';
		$args['headers']['User-Agent']    = $this->user_agent();

		return $args;
	}

	/**
	 * Clear cached remote data after a theme update.
	 *
	 * @param WP_Upgrader $upgrader   Upgrader instance.
	 * @param array       $hook_extra Upgrader context.
	 */
	public function clear_cache_after_update( $upgrader, $hook_extra ) {
		if ( ! empty( $hook_extra['theme'] ) && $this->slug === $hook_extra['theme'] ) {
			delete_site_transient( $this->cache_key() );
		}
	}

	/**
	 * Get remote theme metadata with a short cache.
	 *
	 * @return array<string,string>
	 */
	private function get_remote_theme() {
		$cached = get_site_transient( $this->cache_key() );

		if ( is_array( $cached ) ) {
			return $cached;
		}

		$remote = $this->get_latest_release();

		if ( empty( $remote ) ) {
			$remote = $this->get_branch_theme();
		}

		if ( ! empty( $remote ) ) {
			set_site_transient( $this->cache_key(), $remote, HOUR_IN_SECONDS );
		}

		return is_array( $remote ) ? $remote : array();
	}

	/**
	 * Read the latest GitHub release.
	 *
	 * @return array<string,string>
	 */
	private function get_latest_release() {
		$body = $this->request( 'https://api.github.com/repos/' . $this->repo . '/releases/latest' );

		if ( '' === $body ) {
			return array();
		}

		$data = json_decode( $body, true );

		if ( empty( $data['tag_name'] ) || ! is_array( $data ) ) {
			return array();
		}

		$version = $this->extract_version( $data['tag_name'] );

		if ( '' === $version ) {
			return array();
		}

		$package = $this->release_asset_url( $data );

		if ( '' === $package ) {
			$package = 'https://github.com/' . $this->repo . '/archive/refs/tags/' . rawurlencode( $data['tag_name'] ) . '.zip';
		}

		return array(
			'version'      => $version,
			'url'          => ! empty( $data['html_url'] ) ? esc_url_raw( $data['html_url'] ) : 'https://github.com/' . $this->repo,
			'package'      => esc_url_raw( $package ),
			'requires'     => '6.0',
			'requires_php' => '7.4',
			'changelog'    => ! empty( $data['body'] ) ? wp_kses_post( $data['body'] ) : '',
		);
	}

	/**
	 * Fallback to the branch style.css version.
	 *
	 * @return array<string,string>
	 */
	private function get_branch_theme() {
		$style_url = 'https://raw.githubusercontent.com/' . $this->repo . '/' . rawurlencode( $this->branch ) . '/style.css';
		$body      = $this->request( $style_url );

		if ( '' === $body ) {
			return array();
		}

		$version = $this->extract_header( $body, 'Version' );

		if ( '' === $version ) {
			return array();
		}

		return array(
			'version'      => $version,
			'url'          => 'https://github.com/' . $this->repo,
			'package'      => 'https://github.com/' . $this->repo . '/archive/refs/heads/' . rawurlencode( $this->branch ) . '.zip',
			'requires'     => $this->extract_header( $body, 'Requires at least', '6.0' ),
			'requires_php' => $this->extract_header( $body, 'Requires PHP', '7.4' ),
			'changelog'    => __( 'Update diambil dari branch GitHub.', 'wedding-elegant-wedding' ),
		);
	}

	/**
	 * Request a GitHub URL.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	private function request( $url ) {
		$args = array(
			'timeout' => 12,
			'headers' => array(
				'Accept'     => 'application/vnd.github+json',
				'User-Agent' => $this->user_agent(),
			),
		);

		$token = $this->get_token();

		if ( '' !== $token ) {
			$args['headers']['Authorization'] = 'Bearer ' . $token;
		}

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$code = (int) wp_remote_retrieve_response_code( $response );

		if ( $code < 200 || $code >= 300 ) {
			return '';
		}

		return (string) wp_remote_retrieve_body( $response );
	}

	/**
	 * Find a release asset ZIP.
	 *
	 * @param array<string,mixed> $release Release data.
	 * @return string
	 */
	private function release_asset_url( $release ) {
		if ( empty( $release['assets'] ) || ! is_array( $release['assets'] ) ) {
			return '';
		}

		$preferred = array(
			'theme-elegant-wedding.zip',
			'wedding-elegant-wedding.zip',
		);

		foreach ( $preferred as $filename ) {
			foreach ( $release['assets'] as $asset ) {
				if ( ! empty( $asset['name'] ) && $filename === $asset['name'] && ! empty( $asset['browser_download_url'] ) ) {
					return (string) $asset['browser_download_url'];
				}
			}
		}

		return '';
	}

	/**
	 * Extract a semver-like version from a tag.
	 *
	 * @param string $value Tag value.
	 * @return string
	 */
	private function extract_version( $value ) {
		if ( preg_match( '/\d+(?:\.\d+){1,3}(?:[-+][0-9A-Za-z.-]+)?/', $value, $matches ) ) {
			return $matches[0];
		}

		return '';
	}

	/**
	 * Extract a header from style.css text.
	 *
	 * @param string $body    CSS body.
	 * @param string $header  Header name.
	 * @param string $default Default value.
	 * @return string
	 */
	private function extract_header( $body, $header, $default = '' ) {
		$pattern = '/^[ \t\/*#@]*' . preg_quote( $header, '/' ) . ':\s*(.+)$/mi';

		if ( preg_match( $pattern, $body, $matches ) ) {
			return trim( $matches[1] );
		}

		return $default;
	}

	/**
	 * GitHub token for private repository support.
	 *
	 * @return string
	 */
	private function get_token() {
		$token = '';

		if ( defined( 'WEW_GITHUB_TOKEN' ) && WEW_GITHUB_TOKEN ) {
			$token = (string) WEW_GITHUB_TOKEN;
		}

		/**
		 * Filters the GitHub token used by the theme updater.
		 *
		 * @param string $token Token value.
		 */
		$token = apply_filters( 'wew_github_token', $token );

		return is_string( $token ) ? trim( $token ) : '';
	}

	/**
	 * Cache key.
	 *
	 * @return string
	 */
	private function cache_key() {
		return 'wew_github_update_' . md5( $this->repo . '|' . $this->branch );
	}

	/**
	 * GitHub user agent.
	 *
	 * @return string
	 */
	private function user_agent() {
		return 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url( '/' ) . '; ' . $this->slug;
	}
}

