## GitHub Link

Displays GitHub link on the **Plugins** page given there is a `GitHub Plugin URI`
[plugin header](github-link.php#L11).

When your plugin is on WordPress.org also and there is no `GitHub Branch` header (or its value is "master")
the GitHub icon is displayed **after** other plugin actions (links), otherwise it is the **first** action.

### GitHub headers

- `GitHub Plugin URI` shown as a normal GitHub icon ![GitHub icon](icon/GitHub-Mark-32px.png)
- `GitHub Branch` shown as text after the GitHub icon
- `GitHub Access Token` (aka. private repo) shown as GitHub icon with a lock ![GitHub Private icon](icon/GitHub-Mark-Private-32px.png)

### GitLab headers

- `GitLab Plugin URI` shown as a normal GitLab icon ![GitLab icon](icon/GitLab-Mark-32px.png)
- `GitLab Branch` shown as text after the GitLab icon

### Bitbucket headers

- `Bitbucket Plugin URI` shown as a Bitbucket logo ![Bitbucket logo](icon/bitbucket_32_darkblue_atlassian.png)
- `Bitbucket Branch` shown as text after the Bitbucket icon

### WordPress.org headers

- `Plugin URI` shown as a WordPress logo when the plugin is on WordPress.org ![https://s.w.org/about/images/logos/wordpress-logo-32-blue.png](icon/wordpress-logo-32.png)

### Related Information

These plugin headers enable automatic updates to your GitHub or Bitbucket hosted WordPress
plugins and themes using the [GitHub Updater plugin](https://github.com/afragen/github-updater).
GitHub Updater is not found on WordPress.org.
