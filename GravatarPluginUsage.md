[[Gravatar](http://code.google.com/p/llbbsc/wiki/GravatarPlugin) |
[Screenshots](http://code.google.com/p/llbbsc/wiki/GravatarPluginScreenshots) |
[Install, Upgrade or Uninstall](http://code.google.com/p/llbbsc/wiki/GravatarPluginIUU) |
[Customization](http://code.google.com/p/llbbsc/wiki/GravatarPluginCustomization) |
[Usage](http://code.google.com/p/llbbsc/wiki/GravatarPluginUsage)]

# Setting your own Gravatar Email #

## Registered Email method ##

The first time users should receive their password notification mail as well as another Gravatar usage verification notification email. Just copy the code and paste it to `Gravatar Verification Code` field in profile editing page after first time login.

If the user changes their registered email, plugin will automatically send new verification email to the new registered email. Meanwhile, the users' Gravatar are disabled.

## Additional Gravatar Email field ##

**Entering in registration**

The first time users can enter their Gravatar email in registration as well. They should receive their password notification mail as well as another Gravatar usage verification notification email. Just copy the code and paste it to `Gravatar Verification Code` field in profile editing page after first time login.

**Activating after registration or Modifying Gravatar Email**

Go checking your profile page and edit it. There should be an additional field called **Gravatar Email**. **Gravatar Email** will only be shown to whom has privilege to edit the user's profile. That means only Key Master, Administrator can see others' Gravatar Email.

Once you save your new **Gravatar Email**, there should be a new **Verification Code** notification mail in your mailbox (registered mail). Input the 8-letter code into your profile editing page. After you save your profile with the **Verification Code**, you should see your Gravatar avatar in you profile page immediately.

# Functions #

## `GAGetImageURI` ##

Returns `src` for `<img>` tag.

  * `$id=0` - User ID
  * `$size=0` - The size (px) of avatar. Currently, it is up to 80px.

Sample Result:

```
http://www.gravatar.com/avatar.php?gravatar_id=9c8425c85f81798109d48e30fb1d28e4&amp;default=http%3A%2F%2Fwww.livibetter.com%2Fit%2Fmy-plugins%2Fgravatar-default.png&amp;size=64
```

## `GAImageURI` ##

The `echo` version of `GAGetImageURI`

Sample Result:

```
http://www.gravatar.com/avatar.php?gravatar_id=9c8425c85f81798109d48e30fb1d28e4&amp;default=http%3A%2F%2Fwww.livibetter.com%2Fit%2Fmy-plugins%2Fgravatar-default.png&amp;size=64
```

## `GAGetImage` ##

Returns complete `<img>` tag.

  * `$id=0` - User ID
  * `$size=0` - The size (px) of avatar. Currently, it is up to 80px.
  * `$style='border: 1px solid black'` - style attribute of `img`
  * `$class=''` - class attribute of `img`
  * `$link=true` - `img` wrapped within `a`.

Sample Result:

```
<a href="http://www.livibetter.com/it/profile/livibetter"><img style="border: 1px solid black;" src="http://www.gravatar.com/avatar.php?gravatar_id=9c8425c85f81798109d48e30fb1d28e4&amp;default=http%3A%2F%2Fwww.livibetter.com%2Fit%2Fmy-plugins%2Fgravatar-default.png&amp;size=64" alt="livibetter" height="64" width="64"></a>
```

## `GAImage` ##

The `echo` version of `GAGetImage` without a link.

Sample Result:

```
<img style="border: 1px solid black;" src="http://www.gravatar.com/avatar.php?gravatar_id=9c8425c85f81798109d48e30fb1d28e4&amp;default=http%3A%2F%2Fwww.livibetter.com%2Fit%2Fmy-plugins%2Fgravatar-default.png&amp;size=64" alt="livibetter" height="64" width="64">
```

## `GAImageLink` ##

The `echo` version of `GAGetImage` with a link.

Sample Result:

```
<a href="http://www.livibetter.com/it/profile/livibetter"><img style="border: 1px solid black;" src="http://www.gravatar.com/avatar.php?gravatar_id=9c8425c85f81798109d48e30fb1d28e4&amp;default=http%3A%2F%2Fwww.livibetter.com%2Fit%2Fmy-plugins%2Fgravatar-default.png&amp;size=64" alt="livibetter" height="64" width="64"></a>
```