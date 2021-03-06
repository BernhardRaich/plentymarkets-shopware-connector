<?php

namespace PlentymarketsAdapter\ResponseParser\Media;

use Assert\Assertion;
use Assert\AssertionFailedException;
use PlentymarketsAdapter\Helper\MediaCategoryHelper;
use PlentymarketsAdapter\PlentymarketsAdapter;
use SystemConnector\IdentityService\IdentityServiceInterface;
use SystemConnector\TransferObject\Media\Media;
use SystemConnector\TransferObject\MediaCategory\MediaCategory;

class MediaResponseParser implements MediaResponseParserInterface
{
    /**
     * @var IdentityServiceInterface
     */
    private $identityService;

    /**
     * @var MediaCategoryHelper
     */
    private $categoryHelper;

    public function __construct(
        IdentityServiceInterface $identityService,
        MediaCategoryHelper $categoryHelper
    ) {
        $this->identityService = $identityService;
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * {@inheritdoc}
     *
     * @throws AssertionFailedException
     */
    public function parse(array $entry): Media
    {
        Assertion::url($entry['link']);

        if (empty($entry['filename'])) {
            $entry['filename'] = basename($entry['link']);
        }

        if (empty($entry['name'])) {
            $entry['name'] = null;
        }

        if (empty($entry['alternateName'])) {
            $entry['alternateName'] = null;
        }

        if (!array_key_exists('translations', $entry)) {
            $entry['translations'] = [];
        }

        if (!array_key_exists('attributes', $entry)) {
            $entry['attributes'] = [];
        }

        if (array_key_exists('mediaCategory', $entry)) {
            $mediaCategories = $this->categoryHelper->getCategories();

            $mediaCategoryIdentity = $this->identityService->findOneOrCreate(
                (string) $mediaCategories[$entry['mediaCategory']]['id'],
                PlentymarketsAdapter::NAME,
                MediaCategory::TYPE
            );

            $entry['mediaCategoryIdentifier'] = $mediaCategoryIdentity->getObjectIdentifier();
        } else {
            $entry['mediaCategoryIdentifier'] = null;
        }

        $entry['hash'] = sha1(json_encode($entry)); // include all fields when computing the hash

        $identity = $this->identityService->findOneOrCreate(
            (string) $entry['id'],
            PlentymarketsAdapter::NAME,
            Media::TYPE
        );

        $media = new Media();
        $media->setIdentifier($identity->getObjectIdentifier());
        $media->setMediaCategoryIdentifier($entry['mediaCategoryIdentifier']);
        $media->setLink($entry['link']);
        $media->setFilename($entry['filename']);
        $media->setHash($entry['hash']);
        $media->setName($entry['name']);
        $media->setAlternateName($entry['alternateName']);
        $media->setTranslations($entry['translations']);
        $media->setAttributes($entry['attributes']);

        return $media;
    }
}
