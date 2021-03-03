<?php
/**
 * @copyright Copyright (c) 2014 Carsten Brandt
 * @license https://github.com/cebe/markdown/blob/master/LICENSE
 * @link https://github.com/cebe/markdown#readme
 */

namespace cebe\markdown\block;

/**
 * Adds the fenced code blocks
 *
 * automatically included 4 space indented code blocks
 */
trait FencedCodeTrait
{
	use CodeTrait;

	/**
	 * identify a line as the beginning of a fenced code block.
	 */
	protected function identifyFencedCode($line)
	{
		return ($line[0] === '`' && strncmp($line, '```', 3) === 0) ||
			   ($line[0] === '~' && strncmp($line, '~~~', 3) === 0) ||
			   (isset($line[3]) && (
					($line[3] === '`' && strncmp(ltrim($line), '```', 3) === 0) ||
					($line[3] === '~' && strncmp(ltrim($line), '~~~', 3) === 0)
			   ));
	}

	/**
	 * Consume lines for a fenced code block
	 */
	protected function consumeFencedCode($lines, $current)
	{
		$line = ltrim($lines[$current]);
		$fence = substr($line, 0, $pos = strrpos($line, $line[0]) + 1);
		$language = rtrim(substr($line, $pos));
		// consume until end fence
		$content = [];
		for ($i = $current + 1, $count = count($lines); $i < $count; $i++) {
			if (($pos = strpos($line = $lines[$i], $fence)) === false || $pos > 3) {
				$content[] = $line;
			} else {
				break;
			}
		}
		$block = [
			'code',
			'content' => implode("\n", $content),
		];
		if (!empty($language)) {
			$block['language'] = $language;
		}
		return [$block, $i];
	}
}
