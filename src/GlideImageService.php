<?php

namespace Roboc\Glide;

use League\Glide\ServerFactory;

/**
 * Class GlideImageService
 * @package Roboc\Glide
 */
class GlideImageService
{
    /**
     * @var string
     */
    protected $sourceFile;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * GlideImageService constructor.
     * @param array $configuration
     */
    public function __construct( array $configuration )
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $sourceFile
     * @return GlideImageService
     */
    public function from( string $sourceFile ): GlideImageService
    {
        return $this->setSourceFile( $sourceFile );
    }

    /**
     * @param string $sourceFile
     * @return GlideImageService
     */
    public function setSourceFile( string $sourceFile ): GlideImageService
    {
        $this->sourceFile = $sourceFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceFile(): string
    {
        return $this->sourceFile;
    }

    /**
     * @param string $diskName
     * @return GlideImageService
     */
    public function setSourceDisk( string $diskName ): GlideImageService
    {
        $this->configuration['source_disk'] = $diskName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceDisk(): string
    {
        return $this->configuration['source_disk'];
    }

    /**
     * @param string $diskName
     * @return GlideImageService
     */
    public function setCacheDisk( string $diskName ): GlideImageService
    {
        $this->configuration['cache_disk'] = $diskName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCacheDisk(): string
    {
        return $this->configuration['cache_disk'];
    }

    /**
     * @param string $diskName
     * @return GlideImageService
     */
    public function setWatermarksDisk( string $diskName ): GlideImageService
    {
        $this->configuration['cache_disk'] = $diskName;

        return $this;
    }

    /**
     * @return string
     */
    public function getWatermarksDisk(): string
    {
        if( !isset( $this->configuration['watermarks_disk'] ) )
        {
            return $this->getSourceDisk();
        }

        return $this->configuration['watermarks_disk'];
    }

    /**
     * @return string
     */
    public function getImageManager(): string
    {
        if( !isset( $this->configuration['driver'] ) )
        {
            return 'gd';
        }

        return $this->configuration['driver'];
    }

    /**
     * @return array
     */
    public function getDefaults(): array
    {
        if( !isset( $this->configuration['defaults'] ) )
        {
            return [];
        }

        return $this->configuration['defaults'];
    }

    /**
     * @return array
     */
    public function getPresets(): array
    {
        if( !isset( $this->configuration['presets'] ) )
        {
            return [];
        }

        return $this->configuration['presets'];
    }

    /**
     * @param null $parameters
     * @return array
     */
    protected function getImageManipulations( $parameters = null ): array
    {
        if( $parameters === null )
        {
            $parameters = [];
        }
        elseif( is_string( $parameters ) )
        {
            $parameters = [
                'p' => $parameters,
            ];
        }

        return (array) $parameters;
    }

    /**
     * @return array
     */
    protected function getGlideParameters()
    {
        return [
            'driver' => $this->getImageManager(),
            'source' => \Storage::disk( $this->getSourceDisk() )->getDriver(),
            'cache' => \Storage::disk( $this->getCacheDisk() )->getDriver(),
            'defaults' => $this->getDefaults(),
            'presets' => $this->getPresets(),
            'watermarks' => \Storage::disk( $this->getWatermarksDisk() )->getDriver(),
        ];
    }

    /**
     * @return \League\Glide\Server
     */
    protected function getGlide()
    {
        return ServerFactory::create( $this->getGlideParameters() );
    }

    /**
     * @param mixed $parameters
     * @return string
     */
    public function getImageUrl( $parameters = null ): string
    {
        $cachedImagePath = $this->getGlide()->makeImage(
            $this->getSourceFile(),
            $this->getImageManipulations( $parameters )
        );

        return \Storage::disk( $this->getCacheDisk() )->url( $cachedImagePath );
    }
}
