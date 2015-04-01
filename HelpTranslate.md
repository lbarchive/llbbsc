# Introduction #

If you already have experiences of translating using a given .pot file, please download the latest .pot for translating.

If you don't know how to translate using a .pot and you use Linux, please read [Internationalizing WordPress Plugin](http://www.livibetter.com/blog/2007/11/05/internationalizing-wordpress-plugin/).

# Notes #

  * All communications should use [llbbsc Discussion Group](http://groups.google.com/group/llbbsc).
  * **Contacting before translating**.

# General Tasks for Translators #

  1. Translating
    1. Translating all untranslated messages and updated all fuzzy messages.
    1. Verifying in program, make sure nothing is broken by translation.
  1. Providing you public personal information _(optional)_
    1. Your Name - Real name or nickname.
    1. **Personal** Website - This would be listed in homepage of the program. Note: I may not list your website.
  1. Making a simple introduction page for program _(optional)_
> > If you can make a simple page for the program in your web space, llbbsc's discussion group space or Google Pages, etc. That would be greatly appreciated. You don't need to make new just translated one, e.g. translating http://code.google.com/p/llbbsc/w/edit/OnThisDayWPPlugin for On this day WordPress plugin like [this](http://www.livibetter.com/blog/2007/11/05/%e6%ad%b7%e5%8f%b2%e4%b8%8a%e7%9a%84%e4%bb%8a%e5%a4%a9-wordpress-%e5%a4%96%e6%8e%9b/) in Traditional Chinese. You don't have to do this with PO translating at the same time. Just post a link in group when you done.

# Gravatar bbPress plugin #

## Latest `.pot` and last modified `.po` ##

Download it in [trunk](http://llbbsc.googlecode.com/svn/trunk/bbPress/Gravatar/po/)

## `bmgc.conf` sample ##
```
# General
# ======================================
# Directory stores .pot and .po
PO_DIR=po
# DOMAIN
DOMAIN=Gravatar
# filename of .pot
POT="$DOMAIN.pot"
# Source directory
SOURCE_DIR=Gravatar

# build
# ======================================
# Your program name
PACKAGE="Gravatar"
# Title for message catalog file
TITLE="Message Catalog for $PACKAGE"
# The copyright holder
COPYRIGHT_HOLDER="Yu Jie Lin"
# Email for receiving bug reports
MSGID_BUGS_ADDRESS="lb07@livibetter.com"
# Source filenames. Space separated. e.g. OnThisDay.php OptionsPage.php
SOURCES="Gravatar.php OptionsPage.php"

# generate
# ======================================
# Directory stores .mo
LOCALE_DIR=$SOURCE_DIR/locale
# Translator's name and email: FULL NAME <EMAIL@ADDRESS>
LAST_TRANSLATOR="Yu-Jie Lin <lb07@livibetter.com>"
```

# On this day WordPress plugin #
## Current Status ##
  * bg\_BG - Bulgarian - [94/95]
  * zh\_TW - Traditional Chinese - [95/95]

## Latest `.pot` and last modified `.po` ##

Download it in [trunk](http://svn.wp-plugins.org/on-this-day/trunk/po/)

## `bmgc.conf` sample ##
```
# General
# ======================================
# Directory stores .pot and .po
PO_DIR=po
# DOMAIN
DOMAIN=OnThisDay
# filename of .pot
POT="$DOMAIN.pot"
# Source directory
SOURCE_DIR=OnThisDay

# build
# ======================================
# Your program name
PACKAGE="OnThisDay"
# Title for message catalog file
TITLE="Message Catalog for $PACKAGE"
# The copyright holder
COPYRIGHT_HOLDER="Yu Jie Lin"
# Email for receiving bug reports
MSGID_BUGS_ADDRESS="lb07@livibetter.com"
# Source filenames. Space separated. e.g. OnThisDay.php OptionsPage.php
SOURCES="OnThisDay.php OptionsPage.php"

# generate
# ======================================
# Directory stores .mo
LOCALE_DIR=$SOURCE_DIR/locale
# Translator's name and email: FULL NAME <EMAIL@ADDRESS>
LAST_TRANSLATOR="Yu-Jie Lin <lb07@livibetter.com>"
```