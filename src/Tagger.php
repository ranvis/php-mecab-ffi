<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

class Tagger
{
    use TokenIssuerTrait;

    private Env $env;
    private FFI\CData $tagger;

    /**
     * Instantiate Tagger.
     *
     * @param Env|Model $origin Env or Model instance that Tagger depends on.
     * @param array|string $args Option arguments for Env.
     * This should be the default [] if $origin is Model.
     */
    public function __construct(
        Env|Model $origin,
        array|string $args = [],
    ) {
        if ($origin instanceof Env) {
            $this->env = $origin;
            $lib = $origin->lib();
            if (!is_array($args)) {
                // N.B. empty string != no args
                $tagger = $lib->mecab_new2($args);
            } else {
                array_unshift($args, 'mecab');  // A program name as we have in argv[0].
                [$argsList, $gc] = FfiUtil::newArgs($args);
                $tagger = $lib->mecab_new(count($args), $argsList);
                unset($argsList, $gc);
            }
        } else {
            if ($args !== []) {
                throw new \InvalidArgumentException('Args cannot be specified with Model');
            }
            $this->env = $origin->getEnv();
            $tagger = $this->env->lib()->mecab_model_new_tagger($origin);
        }
        if ($tagger === null) {
            $message = $this->env->lib()->mecab_strerror(null);
            if ($message === '') {  // https://github.com/taku910/mecab/issues/57
                $message = 'Could not instantiate Tagger';
            }
            throw new \InvalidArgumentException($message);
        }
        $this->tagger = $tagger;
    }

    public function __destruct()
    {
        $this->freeToken();
        $this->env->lib()->mecab_destroy($this->tagger);
    }

    public function getLastError(): string
    {
        return $this->env->lib()->mecab_strerror($this->tagger);
    }

    public function parseLattice(Lattice $lattice): bool
    {
        return (bool)$this->env->lib()->mecab_parse_lattice($this->tagger, $lattice->getObject());
    }

    /**
     * Parse string and return into string.
     *
     * @param string $str A string to parse.
     * @return string The result string.
     */
    public function parse(string $str): string
    {
        $strCharP = FfiUtil::newBuffer($str);
        $result = $this->env->lib()->mecab_sparse_tostr2($this->tagger, $strCharP, strlen($str));
        return $result;
    }

    /**
     * Parse string and return head node.
     *
     * @param string $str A string to parse.
     * @return Node The head node of the result.
     */
    public function parseToNode(string $str): Node
    {
        $token = $this->changeToken();
        $strCharP = FfiUtil::newBuffer($str); // node.surface references this buffer
        $token->addChild($strCharP);
        $factory = new NodeFactory();
        $node = $factory->create($this->env->lib()->mecab_sparse_tonode2($this->tagger, $strCharP, strlen($str)), $token);
        return $node;
    }

    /**
     * Get information of the dictionaries.
     *
     * @return DictionaryInfo[] An array of dictionary information.
     */
    public function getDictionaryInfo(): array
    {
        $info = $this->env->lib()->mecab_dictionary_info($this->tagger);
        return DictionaryInfo::createList($info);
    }
}
