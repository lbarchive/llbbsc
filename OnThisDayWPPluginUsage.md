[[OTD](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPlugin) | [Screenshots](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginScreenshots) | [Installation and Upgrading](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginInstallationAndUpgrading) | [Usage](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginUsage) | [Customization](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginCustomization) | [Search Form](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginSearchForm)]

There are three ways to use this plugin.

# Widget #

Go to **Presentation**/**Widgets**, drag widget "On this day" from **Available Widgets**, then drop it onto **Sidebar**.

It results like (in default theme)

![http://groups.google.com/group/llbbsc/web/OTD-Widget.png](http://groups.google.com/group/llbbsc/web/OTD-Widget.png)

**Note**:
  * You can check **`Lists same calendar date posts to the post in single post mode`** on in Widget Options in OTD Options page, then the OTD widget will list same calendar date posts when visitor reads a single post.
  * If **`Include pages`** in General Options isn't checked, then widget will not show on pages.

# Automatically appends #

You can check these two options under General Options in On this day Options page:
  * **`Show OTD list after single post automatically`**
  * **`Show OTD list after every post automatically`**

If you enable them, then the "On this day..." list will be appended right after post content.

# Manually calls OTDList() #

You can call `OTDList()` to get the list where you want to show. If you call this function within posts loop, then it will return posts which have same calendar date as current post.

For example, you can have
```
<?php while (have_posts()) : the_post(); ?>
[...]
	<div class="post-onthisday">
		<h2>On this day...</h2>
			<?php OTDList(); ?>
	</div>
[...]
<?php endwhile;?>
```
in `index.php` of your template. You only need to take care the title of this list, then call `OTDList()`. In this mode, it uses templates in `Single post mode Options` or `Multi-posts mode Options`

In `sidebar.php`,
```
<div class="sidebar-item-content">
	<?php OTDList(); ?>
</div>
```
This use template of Widget Options. This results posts which have same calendar date as today. If visitor is reading a single post or page and `Lists same calendar date posts to the post in single post mode` of Widget Options is checked, then OTD plugin will list same calendar date posts as current post or page.