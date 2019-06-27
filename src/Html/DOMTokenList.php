<?php

namespace Dot\Html;

use ArrayIterator;

class DOMTokenList
{
    /**
     * @var int
     */
    public $length;
    /**
     * @var string
     */
    public $value;

    protected $_tokens = [];

    protected function _updateProperty(): self
    {
        $_tokens = &$this->_tokens;
        $this->length = count($_tokens);
        $this->value = implode(' ', $_tokens);
        return $this;
    }

    function add(string ...$tokens)
    {
        array_push($this->_tokens, ...$tokens);
        $this->_updateProperty();
        return $this;
    }


    function contains(string $token): boolean
    {
        return in_array($token, $this->_tokens);
    }

    function entries(): ArrayIterator
    {
        return $this->_tokens;
    }

//    function forEach()
//    {
//
//    }

    function item(int $index)
    {
        return $this->_tokens[$index] ?? null;
    }

    function keys()
    {
        return array_keys($this->_tokens);
    }

    function remove(string ...$tokens): self
    {
        $_tokens = &$this->_tokens;
        $removedTokens = [];
        foreach ($this->_tokens as $token) {
            if (!in_array($token, $tokens)) {
                $removedTokens[] = $token;
            }
        }
        $_tokens = $removedTokens;
        return $this->_updateProperty();
    }

    function replace(string $before, string $after): self
    {
        $_tokens = &$this->_tokens;
        $indexOf = array_search($before, $_tokens, true);
        if ($indexOf) {
            $_tokens[$indexOf] = $after;
        }
        return $this;
    }

    /*
    function supports(string $token)
    {
    return $this->contains($token);
    }
    */

    function toggle(string $token, bool $force = false)
    {
        $_tokens = &$this->_tokens;
        $indexOf = array_search($token, $_tokens, true);
        if ($indexOf && $force) {
            $this->remove($token);
            return false;
        }
        $this->add($token);
        return true;
    }

    /**
     * @return ArrayIterator
     * @todo need more study of this
     */
    function values()
    {
        return $this->_tokens;
    }
}