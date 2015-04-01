[[Gravatar](http://code.google.com/p/llbbsc/wiki/GravatarPlugin) |
[Screenshots](http://code.google.com/p/llbbsc/wiki/GravatarPluginScreenshots) |
[Install, Upgrade or Uninstall](http://code.google.com/p/llbbsc/wiki/GravatarPluginIUU) |
[Customization](http://code.google.com/p/llbbsc/wiki/GravatarPluginCustomization) |
[Usage](http://code.google.com/p/llbbsc/wiki/GravatarPluginUsage)]

# Gravatar Email Source #

If you switch to the other source, users would need to reactivate.

# Default Image #

First install or reset **Default Image Options` make plugin to search and use a image in the following order:**

  1. my-plugins/gravatar-default.jpg
  1. my-plugins/gravatar-default.gif
  1. my-plugins/gravatar-default.png
  1. bb-template/current\_template/gravatar-default.jpg
  1. bb-template/current\_template/gravatar-default.gif
  1. bb-template/current\_template/gravatar-default.png

If not available, it will leave it blank, and this makes Gravatar shows its icon if users don't have avatars at Gravatar.com.

# Default Role Images #

In plugin's Options page, it will list all the current roles. You can assign an image URI to anyone of those roles. If a user has no avatar setting, then this plugin will use role's default image or the **Default Image** setting above.