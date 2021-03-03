<?php
/**
 * @copyright Copyright (c) 2014 Carsten Brandt
 * @license https://github.com/cebe/markdown/blob/master/LICENSE
 * @link https://github.com/cebe/markdown#readme
 */

namespace cebe\markdown\tests;

use cebe\markdown\GithubMarkdown;

/**
 * Test case for the github flavored markdown.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @group github
 */
class GithubMarkdownTest extends BaseMarkdownTest
{
	public function createMarkdown()
	{
		return new GithubMarkdown();
	}

	public function getDataPaths()
	{
		return [
			'markdown-data' => __DIR__ . '/markdown-data',
			'github-data' => __DIR__ . '/github-data',
		];
	}

	public function testNewlines()
	{
		$markdown = $this->createMarkdown();
		$this->assertEquals("This is text<br />\nnewline\nnewline.", $markdown->parseParagraph("This is text  \nnewline\nnewline."));
		$markdown->enableNewlines = true;
		$this->assertEquals("This is text<br />\nnewline<br />\nnewline.", $markdown->parseParagraph("This is text  \nnewline\nnewline."));

		$this->assertEquals("<p>This is text</p>\n<p>newline<br />\nnewline.</p>\n", $markdown->parse("This is text\n\nnewline\nnewline."));
	}

	public function dataFiles()
	{
		$files = parent::dataFiles();
		foreach($files as $i => $f) {
			// skip files that are different in github MD
			if ($f[0] === 'markdown-data' && (
					$f[1] === 'list-marker-in-paragraph' ||
					$f[1] === 'dense-block-markers'
				)) {
				unset($files[$i]);
			}
		}
		return $files;
	}

	public function testKeepZeroAlive()
	{
		$parser = $this->createMarkdown();

		$this->assertEquals("0", $parser->parseParagraph("0"));
		$this->assertEquals("<p>0</p>\n", $parser->parse("0"));
	}

	public function testAutoLinkLabelingWithEncodedUrl()
	{
		$parser = $this->createMarkdown();

		$utfText = "\xe3\x81\x82\xe3\x81\x84\xe3\x81\x86\xe3\x81\x88\xe3\x81\x8a";
		$utfNaturalUrl = "http://example.com/" . $utfText;
		$utfEncodedUrl = "http://example.com/" . urlencode($utfText);
		$eucEncodedUrl = "http://example.com/" . urlencode(mb_convert_encoding($utfText, 'EUC-JP', 'UTF-8'));

		$this->assertStringEndsWith(">{$utfNaturalUrl}</a>", $parser->parseParagraph($utfNaturalUrl), "Natural UTF-8 URL needs no conversion.");
		$this->assertStringEndsWith(">{$utfNaturalUrl}</a>", $parser->parseParagraph($utfEncodedUrl), "Encoded UTF-8 URL will be converted to readable format.");
		$this->assertStringEndsWith(">{$eucEncodedUrl}</a>", $parser->parseParagraph($eucEncodedUrl), "Non UTF-8 URL should never be converted.");
		// See: \cebe\markdown\inline\UrlLinkTrait::renderAutoUrl
	}
}
