[[OTD](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPlugin) | [Screenshots](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginScreenshots) | [Installation and Upgrading](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginInstallationAndUpgrading) | [Usage](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginUsage) | [Customization](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginCustomization) | [Search Form](http://code.google.com/p/llbbsc/wiki/OnThisDayWPPluginSearchForm)]

# OTD List Titles #

You can use `On %date:jS of F%...` instead of `On this day...`. You will see something like `28th of October...`.

You should not use heading tags(h1, h2,...) embracing title of widget.

# DIV Block #

If you need OTD list within a div block, you can use `<div class="onthisday"><h3>On this day...</h3>` as title and `[snip] %search% </div>` as block.

# Calling OTDList() #

You can call `OTDList()` to list.

```
function OTDList($targetPost=null){
```

# Exclude current year's posts #

Check **`Exclude current year's post`** in General Options. This option also applies on pages if **`Include pages`** checked.

# Applying Style #

## Search Form ##
This form looks like
```
<form class="dateSearchForm" id="dateSearchForm-<?php echo rand(); ?>">
    <div class="dateSearchForm">
        <select class="dateSearchMonth" name="dateSearchMonth">
            <option></option>
            <option></option>
            <option></option>
        </select>
        <select class="dateSearchDay" name="dateSearchDay">
            <option></option>
            <option></option>
            <option></option>
        </select>
        <select class="dateSearchYear" name="dateSearchYear">
            <option></option>
            <option></option>
            <option></option>
        </select>
        <input class="dateSearchButton" type="button" value="&raquo;" onclick="searchDate(this.form)"/>
    </div>
</form>
```
You can define those styles into your stylesheet.