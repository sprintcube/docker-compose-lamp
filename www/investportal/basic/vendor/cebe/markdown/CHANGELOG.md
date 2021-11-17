CHANGELOG
=========

Version 1.2.1 on 26. Mar. 2018
------------------------------

- Improved handling of inline HTML with URL and email tags.
- Improved handling of custom syntax with `[[`, references should not use `[` as the first character in the reference name.

Version 1.2.0 on 14. Mar. 2018
------------------------------

- #50 Do not render empty emphs.
- #69 Improve ABSY for tables, make column and row information directly available in absy (@NathanBaulch)
- #89 Lists should be separated by a HR (@bieleckim)
- #95 Added `TableTrait::composeTable($head, $body)`, for easier overriding of table layout (@maximal, @cebe)
- #111 Improve rendering of successive strongs (@wogsland)
- #132 Improve detection and rendering of fenced code blocks in lists.
- #134 Fix Emph and Strong to allow escaping `*` or `_` inside them.
- #135 GithubMarkdown was not parsing inline code when there are square brackets around it.
- #151 Fixed table rendering for lines begining with | for GFM (@GenaBitu)
- Improved table rendering, allow single column tables.

Version 1.1.2 on 16. Jul 2017
-----------------------------

- #126 Fixed crash on empty lines that extend a lazy list
- #128 Fix table renderer which including default alignment (@tanakahisateru)
- #129  Use given encoded URL if decoded URL text looks insecure, e.g. uses broken UTF-8 (@tanakahisateru)
- Added a workaround for a [PHP bug](https://bugs.php.net/bug.php?id=45735) which exists in versions `<` 7.0, where `preg_match()` causes a segfault
  on [catastropic backtracking][] in emph/strong parsing.
  
[catastropic backtracking]: http://www.regular-expressions.info/catastrophic.html

Version 1.1.1 on 14. Sep 2016
-----------------------------

- #112 Fixed parsing for custom self-closing HTML tags
- #113 improve extensibility by making `prepareMarkers()` protected and add `parseBlock()` method
- #114 better handling of continued inline HTML in paragraphs

Version 1.1.0 on 06. Mar. 2015
------------------------------

- improve compatibility with github flavored markdown
- #64 fixed some rendering issue with emph and strong
- #56 trailing and leading spaces in a link are now ignored
- fixed various issues with table rendering
- #98 Fix PHP fatal error when maximumNestingLevel was reached (@tanakahisateru)
- refactored nested and lazy list handling, improved overall list rendering consistency
- Lines containing "0" where skipped or considered empty in some cases (@tanakahisateru)
- #54 escape characters are now also considered inside of urls

Version 1.0.1 on 25. Oct. 2014
------------------------------

- Fixed the `bin/markdown` script to work with composer autoloader (c497bada0e15f61873ba6b2e29f4bb8b3ef2a489)
- #74 fixed a bug that caused a bunch of broken characters when non-ASCII input was given. Parser now handles UTF-8 input correctly. Other encodings are currently untested, UTF-8 is recommended.

Version 1.0.0 on 12. Oct. 2014
------------------------------

This is the first stable release of version 1.0 which is incompatible to the 0.9.x branch regarding the internal API which is used when extending the Markdown parser. The external API has no breaking changes. The rendered Markdown however has changed in some edge cases and some rendering issues have been fixed.

The parser got a bit slower compared to earlier versions but is able to parse Markdown more accurately and uses an abstract syntax tree as the internal representation of the parsed text which allows extensions to work with the parsed Markdown in many ways including rendering as other formats than HTML.

For more details about the changes see the [release message of 1.0.0-rc](https://github.com/cebe/markdown/releases/tag/1.0.0-rc).

You can try it out on the website: <http://markdown.cebe.cc/try>

The parser is now also regsitered on the [Babelmark 2 page](http://johnmacfarlane.net/babelmark2/?normalize=1&text=Hello+**World**!) by [John MacFarlane](http://johnmacfarlane.net/) which you can use to compare Markdown output of different parsers.

Version 1.0.0-rc on 10. Oct. 2014
---------------------------------

- #21 speed up inline parsing using [strpbrk](http://www.php.net/manual/de/function.strpbrk.php) about 20% speedup compared to parsing before.
- #24 CLI script now sends all error output to stderr instead of stdout
- #25 Added partial support for the Markdown Extra flavor
- #10 GithubMarkdown is now fully supported including tables
- #67 All Markdown classes are now composed out of php traits
- #67 The way to extend markdown has changed due to the introduction of an abstract syntax tree. See https://github.com/cebe/markdown/commit/dd2d0faa71b630e982d6651476872469b927db6d for how it changes or read the new README.
- Introduced an abstract syntax tree as an intermediate representation between parsing steps.
  This not only fixes some issues with nested block elements but also allows manipulation of the markdown
  before rendering.
- This version also fixes serveral rendering issues.

Version 0.9.2 on 18. Feb. 2014 
------------------------------

- #27 Fixed some rendering problems with block elements not separated by newlines

Version 0.9.1 on 18. Feb. 2014
------------------------------

Fixed an issue with inline markers that begin with the same character e.g. `[` and `[[`.

Version 0.9.0 on 18. Feb. 2014
------------------------------

The initial release.

- Complete implementation of the original Markdown spec
- GFM without tables
- a command line tool for markdown parsing
