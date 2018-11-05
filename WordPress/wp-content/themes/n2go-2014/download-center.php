<?php

/**
 * Utility base class that makes it easier to fill in readonly value objects.
 * @internal
 */
abstract class ImmutableValueObject
{
    protected $data = [];

    final public function __construct(array $properties = [])
    {
        $this->data = array_merge($this->data, $properties);
    }

    final public function __get($name)
    {
        if (!array_key_exists($name, $this->data)) {
            throw new RuntimeException(sprintf('Undefined property: %s::%s', __CLASS__, $name));
        }

        return $this->data[$name];
    }

    final public function __set($name, $value)
    {
        throw new RuntimeException(sprintf(
            '%s property: %s::%s',
            array_key_exists($name, $this->data) ? 'Readonly' : 'Undefined',
            __CLASS__,
            $name
        ));
    }

    final public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }
}

/**
 * @internal This is just a stub.
 *
 * @property string $id Unique ID of the integration
 * @property string $name Full name of integration, eg; 'WordPress'
 * @property string $abbreviation Short code of integration, eg; 'MAG' for Magento or 'WP' for WordPress
 * @property string $imageUrl Logo of the integration, usually in SVG format
 * @property string $helpUrl User guide URL - can be empty, in which case disable or hide help link
 * @property string $type A value from: 'CRM', 'CMS' or 'Webshop'
 * @property IntegrationSystem[] $items List of supported integrations (plugins or connectors bound to one system)
 */
class Integration extends ImmutableValueObject {
    private $Systype = '';
    
    // Main Loop
    public function MainLoop() {
        foreach ( $this->data as $MainLoopKey => $MainLoopValue ) {
            $Loops[] = $MainLoopValue;
        }

        foreach ($Loops as $LoopKey => $LoopValue) {
            $AllData[] = $LoopValue->data;
        }

        return $AllData;
    }

    // Get Types
    public function Type() {
        foreach ($this->MainLoop() as $KeyType => $ValuType) {
            $type[] = $ValuType['type'];
        }
        $type = array_unique($type);
        return $type;
    }

    // Get systems with same type
    public function GetSystemsViaTypes($Systype) {
        foreach ($this->MainLoop() as $KeyType => $ValuType) {
            if ( $Systype == $ValuType['type'] ) {
                $SysTypeArrays[] = $ValuType;
            }
        }
        return $SysTypeArrays;
    }


}

/**
 * @internal This is just a stub.
 *
 * @property string $id Unique ID of the integration system
 * @property string $edition Eg; 'v4.2', 'v3 and older' or 'All Versions' - used in combination with integration
 * @property int $position Used for ordering
 * @property IntegrationPlugin[] $plugins List of plugins that support this edition.
 * @property IntegrationConnector[] $connectors List of connectors that support this edition.
 */
class IntegrationSystem extends ImmutableValueObject {

    // Get All Systems
    public function AllSystems() {
        foreach ($this->data as $AllSystemsKeys => $AllSystemsArrays) {
                $AllSystems = $AllSystemsArrays->data;
        }
        return $AllSystems;
    }

    //Get Editions
    public function Editions() {
        return $this->AllSystems()['edition'];
    }

    // Get Plugins
    public function Plugins() {
        return $this->AllSystems()['plugins'];
    }

}

/**
 * @internal This is just a stub.
 *
 * @property string $id Unique ID of the plugin
 * @property string $version Eg; '4000' or '4.0.0.0' - if it is 4 chars and no dots, then it should be formatted with dots between each char.
 * @property string $editionFlag Unique key of the edition
 * @property string $url Full URL for download the plugin. This may be a direct download or a page showcasing the plugin in a marketplace.
 */
class IntegrationPlugin extends ImmutableValueObject {

    private $SysName = '';
    private $SysEdition = '';
    private $EditionNumber = '';

    // Get All Plugins
    public function AllPlugins() {
        foreach ($this->data as $PluginsKeys => $PluginsArrays) {
            $Plugins[] = $PluginsArrays->data;
        }
        return $Plugins;
    }

    // Get Editions Plugins (specific plugins)
    public function EditionPlugins($EditionNumber) {
        foreach ($this->AllPlugins() as $EditionPluginsKeys => $EditionPluginsArrays) {
           if ( $EditionPluginsArrays['editionFlag'] == $EditionNumber ) {
                $OutPlugins[] = $EditionPluginsArrays;
           }
        }
        return $OutPlugins;
    }

    // Get Plugins Group for optgroups
    public function AllPluginsGroup() {
        foreach ($this->AllPlugins() as $PluginsGroupKeys => $PluginsGroupArrays) {
            $PluginsGroup[] =  $PluginsGroupArrays; 
        }

        $PluginGroup = [];
        foreach ($PluginsGroup as $EachPlugin) {
            $PluginGroup[$EachPlugin['editionFlag']][] = $EachPlugin;
        }
        return $PluginGroup;
    }


