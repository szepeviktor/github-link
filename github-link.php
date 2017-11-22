<?php
/*
Plugin Name:       GitHub Link
Version:           0.4.5
Plugin URI:        https://github.com/szepeviktor/github-link
Description:       Displays GitHub link on the Plugins page given there is a <code>GitHub Plugin URI</code> plugin header.
License:           The MIT License (MIT)
Author:            Viktor Szépe
Domain Path:       /languages
Text Domain:       github-link
GitHub Plugin URI: https://github.com/szepeviktor/github-link
*/

if ( ! function_exists( 'add_filter' ) ) {
    error_log( 'Malicious traffic detected: github_link_direct_access '
        . addslashes( $_SERVER['REQUEST_URI'] )
    );
    ob_get_level() && ob_end_clean();
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.0 403 Forbidden' );
    exit();
}

load_plugin_textdomain( 'github-link', false, dirname( __FILE__ ) . '/languages' );

add_filter( "extra_plugin_headers", "GHL_extra_headers" );
add_filter( "plugin_action_links", "GHL_plugin_link", 10, 4 );
add_filter( "network_admin_plugin_action_links", "GHL_plugin_link", 10, 4 );

function GHL_extra_headers( $extra_headers ) {

    // Keys will get lost.
    return array_merge( $extra_headers, array(
        "GitHubURI" => "GitHub Plugin URI",
        "GitHubBranch" => "GitHub Branch",
        "GitHubToken" => "GitHub Access Token",
        "GitLabURI" => "GitLab Plugin URI",
        "GitLabBranch" => "GitLab Branch",
        "BitbucketURI" => "Bitbucket Plugin URI",
        "BitbucketBranch" => "Bitbucket Branch"
    ) );
}

function GHL_plugin_link( $actions, $plugin_file, $plugin_data, $context ) {

    // No GitHub data during search installed plugins.
    if ( 'search' === $context )
        return $actions;

    $link_template = '<a href="%s" title="%s" target="_blank"><img src="%s" style="width: 16px; height: 16px; vertical-align:-3px;" height="16" width="16" alt="%s" />%s</a>';
    $wp_link_template = '<a href="%s" title="%s" target="_blank"><span style="width: 16px; height: 16px; color:#2880A8; font-size:16px; vertical-align:-3px;" class="dashicons dashicons-wordpress"></span></a>';

    $on_wporg = false;
    _maybe_update_plugins();
    $plugin_state = get_site_transient( 'update_plugins' );
    if ( isset( $plugin_state->response[ $plugin_file ] )
        || isset( $plugin_state->no_update[ $plugin_file ] )
    ) {
        $on_wporg = true;
    }

    if ( ! empty( $plugin_data["GitHub Plugin URI"] ) ) {
        $icon = "icon/GitHub-Mark-32px.png";
        $branch = '';

        if ( ! empty( $plugin_data["GitHub Access Token"] ) )
            $icon = 'icon/GitHub-Mark-Private-32px.png"';
        if ( ! empty( $plugin_data["GitHub Branch"] ) )
            $branch = '/' . $plugin_data["GitHub Branch"];

        $new_action = array ( 'github' => sprintf(
            $link_template,
            $plugin_data["GitHub Plugin URI"],
            __( "Visit GitHub repository" , "github-link" ),
            plugins_url( $icon, __FILE__ ),
            "GitHub",
            $branch
        ) );
        // If on WP.org + master -> put the icon after other actions.
        if ( $on_wporg && ( empty( $branch ) || '/master' === $branch ) ) {
            $actions = array_merge( $actions, $new_action );
        } else {
            $actions = array_merge( $new_action, $actions );
        }
    }

    if ( ! empty( $plugin_data["GitLab Plugin URI"] ) ) {
        $icon = "icon/GitLab-Mark-32px.png";
        $branch = '';

        if ( ! empty( $plugin_data["GitLab Branch"] ) )
            $branch = '/' . $plugin_data["GitLab Branch"];

        $new_action = array ( 'gitlab' => sprintf(
            $link_template,
            $plugin_data["GitLab Plugin URI"],
            __( "Visit GitLab repository" , "github-link" ),
            plugins_url( $icon, __FILE__ ),
            "GitLab",
            $branch
        ) );
        // If on WP.org + master -> put the icon after other actions.
        if ( $on_wporg && ( empty( $branch ) || '/master' === $branch ) ) {
            $actions = array_merge( $actions, $new_action );
        } else {
            $actions = array_merge( $new_action, $actions );
        }
    }

    if ( ! empty( $plugin_data["Bitbucket Plugin URI"] ) ) {
        $icon = "icon/bitbucket_32_darkblue_atlassian.png";
        $branch = '';

        if ( ! empty( $plugin_data["Bitbucket Branch"] ) )
            $branch = '/' . $plugin_data["Bitbucket Branch"];

        $new_action = array( 'bitbucket' => sprintf(
            $link_template,
            $plugin_data["Bitbucket Plugin URI"],
            __( "Visit Bitbucket repository" , "github-link" ),
            plugins_url( $icon, __FILE__ ),
            "Bitbucket",
            $branch
        ) );
        // If on WP.org + master -> put the icon after other actions.
        if ( $on_wporg && ( empty( $branch ) || '/master' === $branch ) ) {
            $actions = array_merge( $actions, $new_action );
        } else {
            $actions = array_merge( $new_action, $actions );
        }
    }

    if ( $on_wporg ) {
        $plugin_page = '';
        if ( isset( $plugin_state->response[ $plugin_file ] ) ) {
            if ( property_exists( $plugin_state->response[ $plugin_file ], 'url' ) ) {
                $plugin_page = $plugin_state->response[ $plugin_file ]->url;
            }
        } elseif ( isset( $plugin_state->no_update[ $plugin_file ] ) ) {
            if ( property_exists( $plugin_state->no_update[ $plugin_file ], 'url' ) ) {
                $plugin_page = $plugin_state->no_update[ $plugin_file ]->url;
            }
        }

        // GHU also sets plugin->url.
        if ( false !== strstr( $plugin_page, '//wordpress.org/plugins/' ) ) {
            $new_action = array( 'wordpress_org' => sprintf(
                $wp_link_template,
                $plugin_page,
                __( "Visit WordPress.org Plugin Page" , "github-link" )
            ) );
            $actions = array_merge( $new_action, $actions );
        }
    }

    return $actions;
}
