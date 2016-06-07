<?php
/**
 * Crowdin API implementation in PHP.
 *
 * @copyright  Copyright (C) 2016 Nikolai Plath (elkuku)
 * @license    GNU General Public License version 2 or later
 */

namespace ElKuKu\Crowdin\Package;

use ElKuKu\Crowdin\Languagefile;
use ElKuKu\Crowdin\Package;

/**
 * Class Translation
 *
 * @since  1.0
 */
Class Translation extends Package
{
	/**
	 * Upload existing translations to your Crowdin project.
	 *
	 * @param   Languagefile  $languagefile            The translation object.
	 * @param   string        $language                The language tag.
	 * @param   boolean       $importDuplicates        IDK.
	 * @param   boolean       $importEqualSuggestions  IDK.
	 * @param   boolean       $autoImproveImports      IDK.
	 *
	 * @see     https://crowdin.com/page/api/upload-translation
	 * @since   1.0.1
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function upload(
		Languagefile $languagefile, $language, $importDuplicates = false,
		$importEqualSuggestions = false, $autoImproveImports = false)
	{
		$data = [];

		$data[] = [
			'name'     => 'import_duplicates',
			'contents' => (int) $importDuplicates
		];

		$data[] = [
			'name'     => 'import_eq_suggestions',
			'contents' => (int) $importEqualSuggestions
		];

		$data[] = [
			'name'     => 'auto_approve_imported',
			'contents' => (int) $autoImproveImports
		];

		$data[] = [
			'name'     => 'language',
			'contents' => $language
		];

		$data[] = [
			'name'     => 'files[' . $languagefile->getCrowdinPath() . ']',
			'contents' => fopen($languagefile->getLocalPath(), 'r')
		];

		return $this->getHttpClient()
			->post($this->getBasePath('upload-translation'), ['multipart' => $data]);
	}

	/**
	 * Track overall translation and proofreading progresses of each target language.
	 * Default response format is XML.
	 *
	 * @see    https://crowdin.com/page/api/status
	 * @since  1.0.1
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function status()
	{
		return $this->getHttpClient()
			->get($this->getBasePath('status'));
	}
}