    // Work with Ajax
    public function GetSystemsViaNameAndEdition($SysName,$SysEdition) {

        foreach ( $this->data as $MainLoopKey => $MainLoopValue ) {
            $Loops[] = $MainLoopValue;
        }

        foreach ($Loops as $LoopKey => $LoopValue) {
            $AllData[] = $LoopValue->data;
        }

        foreach ($AllData as $KeyType => $ValuType) {
            if ( $SysName == $ValuType['name'] ) {
                $SysNameArray[] = $ValuType;
            }
        }

        foreach ($SysNameArray as $SysNameKey => $SysNameItems ) {
            $Items = $SysNameItems['items'];
        }

        foreach ( $Items as $ItemsKey => $ItemsArray ) {
            $Plugins = $ItemsArray->data;
        }

        foreach ($Plugins['plugins'] as $PluginsKey => $PluginsArray) {
            $AllPlugins[] = $PluginsArray->data;
        }

        foreach ($AllPlugins as $AllPluginsKey => $AllPluginsValue) {
           if ( $SysEdition == $AllPluginsValue['editionFlag']) {
               $OutPlugins[] = $AllPluginsValue;
           }
        }

        return $OutPlugins;
    }
}

/**
 * @internal This is just a stub.
 *
 * @property string $id Unique ID of the connector
 * @property string $version Eg; '4000' or '4.0.0.0' - if it is 4 chars and no dots, then it should be formatted with dots between each char.
 */
class IntegrationConnector extends ImmutableValueObject {}

/** @var Integration[] $sampleData */
$sampleData = [
    new Integration([
        'id' => '1',
        'name' => 'Amazon',
        'abbreviation' => 'AM',
        'imageUrl' => '//files-staging.newsletter2go.com/integration/amazon.svg',
        'helpUrl' => 'https://www.newsletter2go.de/features/amazon-newsletter-integration/',
        'type' => 'Webshop',
        'items' => [
            new IntegrationSystem([
                'id' => '1',
                'edition' => [
                    'v4.2',
                    'v3.1',
                    'v3.0.3'
                ],
                'position' => 0,
                'plugins' => [
                    new IntegrationPlugin(['id' => '1', 'editionFlag' => 'v4.2' , 'version' => '4200', 'url' => 'https://www.newsletter2go.de/plugins/wordpress/wp_v4_2_00.zip']),
                    new IntegrationPlugin(['id' => '2', 'editionFlag' => 'v3.1' , 'version' => '3100', 'url' => 'https://www.newsletter2go.de/plugins/wordpress/wp_v3_0_00.zip']),
                    new IntegrationPlugin(['id' => '3', 'editionFlag' => 'v3.0.3' , 'version' => '3003', 'url' => 'https://www.newsletter2go.de/plugins/wordpress/wp_v3_0_03.zip']),
                ],
                'connectors' => [
                    new IntegrationConnector(['id' => '1', 'version' => '3000']),
                ],
            ])
        ]
    ]),
    new Integration([
        'id' => '2',
        'name' => 'Lightspeed eCom',
        'abbreviation' => 'LS',
        'imageUrl' => '//files-staging.newsletter2go.com/integration/lightspeed.svg',
        'helpUrl' => '',
        'type' => 'Webshop',
        'items' => [
            new IntegrationSystem([
                'id' => '2',
                'edition' => 'All Versions',
                'position' => 0,
                'plugins' => [],
                'connectors' => [
                    new IntegrationConnector(['id' => '2', 'version' => '3000']),
                    new IntegrationConnector(['id' => '3', 'version' => '3001']),
                    new IntegrationConnector(['id' => '4', 'version' => '3002']),
                    new IntegrationConnector(['id' => '5', 'version' => '3003']),
                ],
            ])
        ]
    ]),
    new Integration([
        'id' => '3',
        'name' => 'WordPress',
        'abbreviation' => 'WP',
        'imageUrl' => '//files-staging.newsletter2go.com/integration/wordpress.svg',
        'helpUrl' => 'https://www.newsletter2go.com/help/integration-api/set-up-wordpress-plug-in/',
        'type' => 'CMS',
        'items' => [
            new IntegrationSystem([
                'id' => '3',
                'edition' => [
                    'All Versions',
                    'v4.6',
                    'v3.0 and older'
                ],
                'position' => 0,
                'plugins' => [
                    new IntegrationPlugin(['id' => '1', 'editionFlag' => 'v3.0 and older' , 'version' => '2100', 'url' => 'https://www.newsletter2go.de/plugins/wordpress/wp_v2_1_00.zip']),
                    new IntegrationPlugin(['id' => '2', 'editionFlag' => 'v3.0 and older' , 'version' => '3100', 'url' => 'https://www.newsletter2go.de/plugins/wordpress/wp_v3_0_00.zip']),
                    new IntegrationPlugin(['id' => '3', 'editionFlag' => 'v3.0 and older' , 'version' => '3003', 'url' => 'https://www.newsletter2go.de/plugins/wordpress/wp_v3_0_03.zip']),
                    new IntegrationPlugin(['id' => '4', 'editionFlag' => 'v3.0 and older' , 'version' => '3005', 'url' => 'https://www.newsletter2go.de/plugins/wordpress/wp_v3_0_05.zip']),
                    new IntegrationPlugin(['id' => '5', 'editionFlag' => 'v4.6'           , 'version' => '4006', 'url' => 'https://www.newsletter2go.de/plugins/wordpress/wp_latest.zip']),
                ],
                'connectors' => [
                    new IntegrationConnector(['id' => '6', 'version' => '3000']),
                ],
            ])
        ]
    ]),
];

return $sampleData;
