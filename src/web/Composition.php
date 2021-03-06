<?php

namespace luya\web;

use Yii;
use Exception;
use luya\helpers\Url;
use luya\web\CompositionAfterSetEvent;

/**
 * Luya Composition Component to provide i18n/language handling.
 * 
 * @since 1.0.0-beta1 Implementation of ArrayAccess.
 *
 * @author nadar
 */
class Composition extends \yii\base\Component implements \ArrayAccess
{
    const EVENT_AFTER_SET = 'EVENT_AFTER_SET';
    
    /**
     * @var string The Regular-Expression matching the var finder inside the url parts
     */
    const VAR_MATCH_REGEX = '/<(\w+):?([^>]+)?>/';

    /**
     * @var \yii\web\Request Request-Object container from DI
     */
    public $request = null;

    /**
     * @var bool Enable or disable the->getFull() prefix. If disabled the response of getFull() would be empty, otherwhise it
     *           returns the full prefix composition pattern based url.
     */
    public $hidden = false;

    /**
     * @var string Url matching prefix, which is used for all the modules (e.g. an e-store requireds a language
     *             as the cms needs this informations too). After proccessing this informations, they will be removed
     *             from the url for further proccessing.
     *
     * The fullqualified composer key will be stored in `$request->get('urlPrefixCompositionKey')`.
     *
     * Examples of how to use urlPrefixComposition
     * 
     * ```
     * $urlPrefixComposition = '<langShortCode:[a-z]{2}>/<countryShortCode:[a-z]{2}>'; // de/ch; fr/ch
     * ```
     */
    public $pattern = '<langShortCode:[a-z]{2}>';

    /**
     * @var array Default value if there is no composition provided in the url. The default value must match the url.
     */
    public $default = ['langShortCode' => 'en'];

    /**
     * @var array Read-Only property, contains all composition key value paringins
     */
    private $_composition = [];
    
    private $_compositionKeys = [];

    /**    
     * Class constructor, to get data from DiContainer.
     * 
     * @param \luya\web\Request $request
     * @param array             $config
     */
    public function __construct(\luya\web\Request $request, array $config = [])
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * 
     */
    public function getDefaultLangShortCode()
    {
        return $this->default['langShortCode'];
    }

    /**
     * Resolve the the composition on init.
     */
    public function init()
    {
        // check if the required key langShortCode exist in the default array.
        if (!array_key_exists('langShortCode', $this->default)) {
            throw new Exception("The composition default rule must contain a langShortCode.");
        }
        // atach event to component
        $this->on(self::EVENT_AFTER_SET, [$this, 'eventAfterSet']);
        // resolved data
        $resolve = $this->getResolvedPathInfo($this->request);
        // set initializer comosition 
        foreach ($resolve['compositionKeys'] as $key => $value) {
            $this->setKey($key, $value);
        }
        $this->_compositionKeys = $resolve['keys'];
    }

    /**
     * This event will method will triggere after setKey method is proccessed. The
     * main purpose of this function to change the localisation based on the required
     * key 'langShortCode'.
     * 
     * @param luya\web\CompositionAfterSetEvent $event
     */
    public function eventAfterSet($event)
    {
        if ($event->key == 'langShortCode') {
            Yii::$app->language = $event->value;
            setlocale(LC_ALL, $this->locale, $this->locale.'.utf8');
        }
    }
    
    /**
     * Resolve the current url request and retun an array contain resolved route and the resolved values.
     *
     * @param \yii\web\Request $request
     *
     * @return array An array containing the route and the resolvedValues. Example array output when request path is `de/hello/world`:
     * 
     * ```php
     * [
     *     'route' => 'hello/world',
     *     'resolvedValues' => [
     *         0 => 'de'
     *     ],
     * ]
     * ```
     */
    public function getResolvedPathInfo(\yii\web\Request $request)
    {
        // contains all resolved values
        $resolvedValues = [];
        $foundKeys = [];
        // array with all url parts, seperated by slash
        $requestUrlParts = explode('/', $request->pathInfo);
        // catch all results matching the var match regular expression
        preg_match_all(static::VAR_MATCH_REGEX, $this->pattern, $matches, PREG_SET_ORDER);
        // get all matches
        foreach ($matches as $index => $match) {
            $foundKeys[] = $match[1];
            // check if the index of the match is existing in the requestUrlParts array as the match always
            // starts from the begin of a string.
            if (isset($requestUrlParts[$index])) {
                $requestUrlValue = $requestUrlParts[$index];
                // check if the value of the requeste url value matches the regex of the compositoin
                if (preg_match('/^'.$match[2].'$/', $requestUrlValue)) {
                    $resolvedValues[$match[1]] = $requestUrlValue;
                    // cause the part is matched by the composition, we have to unset the key from the array.
                    unset($requestUrlParts[$index]);
                }
            }
        }
        // get default values if nothing have been resolved
        if (count($resolvedValues) == 0) {
            $keys = $this->default;
        } else {
            $keys = $resolvedValues;
        }
        // return array with route and resolvedValues
        return ['route' => implode('/', $requestUrlParts), 'resolvedValues' => $resolvedValues, 'compositionKeys' => $keys, 'keys' => $foundKeys];
    }

