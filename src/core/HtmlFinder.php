<?php

namespace app\core;

use DOMDocument;
use DomXPath;
use DOMNodeList;
use DOMNode;
use yii\base\BaseObject;

class HtmlFinder extends BaseObject
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $nodesXPath;

    /**
     * @var DOMDocument
     */
    public $dom;

    /**
     * @var DomXPath
     */
    public $domXPath;

    /**
     * @var DOMNodeList
     */
    public $nodes;

    public function init()
    {
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        if (!$this->content) {
            $this->content = file_get_contents($this->url);
        }
        $this->dom->loadHTML($this->content);
        $this->domXPath = new DomXPath($this->dom);
        $this->nodes = $this->domXPath->query($this->nodesXPath);
    }

    public function getText(DOMNode $node, string $xpath)
    {
        $result = $this->domXPath->query($xpath, $node);
        return count($result) ? $result[0]->textContent : null;
    }

    public function getAttribute(DOMNode $node, string $xpath, string $attribute)
    {
        $result = $this->domXPath->query($xpath, $node);
        return count($result) ? $result[0]->getAttribute($attribute) : null;
    }
}
