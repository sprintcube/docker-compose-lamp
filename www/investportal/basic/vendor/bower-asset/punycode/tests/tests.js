(function(root) {
	'use strict';

	var noop = Function.prototype;

	var load = (typeof require == 'function' && !(root.define && define.amd)) ?
		require :
		(!root.document && root.java && root.load) || noop;

	var QUnit = (function() {
		return root.QUnit || (
			root.addEventListener || (root.addEventListener = noop),
			root.setTimeout || (root.setTimeout = noop),
			root.QUnit = load('../node_modules/qunitjs/qunit/qunit.js') || root.QUnit,
			addEventListener === noop && delete root.addEventListener,
			root.QUnit
		);
	}());

	var qe = load('../node_modules/qunit-extras/qunit-extras.js');
	if (qe) {
		qe.runInContext(root);
	}

	/** The `punycode` object to test */
	var punycode = root.punycode || (root.punycode = (
		punycode = load('../punycode.js') || root.punycode,
		punycode = punycode.punycode || punycode
	));

	// Quick and dirty test to see if we’re in Node or PhantomJS
	var runExtendedTests = (function() {
		try {
			return process.argv[0] == 'node' || root.phantom;
		} catch (exception) { }
	}());

	/** Data that will be used in the tests */
	var allSymbols = runExtendedTests && require('./data.js');
	var testData = {
		'strings': [
			{
				'description': 'a single basic code point',
				'decoded': 'Bach',
				'encoded': 'Bach-'
			},
			{
				'description': 'a single non-ASCII character',
				'decoded': '\xFC',
				'encoded': 'tda'
			},
			{
				'description': 'multiple non-ASCII characters',
				'decoded': '\xFC\xEB\xE4\xF6\u2665',
				'encoded': '4can8av2009b'
			},
			{
				'description': 'mix of ASCII and non-ASCII characters',
				'decoded': 'b\xFCcher',
				'encoded': 'bcher-kva'
			},
			{
				'description': 'long string with both ASCII and non-ASCII characters',
				'decoded': 'Willst du die Bl\xFCthe des fr\xFChen, die Fr\xFCchte des sp\xE4teren Jahres',
				'encoded': 'Willst du die Blthe des frhen, die Frchte des spteren Jahres-x9e96lkal'
			},
			// http://tools.ietf.org/html/rfc3492#section-7.1
			{
				'description': 'Arabic (Egyptian)',
				'decoded': '\u0644\u064A\u0647\u0645\u0627\u0628\u062A\u0643\u0644\u0645\u0648\u0634\u0639\u0631\u0628\u064A\u061F',
				'encoded': 'egbpdaj6bu4bxfgehfvwxn'
			},
			{
				'description': 'Chinese (simplified)',
				'decoded': '\u4ED6\u4EEC\u4E3A\u4EC0\u4E48\u4E0D\u8BF4\u4E2d\u6587',
				'encoded': 'ihqwcrb4cv8a8dqg056pqjye'
			},
			{
				'description': 'Chinese (traditional)',
				'decoded': '\u4ED6\u5011\u7232\u4EC0\u9EBD\u4E0D\u8AAA\u4E2D\u6587',
				'encoded': 'ihqwctvzc91f659drss3x8bo0yb'
			},
			{
				'description': 'Czech',
				'decoded': 'Pro\u010Dprost\u011Bnemluv\xED\u010Desky',
				'encoded': 'Proprostnemluvesky-uyb24dma41a'
			},
			{
				'description': 'Hebrew',
				'decoded': '\u05DC\u05DE\u05D4\u05D4\u05DD\u05E4\u05E9\u05D5\u05D8\u05DC\u05D0\u05DE\u05D3\u05D1\u05E8\u05D9\u05DD\u05E2\u05D1\u05E8\u05D9\u05EA',
				'encoded': '4dbcagdahymbxekheh6e0a7fei0b'
			},
			{
				'description': 'Hindi (Devanagari)',
				'decoded': '\u092F\u0939\u0932\u094B\u0917\u0939\u093F\u0928\u094D\u0926\u0940\u0915\u094D\u092F\u094B\u0902\u0928\u0939\u0940\u0902\u092C\u094B\u0932\u0938\u0915\u0924\u0947\u0939\u0948\u0902',
				'encoded': 'i1baa7eci9glrd9b2ae1bj0hfcgg6iyaf8o0a1dig0cd'
			},
			{
				'description': 'Japanese (kanji and hiragana)',
				'decoded': '\u306A\u305C\u307F\u3093\u306A\u65E5\u672C\u8A9E\u3092\u8A71\u3057\u3066\u304F\u308C\u306A\u3044\u306E\u304B',
				'encoded': 'n8jok5ay5dzabd5bym9f0cm5685rrjetr6pdxa'
			},
			{
				'description': 'Korean (Hangul syllables)',
				'decoded': '\uC138\uACC4\uC758\uBAA8\uB4E0\uC0AC\uB78C\uB4E4\uC774\uD55C\uAD6D\uC5B4\uB97C\uC774\uD574\uD55C\uB2E4\uBA74\uC5BC\uB9C8\uB098\uC88B\uC744\uAE4C',
				'encoded': '989aomsvi5e83db1d2a355cv1e0vak1dwrv93d5xbh15a0dt30a5jpsd879ccm6fea98c'
			},
			/**
			 * As there's no way to do it in JavaScript, Punycode.js doesn't support
			 * mixed-case annotation (which is entirely optional as per the RFC).
			 * So, while the RFC sample string encodes to:
			 * `b1abfaaepdrnnbgefbaDotcwatmq2g4l`
			 * Without mixed-case annotation it has to encode to:
			 * `b1abfaaepdrnnbgefbadotcwatmq2g4l`
			 * https://github.com/bestiejs/punycode.js/issues/3
			 */
			{
				'description': 'Russian (Cyrillic)',
				'decoded': '\u043F\u043E\u0447\u0435\u043C\u0443\u0436\u0435\u043E\u043D\u0438\u043D\u0435\u0433\u043E\u0432\u043E\u0440\u044F\u0442\u043F\u043E\u0440\u0443\u0441\u0441\u043A\u0438',
				'encoded': 'b1abfaaepdrnnbgefbadotcwatmq2g4l'
			},
			{
				'description': 'Spanish',
				'decoded': 'Porqu\xE9nopuedensimplementehablarenEspa\xF1ol',
				'encoded': 'PorqunopuedensimplementehablarenEspaol-fmd56a'
			},
			{
				'description': 'Vietnamese',
				'decoded': 'T\u1EA1isaoh\u1ECDkh\xF4ngth\u1EC3ch\u1EC9n\xF3iti\u1EBFngVi\u1EC7t',
				'encoded': 'TisaohkhngthchnitingVit-kjcr8268qyxafd2f1b9g'
			},
			{
				'decoded': '3\u5E74B\u7D44\u91D1\u516B\u5148\u751F',
				'encoded': '3B-ww4c5e180e575a65lsy2b'
			},
			{
				'decoded': '\u5B89\u5BA4\u5948\u7F8E\u6075-with-SUPER-MONKEYS',
				'encoded': '-with-SUPER-MONKEYS-pc58ag80a8qai00g7n9n'
			},
			{
				'decoded': 'Hello-Another-Way-\u305D\u308C\u305E\u308C\u306E\u5834\u6240',
				'encoded': 'Hello-Another-Way--fc4qua05auwb3674vfr0b'
			},
			{
				'decoded': '\u3072\u3068\u3064\u5C4B\u6839\u306E\u4E0B2',
				'encoded': '2-u9tlzr9756bt3uc0v'
			},
			{
				'decoded': 'Maji\u3067Koi\u3059\u308B5\u79D2\u524D',
				'encoded': 'MajiKoi5-783gue6qz075azm5e'
			},
			{
				'decoded': '\u30D1\u30D5\u30A3\u30FCde\u30EB\u30F3\u30D0',
				'encoded': 'de-jg4avhby1noc0d'
			},
			{
				'decoded': '\u305D\u306E\u30B9\u30D4\u30FC\u30C9\u3067',
				'encoded': 'd9juau41awczczp'
			},
			/**
			 * This example is an ASCII string that breaks the existing rules for host
			 * name labels. (It's not a realistic example for IDNA, because IDNA never
			 * encodes pure ASCII labels.)
			 */
			{
				'description': 'ASCII string that breaks the existing rules for host-name labels',
				'decoded': '-> $1.00 <-',
				'encoded': '-> $1.00 <--'
			}
		],
		'ucs2': [
			// Every Unicode symbol is tested separately. These are just the extra
			// tests for symbol combinations:
			{
				'description': 'Consecutive astral symbols',
				'decoded': [127829, 119808, 119558, 119638],
				'encoded': '\uD83C\uDF55\uD835\uDC00\uD834\uDF06\uD834\uDF56'
			},
			{
				'description': 'U+D800 (high surrogate) followed by non-surrogates',
				'decoded': [55296, 97, 98],
				'encoded': '\uD800ab'
			},
			{
				'description': 'U+DC00 (low surrogate) followed by non-surrogates',
				'decoded': [56320, 97, 98],
				'encoded': '\uDC00ab'
			},
			{
				'description': 'High surrogate followed by another high surrogate',
				'decoded': [0xD800, 0xD800],
				'encoded': '\uD800\uD800'
			},
			{
				'description': 'Unmatched high surrogate, followed by a surrogate pair, followed by an unmatched high surrogate',
				'decoded': [0xD800, 0x1D306, 0xD800],
				'encoded': '\uD800\uD834\uDF06\uD800'
			},
			{
				'description': 'Low surrogate followed by another low surrogate',
				'decoded': [0xDC00, 0xDC00],
				'encoded': '\uDC00\uDC00'
			},
			{
				'description': 'Unmatched low surrogate, followed by a surrogate pair, followed by an unmatched low surrogate',
				'decoded': [0xDC00, 0x1D306, 0xDC00],
				'encoded': '\uDC00\uD834\uDF06\uDC00'
			}
		],
		'domains': [
			{
				'decoded': 'ma\xF1ana.com',
				'encoded': 'xn--maana-pta.com'
			},
			{ // https://github.com/bestiejs/punycode.js/issues/17
				'decoded': 'example.com.',
				'encoded': 'example.com.'
			},
			{
				'decoded': 'b\xFCcher.com',
				'encoded': 'xn--bcher-kva.com'
			},
			{
				'decoded': 'caf\xE9.com',
				'encoded': 'xn--caf-dma.com'
			},
			{
				'decoded': '\u2603-\u2318.com',
				'encoded': 'xn----dqo34k.com'
			},
			{
				'decoded': '\uD400\u2603-\u2318.com',
				'encoded': 'xn----dqo34kn65z.com'
			},
			{
				'description': 'Emoji',
				'decoded': '\uD83D\uDCA9.la',
				'encoded': 'xn--ls8h.la'
			},
			{
				'description': 'Email address',
				'decoded': '\u0434\u0436\u0443\u043C\u043B\u0430@\u0434\u0436p\u0443\u043C\u043B\u0430\u0442\u0435\u0441\u0442.b\u0440\u0444a',
				'encoded': '\u0434\u0436\u0443\u043C\u043B\u0430@xn--p-8sbkgc5ag7bhce.xn--ba-lmcq'
			}
		],
		'separators': [
			{
				'description': 'Using U+002E as separator',
				'decoded': 'ma\xF1ana\x2Ecom',
				'encoded': 'xn--maana-pta.com'
			},
			{
				'description': 'Using U+3002 as separator',
				'decoded': 'ma\xF1ana\u3002com',
				'encoded': 'xn--maana-pta.com'
			},
			{
				'description': 'Using U+FF0E as separator',
				'decoded': 'ma\xF1ana\uFF0Ecom',
				'encoded': 'xn--maana-pta.com'
			},
			{
				'description': 'Using U+FF61 as separator',
				'decoded': 'ma\xF1ana\uFF61com',
				'encoded': 'xn--maana-pta.com'
			}
		]
	};

	/*--------------------------------------------------------------------------*/

	// simple `Array#forEach`-like helper function
	function each(array, fn) {
		var index = array.length;
		while (index--) {
			fn(array[index], index);
		}
	}

	// `throws` is a reserved word in ES3; alias it to avoid errors
	var raises = QUnit.assert['throws'];

	/*--------------------------------------------------------------------------*/

	// Explicitly call `QUnit.module()` instead of `module()` in case we are in
	// a CLI environment.
	QUnit.module('punycode');

	test('punycode.ucs2.decode', function() {
		// Test all Unicode code points separately.
		runExtendedTests && each(allSymbols, function(string, codePoint) {
			deepEqual(punycode.ucs2.decode(string), [codePoint], 'Decoding symbol with code point ' + codePoint);
		});
		each(testData.ucs2, function(object) {
			deepEqual(punycode.ucs2.decode(object.encoded), object.decoded, object.description);
		});
		raises(
			function() {
				punycode.decode('\x81-');
			},
			RangeError,
			'RangeError: Illegal input >= 0x80 (not a basic code point)'
		);
		raises(
			function() {
				punycode.decode('\x81');
			},
			RangeError,
			'RangeError: Overflow: input needs wider integers to process'
		);
	});

	test('punycode.ucs2.encode', function() {
		// test all Unicode code points separately
		runExtendedTests && each(allSymbols, function(string, codePoint) {
			deepEqual(punycode.ucs2.encode([codePoint]), string, 'Encoding code point ' + codePoint);
		});
		each(testData.ucs2, function(object) {
			equal(punycode.ucs2.encode(object.decoded), object.encoded, object.description);
		});
		var codePoints = [0x61, 0x62, 0x63];
		var result = punycode.ucs2.encode(codePoints);
		equal(result, 'abc');
		deepEqual(codePoints, [0x61, 0x62, 0x63], 'Do not mutate argument array');
	});

	test('punycode.decode', function() {
		each(testData.strings, function(object) {
			equal(punycode.decode(object.encoded), object.decoded, object.description);
		});
		equal(punycode.decode('ZZZ'), '\u7BA5', 'Uppercase Z');
	});

	test('punycode.encode', function() {
		each(testData.strings, function(object) {
			equal(punycode.encode(object.decoded), object.encoded, object.description);
		});
	});

	test('punycode.toUnicode', function() {
		each(testData.domains, function(object) {
			equal(punycode.toUnicode(object.encoded), object.decoded, object.description);
		});
		/**
		 * Domain names (or other strings) that don't start with `xn--` may not be
		 * converted.
		 */
		each(testData.strings, function(object) {
			var message = 'Domain names (or other strings) that don\'t start with `xn--` may not be converted';
			equal(punycode.toUnicode(object.encoded), object.encoded, message);
			equal(punycode.toUnicode(object.decoded), object.decoded, message);
		});
	});

	test('punycode.toASCII', function() {
		each(testData.domains, function(object) {
			equal(punycode.toASCII(object.decoded), object.encoded, object.description);
		});
		/**
		 * Domain names (or other strings) that are already in ASCII may not be
		 * converted.
		 */
		each(testData.strings, function(object) {
			equal(punycode.toASCII(object.encoded), object.encoded, 'Domain names (or other strings) that are already in ASCII may not be converted');
		});
		/**
		 * IDNA2003 separators must be supported for backwards compatibility.
		 */
		each(testData.separators, function(object) {
			var message = 'IDNA2003 separators must be supported for backwards compatibility';
			equal(punycode.toASCII(object.decoded), object.encoded, message);
		});
	});

	/*--------------------------------------------------------------------------*/

	// configure QUnit and call `QUnit.start()` for
	// Narwhal, Node.js, PhantomJS, Rhino, and RingoJS
	if (!root.document || root.phantom) {
		QUnit.config.noglobals = true;
		QUnit.start();
	}

}(typeof global == 'object' && global || this));
