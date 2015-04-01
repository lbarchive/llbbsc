[[CT](http://code.google.com/p/llbbsc/wiki/CT) | [Installation](http://code.google.com/p/llbbsc/wiki/CTInstall) |
[Upgrading](http://code.google.com/p/llbbsc/wiki/CTUpgrade) |
[Usage](http://code.google.com/p/llbbsc/wiki/CTUsage) | [CSS Customization](http://code.google.com/p/llbbsc/wiki/CTCSSCustomization) | [Testing](http://code.google.com/p/llbbsc/wiki/CTTest) | [Theme Modification](http://code.google.com/p/llbbsc/wiki/CTThemeModification)]

# Introduction #

You can customize `/plugins/CiteThis/CiteThis.css`.

## Manual Method ##
```
<div id="citations-POSTID" class="citations">
<a class="citation-manual-dynamic">Cite this...</a>
<a class="citation-new-window" href="link" target="_blank" rel="nofollow">(new window)</a>
</div>	
```

## Manual Method with Dynamic loading ##
```
<div id="citations-POSTID" class="citations">
<a class="citation-manual">Cite this...</a>
<a class="citation-new-window" href="link" target="_blank" rel="nofollow">(new window)</a>
</div>	
```

## Citations ##
```
<div id="citations-POSTID" class="citations">
<h3 class="citations-title">Citation styles</h3>
<dl class="citations">
<dt class="citation"><a href="link">Citation Sytle Name</a></dt>
<dd class="citation">Citation</dd>
...
<dt class="citation"><a href="link">Citation Sytle Name</a></dt>
<dd class="citation">Citation</dd>
</dl>
</div>
```