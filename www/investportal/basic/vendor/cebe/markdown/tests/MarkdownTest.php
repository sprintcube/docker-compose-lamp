<?php
/**
 * @copyright Copyright (c) 2014 Carsten Brandt
 * @license https://github.com/cebe/markdown/blob/master/LICENSE
 * @link https://github.com/cebe/markdown#readme
 */

namespace cebe\markdown\tests;

use cebe\markdown\Markdown;

/**
 * Test case for traditional markdown.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @group default
 */
class MarkdownTest extends BaseMarkdownTest
{
	public function createMarkdown()
	{
		return new Markdown();
	}

	public function getDataPaths()
	{
		return [
			'markdown-data' => __DIR__ . '/markdown-data',
		];
	}

	public function testEdgeCases()
	{
		$this->assertEquals("<p>&amp;</p>\n", $this->createMarkdown()->parse('&'));
		$this->assertEquals("<p>&lt;</p>\n", $this->createMarkdown()->parse('<'));
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

		$this->assertStringEndsWith(">{$utfNaturalUrl}</a>", $parser->parseParagraph("<{$utfNaturalUrl}>"), "Natural UTF-8 URL needs no conversion.");
		$this->assertStringEndsWith(">{$utfNaturalUrl}</a>", $parser->parseParagraph("<{$utfEncodedUrl}>"), "Encoded UTF-8 URL will be converted to readable format.");
		$this->assertStringEndsWith(">{$eucEncodedUrl}</a>", $parser->parseParagraph("<{$eucEncodedUrl}>"), "Non UTF-8 URL should never be converted.");
		// See: \cebe\markdown\inline\LinkTrait::renderUrl
	}
}
