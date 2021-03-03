<?php
/**
 * @copyright Copyright (c) 2014 Carsten Brandt
 * @license https://github.com/cebe/markdown/blob/master/LICENSE
 * @link https://github.com/cebe/markdown#readme
 */

namespace cebe\markdown\inline;

/**
 * Adds inline emphasizes and strong elements
 */
trait EmphStrongTrait
{
	/**
	 * Parses emphasized and strong elements.
	 * @marker _
	 * @marker *
	 */
	protected function parseEmphStrong($text)
	{
		$marker = $text[0];

		if (!isset($text[1])) {
			return [['text', $text[0]], 1];
		}

		if ($marker == $text[1]) { // strong
			// work around a PHP bug that crashes with a segfault on too much regex backtrack
			// check whether the end marker exists in the text
			// https://github.com/erusev/parsedown/issues/443
			// https://bugs.php.net/bug.php?id=45735
			if (strpos($text, $marker . $marker, 2) === false) {
				return [['text', $text[0] . $text[1]], 2];
			}

			if ($marker === '*' && preg_match('/^[*]{2}((?>\\\\[*]|[^*]|[*][^*]*[*])+?)[*]{2}/s', $text, $matches) ||
				$marker === '_' && preg_match('/^__((?>\\\\_|[^_]|_[^_]*_)+?)__/us', $text, $matches)) {

				return [
					[
						'strong',
						$this->parseInline($matches[1]),
					],
					strlen($matches[0])
				];
			}
		} else { // emph
			// work around a PHP bug that crashes with a segfault on too much regex backtrack
			// check whether the end marker exists in the text
			// https://github.com/erusev/parsedown/issues/443
			// https://bugs.php.net/bug.php?id=45735
			if (strpos($text, $marker, 1) === false) {
				return [['text', $text[0]], 1];
			}

			if ($marker === '*' && preg_match('/^[*]((?>\\\\[*]|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*][^*])/s', $text, $matches) ||
				$marker === '_' && preg_match('/^_((?>\\\\_|[^_]|__[^_]*__)+?)_(?!_[^_])\b/us', $text, $matches)) {
				// if only a single whitespace or nothing is contained in an emphasis, do not consider it valid
				if ($matches[1] === '' || $matches[1] === ' ') {
					return [['text', $text[0]], 1];
				}
				return [
					[
						'emph',
						$this->parseInline($matches[1]),
					],
					strlen($matches[0])
				];
			}
		}
		return [['text', $text[0]], 1];
	}

	protected function renderStrong($block)
	{
		return '<strong>' . $this->renderAbsy($block[1]) . '</strong>';
	}

	protected function renderEmph($block)
	{
		return '<em>' . $this->renderAbsy($block[1]) . '</em>';
	}

    abstract protected function parseInline($text);
    abstract protected function renderAbsy($blocks);
}