    /**
     * Set a new composition key and value in composition array. If the key already exists, it will
     * be overwritten. The setKey method triggers the CompositionAfterSetEvent class.
     *
     * @param string $key   The key in the array, e.g. langShortCode
     * @param string $value The value coresponding to the key e.g. de
     */
    public function setKey($key, $value)
    {
        // set and override composition
        $this->_composition[$key] = $value;
        // trigger event
        $event = new CompositionAfterSetEvent();
        $event->key = $key;
        $event->value = $value;
        $this->trigger(self::EVENT_AFTER_SET, $event);
    }

    /**
     * Get value from the composition array for the provided key, if the key does not existing the default value
     * will be return. The standard value of the defaultValue is false, so if nothing defined and the could not
     * be found, the return value is `false`.
     *
     * @param string $key          The key to find in the composition array e.g. langShortCode
     * @param string $defaultValue The default value if they could not be found
     *
     * @return string|bool
     */
    public function getKey($key, $defaultValue = false)
    {
        return (isset($this->_composition[$key])) ? $this->_composition[$key] : $defaultValue;
    }

    /**
     * Return the whole composition array.
     *
     * @return array
     */
    public function get()
    {
        return $this->_composition;
    }

    /**
     * Return a path like string with all composition with trailing slash e.g. us/e.
     *
     * @return void|string
     */
    public function getFull()
    {
        return $this->createRouteEnsure();
    }
    
    /**
     * create a route but ensures if composition is hidden anywho.
     * 
     * @param array $overrideKeys
     * @return string
     */
    public function createRouteEnsure(array $overrideKeys = [])
    {
        return ($this->hidden) ? '' : $this->createRoute($overrideKeys);
    }
    
    /**
     * Create compositon route based on the provided keys (to override), if no keys provided
     * all the default values will be used.
     * 
     * @param array $overrideKeys
     * @return string
     */
    public function createRoute(array $overrideKeys = [])
    {
        $composition = $this->_composition;
        
        foreach ($overrideKeys as $key => $value) {
            if (in_array($key, $this->_compositionKeys)) {
                $composition[$key] = $value;
            }
        }
        
        return implode('/', $composition);
    }

    /**
     * Prepend to current composition (or to provided composition prefix-route) to a given route.
     *
     * @param string $route The route where the composition prefix should be prepended.
     * @param null|string $prefix Define the value you want to prepend to the route or not.
     * @return string
     */
    public function prependTo($route, $prefix = null)
    {
        if ($prefix === null) {
            $prefix = $this->getFull();
        }
        
        if (empty($prefix)) {
            return $route;
        }

        $prepend = '';

        if (substr($route, 0, 1) == '/') {
            $prepend = '/';
        }

        return $prepend.$prefix.'/'.ltrim($route, '/');
    }

    public function removeFrom($route)
    {
        $pattern = preg_quote($this->getFull().'/');

        return preg_replace("#$pattern#", '', $route, 1);
    }

    /**
     * Transform the langShortCode into a locale sign to set php env settings.
     *
     * @return string
     */
    public function getLocale()
    {
        switch ($this->getKey('langShortCode')) {
            case 'de':
                return 'de_DE';
                break;
            case 'en':
                return 'en_EN';
                break;
            case 'fr':
                return 'fr_FR';
                break;
            case 'it':
                return 'it_IT';
                break;
        }
    }

    /**
     * Wrapper for `getKey('langShortCode')` to load language to set php env settings.
     *
     * @return string|bool
     */
    public function getLanguage()
    {
        return $this->getKey('langShortCode');
    }

    // ArrayAccess implentation

    public function offsetExists($offset)
    {
        return isset($this->_composition[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new Exception('Unable to set array value without key. Empty keys not allowed.');
        }
        $this->setKey($offset, $value);
    }

    public function offsetGet($offset)
    {
        return $this->getKey($offset, null);
    }

    public function offsetUnset($offset)
    {
        throw new Exception('Deleting keys in Composition is not allowed.');
    }
}
