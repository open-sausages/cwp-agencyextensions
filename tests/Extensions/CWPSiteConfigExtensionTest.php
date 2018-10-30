<?php

namespace CWP\AgencyExtensions\Tests\Extensions;

use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\TextField;
use SilverStripe\SiteConfig\SiteConfig;

class CWPSiteConfigExtensionTest extends SapphireTest
{
    protected $usesDatabase = true;

    /**
     * Ensure that the two "search caption" fields exist and are in the right tab
     */
    public function testConfigurableSearchLabelsExistAndAreInCorrectTab()
    {
        $fields = SiteConfig::create()->getCMSFields();
        $this->assertInstanceOf(TextField::class, $fields->fieldByName('Root.SearchOptions.EmptySearch'));
        $this->assertInstanceOf(TextField::class, $fields->fieldByName('Root.SearchOptions.NoSearchResults'));
    }

    /**
     * Ensure theme options are returned in the expected format without any excluded values
     */
    public function testGetThemeOptionsExcluding()
    {
        Config::modify()->set(SiteConfig::class, 'theme_colors', [
            'color1' => [
                'Title' => 'Color 1',
                'CSSClass' => 'color-1',
                'Color' => '#111111',
            ],
            'color2' => [
                'Title' => 'Color 2',
                'CSSClass' => 'color-2',
                'Color' => '#222222',
            ],
        ]);
        $siteConfig = SiteConfig::create();

        // Returns all colors by default
        $themeColors = $siteConfig->getThemeOptionsExcluding();
        $this->assertEquals([
            [
                'Title' => 'Color 1',
                'CSSClass' => 'color-1',
                'Color' => '#111111',
            ],
            [
                'Title' => 'Color 2',
                'CSSClass' => 'color-2',
                'Color' => '#222222',
            ],
        ], $themeColors);

        // Returns colors without excludedColors
        $themeColors = $siteConfig->getThemeOptionsExcluding(['color-1']);
        $this->assertEquals([
            [
                'Title' => 'Color 2',
                'CSSClass' => 'color-2',
                'Color' => '#222222',
            ],
        ], $themeColors);
    }
}
